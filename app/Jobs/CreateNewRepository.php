<?php

namespace App\Jobs;

use App\Events\WebsiteEvent;
use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateNewRepository implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $website;
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
        $website = $this->website;
        
        # Create new Gitlab Repository
        $client = \Gitlab\Client::create('https://gitlab.com')
            ->authenticate(config('services.gitlab.token'), \Gitlab\Client::AUTH_URL_TOKEN);
    
        $project = $client->api('projects')->create($website->username, array(
            'namespace_id' => config('services.gitlab.namespace'),
            'description' => $website->name,
            'issues_enabled' => true,
            'visibility' => 'private',
            'container_registry_enabled' => true,
            'merge_requests_enabled' => true
        ));
        
        $website->git_remote_url = $project["ssh_url_to_repo"];
        $website->save();
    }
}
