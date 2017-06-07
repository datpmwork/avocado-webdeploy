<?php

namespace App\Jobs;

use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SwitchWebsiteState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $website;
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
            if ($this->website->is_on) {
                \File::link($this->website->apache_path, '/etc/apache2/site-enabled/' . $this->website->apache_file);
            } else {
                unlink('/etc/apache2/site-enabled/' . $this->website->apache_file);
            }
            dispatch(new RestartApache($this->website));
        }
    }
}
