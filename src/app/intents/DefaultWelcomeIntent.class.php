<?php

namespace Solidjobs\Intent\Intents;

use Solidjobs\Intent\IntentInterface;
use Solidjobs\Intent\IntentModels\ResponseModel;

class DefaultWelcomeIntent implements IntentInterface
{

    /**
     * @param ResponseModel $intentModel
     * @return mixed
     */
    public function runIntent(ResponseModel $intentModel)
    {
        // TODO: Implement runIntent() method.
    }
}
