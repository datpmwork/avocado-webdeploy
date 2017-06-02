<?php

namespace App\Jobs;

use App\Events\WebsiteEvent;
use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChangeWebsiteConfig implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $_path;
    protected $_content;
    protected $_website;
    protected $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Website $website, $path, $content, $message)
    {
        $this->_website = $website;
        $this->_content = $content;
        $this->_path = $path;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \File::put($this->_path, $this->_content);
        broadcast(new WebsiteEvent($this->_website, $this->message));
    }
}
