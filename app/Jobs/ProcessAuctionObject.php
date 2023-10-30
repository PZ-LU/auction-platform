<?php

namespace App\Jobs;

use App\Jobs\OperationalJob;
use App\Http\Controllers\AuctionObjectController;

class ProcessAuctionObject extends OperationalJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request_data, $op)
    {
        parent::__construct($request_data, $op);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->op) {
            case 'store':
                AuctionObjectController::storeType($this->request_data);
                break;
            // case 'delete': not possible because client expects a confirmation if it is
            //    possible to delete the type 
            default:
                $err = '"'.$this->op.'" op is not defined!';
                throw new Exception($err);
        }
    }
}
