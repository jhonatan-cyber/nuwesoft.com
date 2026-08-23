<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\ContactMessage;
use App\Services\EntityCacheManager;

class ContactMessageObserver
{
    /**
     * Flush message cache and broadcast entity update after save.
     */
    public function saved(ContactMessage $message): void
    {
        EntityCacheManager::flushEntity('message');
        event(new EntityUpdated('message'));
    }

    /**
     * Flush message cache and broadcast entity update after delete.
     */
    public function deleted(ContactMessage $message): void
    {
        EntityCacheManager::flushEntity('message');
        event(new EntityUpdated('message'));
    }
}
