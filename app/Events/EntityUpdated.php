<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EntityUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $type  The entity type (e.g. 'settings', 'project', 'technology', 'testimonial', 'post', 'message')
     * @param  array|null  $data  Optional payload data
     */
    public function __construct(
        public string $type,
        public ?array $data = null
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('public-updates'),
        ];
    }

    /**
     * Skip broadcasting when no realtime backend is configured.
     *
     * The observers fire this on every model save. With BROADCAST_CONNECTION
     * set to a websocket server that is not reachable (CI, testing, local
     * without Reverb running) and a sync queue, the Pusher SDK throws a
     * connection error that bubbles up as a 500 and breaks the admin flows.
     */
    public function broadcastWhen(): bool
    {
        $connection = config('broadcasting.default');

        if (in_array($connection, ['null', 'log', ''], true)) {
            return false;
        }

        $driver = config("broadcasting.connections.{$connection}.driver");

        if ($driver === 'reverb' && empty(config("broadcasting.connections.{$connection}.key"))) {
            return false;
        }

        return true;
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'entity.updated';
    }
}
