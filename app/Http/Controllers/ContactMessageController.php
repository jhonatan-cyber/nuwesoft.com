<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->get('filter') === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->get('filter') === 'read') {
            $query->whereNotNull('read_at');
        }

        $messages = $query->paginate($request->input('per_page', 20))->withQueryString();
        $unreadCount = ContactMessage::whereNull('read_at')->count();

        return Inertia::render('Dashboard/Messages/Index', [
            'messages'    => $messages,
            'unreadCount' => $unreadCount,
            'filters'     => $request->only(['filter']),
        ]);
    }

    public function show(ContactMessage $message)
    {
        // Mark as read on open
        if (! $message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return Inertia::render('Dashboard/Messages/Show', [
            'message' => $message,
        ]);
    }

    public function markAsRead(ContactMessage $message)
    {
        $message->update(['read_at' => now()]);
        return back();
    }

    public function markAsUnread(ContactMessage $message)
    {
        $message->update(['read_at' => null]);
        return back();
    }

    public function markAllAsRead()
    {
        ContactMessage::whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Todos los mensajes marcados como leídos.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('messages.index')->with('success', 'Mensaje eliminado.');
    }
}
