<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Symfony\Component\Process\Process;

class ProjectController extends Controller
{
    public function analyzeTechnologies(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url:http,https', 'max:500'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:1000'],
            'credentials' => ['nullable', 'array', 'max:20'],
            'credentials.*' => ['nullable', 'string', 'max:1000'],
        ]);

        $host = parse_url($validated['url'], PHP_URL_HOST);
        $ips = $host ? gethostbynamel($host) : false;
        if (! $ips || collect($ips)->contains(fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false)) {
            return response()->json(['message' => 'La URL debe apuntar a un servidor público.'], 422);
        }

        try {
            $client = Http::timeout(15)->connectTimeout(6)->withoutRedirecting()
                ->withHeaders(['User-Agent' => 'Nuwesoft Technology Analyzer/1.0', 'Accept' => 'text/html,application/xhtml+xml']);
            if (! empty($validated['username'])) {
                $client = $client->withBasicAuth($validated['username'], $validated['password'] ?? '');
            }
            $response = $client->get($validated['url']);
        } catch (\Throwable) {
            return response()->json(['message' => 'No se pudo conectar con el sitio.'], 422);
        }

        if (in_array($response->status(), [401, 403], true)) {
            return response()->json(['needs_credentials' => true, 'message' => 'El sitio requiere autenticación.'], 401);
        }
        if (! $response->successful() && ! $response->redirect()) {
            return response()->json(['message' => "El sitio respondió HTTP {$response->status()}."], 422);
        }

        $haystack = strtolower($response->body() . ' ' . json_encode($response->headers()));
        $signatures = [
            'Laravel' => ['laravel_session', 'laravel', 'csrf-token'],
            'Vue.js' => ['data-v-', '__vue__', 'vue.js', 'vue.runtime'],
            'React' => ['reactroot', '__next_data__', 'react-dom'],
            'Next.js' => ['__next_data__', '/_next/static/'],
            'Nuxt.js' => ['__nuxt__', '/_nuxt/'],
            'WordPress' => ['wp-content', 'wp-includes'],
            'Tailwind' => ['tailwind', '--tw-'],
            'Bootstrap' => ['bootstrap.min.css', 'bootstrap.bundle'],
            'jQuery' => ['jquery.min.js', 'jquery-'],
            'Cloudflare' => ['cf-ray', '__cf_bm', 'cloudflare'],
            'PHP' => ['phpsessid', 'x-powered-by: php'],
            'Node.js' => ['x-powered-by: express', 'connect.sid'],
        ];

        $detected = collect($signatures)
            ->filter(fn ($needles) => collect($needles)->contains(fn ($needle) => str_contains($haystack, $needle)))
            ->keys();
        $technologies = \App\Models\Technology::where('is_active', true)->get(['id', 'name']);
        $matches = $technologies->filter(fn ($technology) => $detected->contains(fn ($name) => strcasecmp($name, $technology->name) === 0));
        $authenticationFields = $this->detectAuthenticationFields($response->body());
        $pageDescription = $this->extractPageDescription($response->body());
        $captures = [];
        $submittedCredentials = collect($validated['credentials'] ?? [])->contains(fn ($value) => filled($value));

        if ($authenticationFields !== [] && $submittedCredentials) {
            $captureResult = $this->captureAuthenticatedPages(
                $validated['url'],
                $authenticationFields,
                $validated['credentials'] ?? [],
            );

            if (isset($captureResult['error'])) {
                return response()->json(['message' => $captureResult['error']], 422);
            }

            $captures = $captureResult['captures'] ?? [];
        }

        return response()->json([
            'needs_credentials' => $authenticationFields !== [] && ! $submittedCredentials,
            'authentication_type' => $authenticationFields === [] ? null : 'form',
            'authentication_fields' => $authenticationFields,
            'page_description' => $pageDescription,
            'captures' => $captures,
            'pages_captured' => count($captures),
            'detected' => $detected->values(),
            'technology_ids' => $matches->pluck('id')->values(),
            'status' => $response->status(),
        ]);
    }

    private function captureAuthenticatedPages(string $url, array $fields, array $credentials): array
    {
        $payloadFields = collect($fields)->map(fn ($field) => [
            'type' => $field['type'],
            'value' => $credentials[$field['name']] ?? null,
        ])->values()->all();

        try {
            $command = PHP_OS_FAMILY === 'Windows'
                ? ['cmd.exe', '/D', '/S', '/C', base_path('scripts/run-browser-capture.cmd')]
                : [config('services.browser_capture.node_binary', 'node'), base_path('scripts/browser-capture.mjs')];
            $process = new Process(
                $command,
                base_path(),
                [
                    'OPENSSL_CONF' => false,
                    'ELECTRON_RUN_AS_NODE' => false,
                    'NODE_OPTIONS' => false,
                ],
            );
            $process->setInput(json_encode(['url' => $url, 'fields' => $payloadFields], JSON_THROW_ON_ERROR));
            $process->setTimeout(150);
            $process->run();

            if (! $process->isSuccessful()) {
                $details = trim($process->getErrorOutput());
                Log::warning('Project browser capture failed', [
                    'host' => parse_url($url, PHP_URL_HOST),
                    'exit_code' => $process->getExitCode(),
                    'error' => mb_substr($details, 0, 500),
                ]);

                return ['error' => $details !== '' ? mb_substr($details, 0, 500) : 'No se pudo ejecutar el navegador de análisis.'];
            }

            $result = json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR);

            return is_array($result) ? $result : ['error' => 'El navegador devolvió una respuesta inválida.'];
        } catch (\Throwable $exception) {
            report($exception);

            return ['error' => 'No se pudieron capturar las páginas del sistema.'];
        }
    }

    private function detectAuthenticationFields(string $html): array
    {
        if (! str_contains(strtolower($html), '<input')) {
            return [];
        }

        $document = new \DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $loaded = $document->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return [];
        }

        $xpath = new \DOMXPath($document);
        $passwordInputs = $xpath->query('//input[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="password"]');
        if (! $passwordInputs || $passwordInputs->length === 0) {
            return [];
        }

        $form = $passwordInputs->item(0)?->parentNode;
        while ($form instanceof \DOMElement && strtolower($form->tagName) !== 'form') {
            $form = $form->parentNode;
        }

        $scope = $form instanceof \DOMElement ? $form : $document;
        $inputs = $xpath->query('.//input', $scope);
        $fields = [];

        foreach ($inputs ?: [] as $index => $input) {
            if (! $input instanceof \DOMElement) {
                continue;
            }

            $type = strtolower($input->getAttribute('type') ?: 'text');
            if (! in_array($type, ['text', 'email', 'password', 'tel'], true)) {
                continue;
            }

            $name = $input->getAttribute('name') ?: $input->getAttribute('id') ?: "field_{$index}";
            $placeholder = trim($input->getAttribute('placeholder'));
            $label = $placeholder ?: match ($type) {
                'email' => 'Correo electrónico',
                'password' => 'Contraseña',
                'tel' => 'Teléfono',
                default => 'Usuario',
            };

            $fields[] = [
                'name' => $name,
                'type' => $type,
                'label' => $label,
                'autocomplete' => $input->getAttribute('autocomplete') ?: ($type === 'password' ? 'current-password' : 'username'),
                'required' => $input->hasAttribute('required') || $type === 'password',
            ];
        }

        return $fields;
    }

    private function extractPageDescription(string $html): ?string
    {
        $document = new \DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $loaded = $document->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return null;
        }

        $xpath = new \DOMXPath($document);
        $queries = [
            '//meta[translate(@property, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="og:description"]/@content',
            '//meta[translate(@name, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="description"]/@content',
            '//meta[translate(@name, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="twitter:description"]/@content',
        ];

        foreach ($queries as $query) {
            $value = $xpath->query($query)?->item(0)?->nodeValue;
            $description = $this->cleanPageText($value);
            if ($description !== null) {
                return $description;
            }
        }

        foreach ($xpath->query('//script[@type="application/ld+json"]') ?: [] as $script) {
            $data = json_decode($script->nodeValue, true);
            $description = $this->findStructuredDescription($data);
            if ($description !== null) {
                return $description;
            }
        }

        return null;
    }

    private function findStructuredDescription(mixed $data): ?string
    {
        if (! is_array($data)) {
            return null;
        }

        if (isset($data['description']) && is_string($data['description'])) {
            return $this->cleanPageText($data['description']);
        }

        foreach ($data as $value) {
            if (is_array($value) && ($description = $this->findStructuredDescription($value)) !== null) {
                return $description;
            }
        }

        return null;
    }

    private function cleanPageText(?string $value): ?string
    {
        $value = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');

        return $value === '' ? null : mb_substr($value, 0, 1000);
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Project::with(['images', 'technologies']);

        // Server-side search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('desc', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Server-side sort
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortField, ['name', 'category', 'created_at', 'updated_at'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        $projects = $query->paginate($perPage)->withQueryString();
        $technologies = \App\Models\Technology::where('is_active', true)->get();

        return Inertia::render('Dashboard/Projects/Index', [
            'projects' => $projects,
            'technologies' => $technologies,
            'filters' => $request->only(['search', 'sort_field', 'sort_order']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Projects/Form');
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $project = Project::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'desc' => $validated['desc'] ?? '',
            'icon' => $validated['icon'] ?? 'Briefcase',
            'project_url' => $validated['project_url'] ?? '',
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if ($request->has('technologies')) {
            $project->technologies()->sync($validated['technologies']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $project->uploadImage($image);
                } catch (\Throwable $e) {
                    report($e);

                    return back()->withErrors(['images' => 'Error al subir una o más imágenes. Intenta de nuevo.']);
                }
            }
        }

        return Redirect::route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $project->load('images');

        return Inertia::render('Dashboard/Projects/Form', [
            'project' => (new ProjectResource($project))->resolve(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

        $project->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'desc' => $validated['desc'] ?? '',
            'icon' => $validated['icon'] ?? 'Briefcase',
            'project_url' => $validated['project_url'] ?? '',
            'is_active' => $validated['is_active'] ?? $project->is_active,
        ]);

        if ($request->has('technologies')) {
            $project->technologies()->sync($validated['technologies']);
        }

        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $imageId) {
                try {
                    $project->deleteImage($imageId);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $project->uploadImage($image);
                } catch (\Throwable $e) {
                    report($e);

                    return back()->withErrors(['images' => 'Error al subir una o más imágenes. Intenta de nuevo.']);
                }
            }
        }

        return Redirect::route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->deleteAllImages();
        $project->delete();

        return Redirect::route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function publicIndex()
    {
        $projects = Project::with(['images', 'technologies'])
            ->where('is_active', true)
            ->latest('created_at')
            ->get();

        return ProjectResource::collection($projects);
    }

    public function publicShow(Project $project)
    {
        abort_if(! $project->is_active, 404);

        $project->load(['images', 'technologies']);

        return Inertia::render('PortfolioProjectDetail', [
            'project' => $project,
        ]);
    }
}
