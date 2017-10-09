<?php

namespace App\Jobs;

use App\Events\WebsiteEvent;
use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChangeWebsiteBranch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $website;
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
            # Update Deploy Code
            $hook_path = "{$this->website->git_root}/hooks/post-receive";
            \File::put($hook_path, view('scripts.post-receive', ['website' => $this->website]));
        }

        broadcast(new WebsiteEvent($this->website, WebsiteEvent::BRANCH_CHANGE));
    }
}
