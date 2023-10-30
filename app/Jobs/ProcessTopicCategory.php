<?php

namespace App\Jobs;

use App\Jobs\OperationalJob;
use App\Http\Controllers\TopicCategoryController;
use Exception;

class ProcessTopicCategory extends OperationalJob
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
                TopicCategoryController::store($this->request_data);
                break;
            case 'delete':
                TopicCategoryController::delete($this->request_data);
                break;
            default:
                $err = '"'.$this->op.'" op is not defined!';
                throw new Exception($err);
        }
    }
}
