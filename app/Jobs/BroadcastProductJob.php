<?php

namespace App\Jobs;

use App\Models\ExternalSuppliers;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Mockery\Exception;

class BroadcastProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ExternalSuppliers $external,
        public Product $product,
        public $changes
    )
    {
        $this->onQueue('suppliers');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            dump(
                [
                    ...$this->product->toArray(),
                    'changes' => $this->changes
                ]
            );
            $response = Http::post($this->external->url, [
                ...$this->product->toArray(),
                'changes' => $this->changes
            ]);

            if(!$response->ok()) {
                $this->fail();
            }
        }catch (Exception $exception) {
            logger($exception->getMessage());
            logger($this->external->name .' failed on ' . $this->product->id);
        }

    }
}
