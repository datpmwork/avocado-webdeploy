<?php

namespace App\Jobs;

use App\Events\ApacheRestarted;
use App\Events\WebsiteEvent;
use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RestartApache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $website;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (env('APP_ENV') != 'local') {
            shell_exec('service apache2 reload');
            $this->website->activity_logs .= "Apache has been reloaded\n";
            $this->website->save();
        }

        broadcast(new WebsiteEvent($this->website));
    }
}
