<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Http\Controllers\AuctionParticipantsController;
use Illuminate\Support\Facades\Log;

class ProcessAuctionParticipant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request_data)
    {
        $this->request_data = $request_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AuctionParticipantsController::store($this->request_data);
    }
}
