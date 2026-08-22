<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Cache;

class ContactMessageObserver
{
    /**
     * Flush message cache and broadcast entity update after save.
     */
    public function saved(ContactMessage $message): void
    {
        Cache::forget('dashboard.pending_messages');
        event(new EntityUpdated('message'));
    }

    /**
     * Flush message cache and broadcast entity update after delete.
     */
    public function deleted(ContactMessage $message): void
    {
        Cache::forget('dashboard.pending_messages');
        event(new EntityUpdated('message'));
    }
}
