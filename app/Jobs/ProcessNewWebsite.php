<?php

namespace App\Jobs;

use App\Events\WebsiteEvent;
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
    protected $servername;
    public function __construct(Website $website, $servername)
    {
        $this->website = $website;
        $this->servername = $servername;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $website = $this->website;
        $ip = env('APP_IP', '127.0.0.1');
        if (env('APP_ENV') == 'local') {
            $base_path = storage_path('app') . "/home/{$website->username}";
            if (!\File::exists($base_path)) {
                \File::makeDirectory($base_path, 777, true);
            }
        } else {
            $base_path = "/home/{$website->username}";
            # Create Website User in System
            shell_exec("sudo useradd -p `mkpasswd \"{$website->password}\"` -d /home/\"{$website->username}\" -m -s /bin/bash \"{$website->username}\"");
        }

        # Create Directory to Store Deploy source and Version Control
        $website->document_root = "{$base_path}/deploy";
        $website->deploy_path = $website->document_root;
        $website->git_root = "{$base_path}/{$website->username}.git";
        $website->git_remote_url = "{$website->username}@{$ip}:{$website->username}.git";
        \File::makeDirectory($website->git_root, 770);
        \File::makeDirectory($website->document_root, 770);

        # Git Init Bare
        shell_exec("git init --bare {$website->git_root}");

        # Create Deploy Code
        $hook_path = "{$website->git_root}/hooks/post-receive";
        \File::put($hook_path, view('scripts.post-receive', compact('website')));

        # Change Folder Permission
        chmodr("{$base_path}", 0760);
        chmod($hook_path, 0770);
        chgrpr("{$base_path}", $website->username);
        chownr("{$base_path}", $website->username);

        # Create Sample Virtual Host Config And Store in Storage
        if ($website->type == "Laravel") $website->document_root .= "/public";

        # Store apache config
        $servername = $this->servername;
        $apache_config = view('scripts.sample_apache_config', compact('website', 'servername', 'base_path'))->render();
        $apache_path = "apache_config/{$website->id}-{$website->username}.conf";
        \Storage::drive('local')->put($apache_path, $apache_config);
        $website->apache_path = storage_path('app/' . $apache_path);

            # Save Website Changes
        $website->save();

        broadcast(new WebsiteEvent($website, WebsiteEvent::CREATED));
    }
}
