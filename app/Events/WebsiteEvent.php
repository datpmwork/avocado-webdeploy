<?php

namespace App\Events;

use App\Website;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebsiteEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const CREATED = "Congrats. Website has been created\n";
    const DOWN = "Website has been stopped\n";
    const UP = "Website has been started\n";
    const APACHE_CHANGE = "Apache config has been changed\n";
    const DEPLOY_CHANGE = "Deploy scripts has been changed\n";
    const BRANCH_CHANGE = "Default checkout branch has been changed\n";

    public $website;
    protected $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Website $website, $message)
    {
        $this->message = $message;
        $website->activity_logs .= date('\[d/m/Y H:i:s\]: ') . $message;
        $website->save();
        $this->website = $website;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("website_channel_" . $this->website->id);
    }

    public function broadcastAs() {
        switch ($this->message) {
            case WebsiteEvent::APACHE_CHANGE:
                return 'apache_changed';
            case WebsiteEvent::DEPLOY_CHANGE:
            case WebsiteEvent::BRANCH_CHANGE:
                return 'deploy_changed';
        }
        return 'website_event';
    }

}
