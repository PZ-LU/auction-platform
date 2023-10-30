<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class OperationalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request_data, $op;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request_data, $op)
    {
        $this->request_data = $request_data;
        $this->op = $op;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    abstract public function handle();
}
