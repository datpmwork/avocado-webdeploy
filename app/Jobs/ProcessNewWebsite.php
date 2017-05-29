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
        if (env('APP_ENV') == 'local') {
            # Create Directory to Store Deploy source and Version Control
            $website->document_root = "/home/{$website->username}/deploy";
            $website->git_root = "/home/{$website->username}/{$website->username}.git";
            # Create Sample Virtual Host Config And Store in Storage
            if ($website->type == "Laravel") $website->document_root .= "/public";
            $website->save();
        } else {
            $password = env('GLOBAL_PASSWD', '');
            # Create Website User in System
            shell_exec("sshpass -p '{$password}' sudo useradd -p `mkpasswd \"{$website->password}\"` -d /home/\"{$website->username}\" -m -s /bin/bash \"{$website->username}\"");

            # Create Directory to Store Deploy source and Version Control
            $website->document_root = "/home/{$website->username}/deploy";
            $website->git_root = "/home/{$website->username}/{$website->username}.git";
            \File::makeDirectory($website->git_root, 770);
            \File::makeDirectory($website->document_root, 770);

            # Git Init Bare
            shell_exec("git init --bare {$website->git_root}");

            # Create Deploy Code
            $deploy_path = "{$website->git_root}/hooks/post-receive";
            \File::put($deploy_path, view('scripts.post-receive', compact('website')));

            # Change Folder Permission
            chmodr("/home/{$website->username}/", 0760);
            chmod($deploy_path, 0770);
            chgrpr("/home/{$website->username}/", $website->username);
            chownr("/home/{$website->username}/", $website->username);

            # Create Sample Virtual Host Config And Store in Storage
            if ($website->type == "Laravel") $website->document_root .= "/public";

            # Store apache config
            $apache_config = view('scripts.sample_apache_config', compact('website'))->render();
            \Storage::drive('local')->put("{$website->id}-{$website->username}.config", $apache_config);

            # Save Website Changes
            $website->save();
        }
    }
}
