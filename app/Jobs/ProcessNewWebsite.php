<?php

namespace App\Jobs;

use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessNewWebsite implements ShouldQueue
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
        $password = env('GLOBAL_PASSWD', '');
        # Create Website User in System
        shell_exec("sshpass -p '{$password}' sudo useradd -p `mkpasswd \"{$website->password}\"` -d /home/\"{$website->username}\" -m -g users -s /bin/bash \"{$website->username}\"");

        # Create Directory to Store Deploy source and Version Control
        $website->document_root = "/home/{$website->username}/deploy";
        $website->git_root = "/home/{$website->username}/{$website->username}.git";
        \File::makeDirectory($website->git_root, 770, false, true);
        \File::makeDirectory($website->document_root, 770, false, true);

        # Git Init Bare
        exec("cd {$website->git_root}; git init --bare;");

        # Create Deploy Code
        $deploy_path = "{$website->git_root}/hooks/post-receive";
        \File::put($deploy_path, view('scripts.post-receive', compact('website')));

        # Change Folder Permission
        chownr($website->document_root, $website->username);
        chownr($website->git_root, $website->username);
        chownr($deploy_path, $website->username);

        chgrpr($website->document_root, $website->username);
        chgrpr($website->git_root, $website->username);
        chgrpr($deploy_path, $website->username);

        chmodr($website->document_root, "0770");
        chmodr($website->git_root, "0770");
        chmodr($deploy_path, "0770");
    }
}
