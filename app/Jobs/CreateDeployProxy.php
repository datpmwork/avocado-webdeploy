<?php

namespace App\Jobs;

use App\Deployment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateDeployProxy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deployment;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deployment)
    {
        $this->deployment = $deployment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $website = $this->deployment->website;
        
        $deployment = $this->deployment;
        
        $apache_path = $website->apache_path;
    
        $proxy_config = view('scripts.proxy_config', compact('website', 'deployment'))->render();
        
        \Storage::drive('local')->append($apache_path, $proxy_config);
        
        dispatch(new RestartApache($website));
    }
}
