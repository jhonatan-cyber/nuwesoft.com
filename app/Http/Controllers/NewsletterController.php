<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewsletterController extends Controller
{
    /**
     * Admin: List all subscribers.
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 15), 50);
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');

        $query = Subscriber::orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'ilike', "%{$search}%")
                  ->orWhere('name', 'ilike', "%{$search}%");
            });
        }

        $subscribers = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => Subscriber::count(),
            'active' => Subscriber::active()->count(),
            'unsubscribed' => Subscriber::where('status', 'unsubscribed')->count(),
            'this_month' => Subscriber::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return Inertia::render('Dashboard/Subscribers/Index', [
            'subscribers' => $subscribers,
            'stats' => $stats,
            'currentStatus' => $status,
            'currentSearch' => $search,
        ]);
    }

    /**
     * Admin: Delete a subscriber.
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'Suscriptor eliminado.');
    }

    /**
     * Admin: Bulk delete subscribers.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron suscriptores.');
        }

        Subscriber::whereIn('id', $ids)->delete();

        return back()->with('success', count($ids) . ' suscriptores eliminados.');
    }

    /**
     * Admin: Export subscribers as CSV.
     */
    public function export(Request $request)
    {
        $status = $request->input('status', 'active');

        $query = Subscriber::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $subscribers = $query->orderBy('created_at', 'desc')->get();

        $csv = "email,name,status,source,subscribed_at\n";
        foreach ($subscribers as $s) {
            $csv .= implode(',', [
                '"' . e($s->email) . '"',
                '"' . e($s->name ?? '') . '"',
                $s->status,
                $s->source,
                $s->subscribed_at?->toISOString() ?? '',
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers.csv"',
        ]);
    }
}
