<?php

namespace App\Jobs;

use App\Models\BlogPost; // Додаємо цей рядок
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BlogPostAfterCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var BlogPost
     */
    private $blogPost;

    /**
     * Create a new job instance.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function __construct(BlogPost $blogPost)
    {
        $this->blogPost = $blogPost;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logs()->info("Створено новий запис в блозі [{$this->blogPost->id}]");
    }
}
