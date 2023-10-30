<?php

namespace App\Jobs;

use App\Jobs\OperationalJob;
use App\Http\Controllers\TopicController;
use Exception;

class ProcessTopic extends OperationalJob
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
                TopicController::store($this->request_data);
                break;
            case 'delete':
                TopicController::delete($this->request_data);
                break;
            default:
                $err = '"'.$this->op.'" op is not defined!';
                throw new Exception($err);
        }
    }
}
