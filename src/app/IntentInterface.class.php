<?php


namespace Solidjobs\Intent;


use Solidjobs\Intent\IntentModels\IntentModel;

interface IntentInterface
{

    /**
     * @todo Intent interface for exec method
     */

    /**
     * @param IntentModel $intentModel
     * @return mixed
     */
    public function runIntent(IntentModel $intentModel);
}
