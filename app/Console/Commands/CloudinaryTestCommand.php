<?php

namespace App\Console\Commands;

use App\Services\CloudinaryStorageService;
use Illuminate\Console\Command;

class CloudinaryTestCommand extends Command
{
    protected $signature = 'cloudinary:test {--url= : URL de imagen para probar optimización}';

    protected $description = 'Verifica configuración Cloudinary y prueba transformaciones';

    public function handle(): int
    {
        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $hasSecret = ! empty(config('cloudinary.api_secret'));

        $this->info('☁️  Cloudinary check — ENV=' . app()->environment());
        $this->line('   cloud_name: ' . ($cloudName ?: '<vacío>'));
        $this->line('   api_key: ' . ($apiKey ? substr($apiKey, 0, 4) . '...' : '<vacío>'));
        $this->line('   api_secret: ' . ($hasSecret ? 'SET' : '<vacío>'));
        $this->line('   secure: ' . (config('cloudinary.secure') ? 'true' : 'false'));
        $this->line('   folder_prefix: ' . config('cloudinary.folder_prefix'));

        if (empty($cloudName)) {
            $this->error('❌ cloud_name vacío — configura CLOUDINARY_CLOUD_NAME o CLOUDINARY_URL');

            return self::FAILURE;
        }

        // Prueba de transformación URL sin subir archivo
        $sample = 'https://res.cloudinary.com/' . $cloudName . '/image/upload/v1/nuwesoft/test.jpg';
        $optimized = str_replace('/upload/', '/upload/f_auto,q_auto/', $sample);
        $thumb = str_replace('/upload/', '/upload/c_fill,w_400,h_300,q_auto,f_auto/', $sample);
        $blur = str_replace('/upload/', '/upload/w_20,e_blur:1000,q_auto,f_auto/', $sample);

        $this->info('🔗 URLs de prueba:');
        $this->line('   original:  ' . $sample);
        $this->line('   optimized: ' . $optimized);
        $this->line('   thumb:     ' . $thumb);
        $this->line('   blur:      ' . $blur);

        // Si se pasa --url, prueba con esa URL
        if ($url = $this->option('url')) {
            $this->info('🔍 Probando URL provista: ' . $url);
            if (! str_contains($url, 'res.cloudinary.com')) {
                $this->warn('   No es Cloudinary — se devolverá sin transformar');
            }
        }

        // Test de servicio instanciable
        try {
            $service = app(CloudinaryStorageService::class);
            $this->info('✅ Servicio CloudinaryStorageService instanciado OK (prefixed folder: ' . $service->prefixedFolder('projects') . ')');
        } catch (\Throwable $e) {
            $this->error('❌ Error instanciando servicio: ' . $e->getMessage());

            return self::FAILURE;
        }

        // Conteo de imágenes en DB
        $count = \App\Models\ProjectImage::count();
        $cloudinaryCount = \App\Models\ProjectImage::where('image_url', 'like', '%res.cloudinary.com%')->count();
        $this->info("📦 ProjectImage en DB: {$count} (Cloudinary: {$cloudinaryCount}, externo: " . ($count - $cloudinaryCount) . ')');

        // Verifica que optimized_image_url funciona
        $sampleModel = \App\Models\ProjectImage::first();
        if ($sampleModel) {
            $this->line('   sample optimized_image_url: ' . $sampleModel->optimized_image_url);
            $this->line('   sample blur_image_url: ' . ($sampleModel->blur_image_url ?? 'n/a'));
        }

        $this->info('✅ Check completo — dev consumiendo Cloudinary: ' . ($cloudinaryCount > 0 ? 'SÍ (' . $cloudinaryCount . ' imágenes)' : 'NO (usa placeholders/picsum)'));

        return self::SUCCESS;
    }
}
