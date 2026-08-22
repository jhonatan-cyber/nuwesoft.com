<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->get('filter') === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->get('filter') === 'read') {
            $query->whereNotNull('read_at');
        }

        $messages = $query->get();

        $filename = 'mensajes-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($messages) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Headers
            fputcsv($handle, ['Nombre', 'Email', 'Mensaje', 'Leído', 'Fecha']);

            foreach ($messages as $msg) {
                fputcsv($handle, [
                    $msg->nombre,
                    $msg->email,
                    $msg->mensaje,
                    $msg->read_at ? 'Sí' : 'No',
                    $msg->created_at?->format('d/m/Y H:i') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
