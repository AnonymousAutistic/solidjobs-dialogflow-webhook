<?php


namespace SolidJobs\Intent;


use SolidJobs\Intent\IntentModels\IntentPayLoadModel;
use SolidJobs\Intent\IntentModels\ResponseModel;

interface IntentInterface
{

    /**
     * @todo Intent interface for exec method
     */

    /**
     * @param ResponseModel $intentModel
     * @return IntentPayLoadModel
     */
    public function runIntent(ResponseModel $intentModel);
}
