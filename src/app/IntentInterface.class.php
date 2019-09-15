<?php


namespace Solidjobs\Intent;


use Solidjobs\Intent\IntentModels\ResponseModel;

interface IntentInterface
{

    /**
     * @todo Intent interface for exec method
     */

    /**
     * @param ResponseModel $intentModel
     * @return mixed
     */
    public function runIntent(ResponseModel $intentModel);
}
