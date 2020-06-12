<?php

namespace SolidJobs\Intent;

use SolidJobs\Intent\IntentModels\IntentPayLoadModel;
use SolidJobs\Intent\IntentModels\ResponseModel;
use SolidJobs\Intent\Intents\DefaultWelcomeIntent;
use SolidJobs\Intent\Intents\LoginIntent;
use SolidJobs\Intent\Intents\SaveByContextIntent;
use SolidJobs\Intent\Services\SolidjobsAppService;

/**
 * Class IntentChain
 * @package Solidjobs\Intent
 */
class IntentChain
{
    /**
     * @var ResponseModel
     */
    private $responseModel;

    /**
     * @var IntentPayLoadModel
     */
    private $intentPayLoad;

    /**
     * Intent collection indexed by its intent name
     * @var array
     */
    private static $intents = [
        'Default Welcome Intent' => LoginIntent::class,
        'saveByContext' => SaveByContextIntent::class
    ];

    /**
     * @todo add some data validation :)
     */
    public function evaluateRequest()
    {
        $httpBody = file_get_contents('php://input');

        /** @todo delete it, used as mockup */
        // $httpBody = file_get_contents('example_response.json');

        /**
         * Parse the response on a ResponseModel
         */
        $this->setResponseModel(ResponseModel::getFromRaw($httpBody));

        /**
         * Get intent name and look for the belonged Intent
         */
        $intentName = $this->getResponseModel()->getQueryResult()->getIntent()['displayName'];

        /**
         * Perform login only if it's not on welcome intent
         */
        if ($intentName != 'Default Welcome Intent') {
            /**
             * Perform login on SolidJobs service, so session is loaded
             */
            $this->login();
            /*$payLoad = new IntentPayLoadModel();
            $payLoad->setFulfillmentText(json_encode([SolidjobsAppService::getInstance()->getToken()]));
            $payLoad->addResponseMessage(json_encode([SolidjobsAppService::getInstance()->getToken()]));
            $this->setIntentPayLoad($payLoad);
            return;*/
        }

        if (array_key_exists($intentName, self::$intents)) {
            /** @var IntentInterface $intent */
            $intent = new self::$intents[$intentName];
        } else {
            // default intent
            $intent = new self::$intents['saveByContext'];
        }

        $this->setIntentPayLoad($intent->runIntent($this->getResponseModel()));
    }

    /**
     * Perform login on SolidjobsAppService
     */
    private function login()
    {
        /** @var string $dialogFlowSession */
        $dialogFlowSession = $this->getResponseModel()->getSession();
        SolidjobsAppService::getInstance()->loadTokenByDialogFlowSession($dialogFlowSession);
    }

    /**
     * @todo add some exporter for models :)
     */
    public function printResponse()
    {
        // $this->getIntentPayLoad()->setFulfillmentText('Esto es un test');
        // $this->getIntentPayLoad()->addResponseMessage('Esto es un test');
        echo json_encode([
            'fulfillmentText' => $this->getIntentPayLoad()->getFulfillmentText(),
            'fulfillmentMessages' => $this->getIntentPayLoad()->getFulfillmentMessages()
        ]);
    }

    /**
     * @return ResponseModel
     */
    public function getResponseModel()
    {
        return $this->responseModel;
    }

    /**
     * @param ResponseModel $responseModel
     */
    public function setResponseModel($responseModel)
    {
        $this->responseModel = $responseModel;
    }

    /**
     * @return IntentPayLoadModel
     */
    public function getIntentPayLoad()
    {
        return $this->intentPayLoad;
    }

    /**
     * @param IntentPayLoadModel $intentPayLoad
     */
    public function setIntentPayLoad($intentPayLoad)
    {
        $this->intentPayLoad = $intentPayLoad;
    }
}
