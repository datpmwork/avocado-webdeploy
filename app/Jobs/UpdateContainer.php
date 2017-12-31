<?php

namespace App\Jobs;

use App\Deployment;
use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateContainer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imageTag;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($_imageTag)
    {
        $this->imageTag = $_imageTag;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        list($username, $ref) = explode(":", $this->imageTag);
        
        $website = Website::query()->where('username', $username)->first();
        
        # If can't find website
        if (!$website) {
            \Log::info("Couldn't find the website information: {$username}");
        }
        
        $deployment = Deployment::query()->firstOrNew([
            "website_id" => $website->id,
            "branch" => $ref
        ]);
        
        if (!$deployment->exists) {
            $deployment->save();
            # Create new Apache Proxy
            dispatch(new CreateDeployProxy($deployment));
        }
        
        try {
            # Pull docker image
            $docker_image = "{$website->username}:{$deployment->branch}";
            $container_name = "{$website->username}_{$deployment->branch}";
    
            passthru("docker pull {$docker_image}");
    
            # Stop existed container
            passthru("docker stop {$container_name}");
            passthru("docker rm {$docker_image}");
    
            # Start a new container
            passthru("docker run -p {$deployment->port}:80 -name {$docker_image} {$docker_image}");
        } catch (\Exception $exception) {
            \Log::info("Failed to deploy {$website->username}:{$deployment->branch}");
        }
    }
}
