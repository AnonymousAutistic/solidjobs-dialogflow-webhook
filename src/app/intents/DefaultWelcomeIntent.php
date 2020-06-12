<?php

namespace SolidJobs\Intent\Intents;

use Solidjobs\Intent\IntentInterface;
use Solidjobs\Intent\IntentModels\IntentPayLoadModel;
use Solidjobs\Intent\IntentModels\ResponseModel;
use Solidjobs\Intent\Services\SolidjobsAppService;

class DefaultWelcomeIntent implements IntentInterface
{

    /**
     * @param ResponseModel $intentModel
     * @return IntentPayLoadModel
     * @throws \Exception
     */
    public function runIntent(ResponseModel $intentModel)
    {
        /**
         * Create output model
         */
        $intentPayLoad = new IntentPayLoadModel();

        /**
         * Check if there's some personal data set
         */
        if ($this->hasData()) {
            // hi, i see you've some data on your CV,
            // your cv will be overridden with the data that you provide on this session.
            // what's your professional position? Student, worker, gardener...
            $intentPayLoad->setFulfillmentText('');
            $intentPayLoad->setFulfillmentMessages(['text' => ['message' => '']]);
        } else {
            // default fulfillment
            $intentPayLoad->setFulfillmentText($intentModel->getQueryResult()->getFullfillmentText());
            $intentPayLoad->setFulfillmentMessages($intentModel->getQueryResult()->getFullfillmentMessages());
        }

        /**
         * Return the response from DialogFlow
         */
        return $intentPayLoad;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function hasData()
    {
        /**
         * Check if some section has some data
         */
        $hasData = count(SolidjobsAppService::getInstance()->getJobExperiences()) ||
            count(SolidjobsAppService::getInstance()->getTraining()) ||
            count(SolidjobsAppService::getInstance()->getAbilities()) ||
            count(SolidjobsAppService::getInstance()->getLanguages());

        return $hasData;
    }
}
