<?php

namespace Solidjobs\Intent\Intents;

use Solidjobs\Intent\IntentInterface;
use Solidjobs\Intent\IntentModels\IntentPayLoadModel;
use Solidjobs\Intent\IntentModels\ResponseModel;

class DefaultWelcomeIntent implements IntentInterface
{

    /**
     * @param ResponseModel $intentModel
     * @return IntentPayLoadModel
     */
    public function runIntent(ResponseModel $intentModel)
    {
        /**
         * Create output model
         */
        $intentPayLoad = new IntentPayLoadModel();

        /**
         * Return the response from DialogFlow
         */
        $intentPayLoad->setFulfillmentText($intentModel->getQueryResult()->getFullfillmentText());
        $intentPayLoad->setFulfillmentMessages($intentModel->getQueryResult()->getFullfillmentMessages());

        return $intentPayLoad;
    }
}
