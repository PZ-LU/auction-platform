<?php

namespace App\Jobs;

use App\Jobs\OperationalJob;
use App\Http\Controllers\AuctionParticipantsController;

class ProcessAuctionParticipantCommercial extends OperationalJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request_data)
    {
        parent::__construct($request_data, null);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AuctionParticipantsController::storeCommercial($this->request_data);
    }
}
