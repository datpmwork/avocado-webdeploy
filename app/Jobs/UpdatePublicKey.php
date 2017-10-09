<?php

namespace App\Jobs;

use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePublicKey implements ShouldQueue
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
        $ssh_folder = "/home/{$this->website->username}/.ssh";
        if (!\File::exists($ssh_folder)) {
            \File::makeDirectory($ssh_folder, 700, true, true);
        }
        file_put_contents("{$ssh_folder}/.authorized_keys", $this->website->authorized_keys);
        chown("{$ssh_folder}/.authorized_keys", $this->website->username);
        chmod("{$ssh_folder}/.authorized_keys", 600);
    }
}
