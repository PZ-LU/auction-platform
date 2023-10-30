<?php

namespace App\Jobs;

use App\Jobs\OperationalJob;
use App\Http\Controllers\OfferController;
use Exception;

class ProcessOffer extends OperationalJob
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
            // case 'store': is not possible with current implementation as incoming
            //   files simply cannot be serialized into a single job dispatch
            case 'softDelete':
                OfferController::softDelete($this->request_data);
                break;
            default:
                $err = '"'.$this->op.'" op is not defined!';
                throw new Exception($err);
        }
    }
}
