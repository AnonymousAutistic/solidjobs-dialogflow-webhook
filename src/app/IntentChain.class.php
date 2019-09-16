<?php

namespace Solidjobs\Intent;

use Solidjobs\Intent\IntentModels\IntentPayLoadModel;
use Solidjobs\Intent\IntentModels\ResponseModel;
use Solidjobs\Intent\Intents\DefaultWelcomeIntent;
use Solidjobs\Intent\Intents\SaveByContextIntent;

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
        'Default Welcome Intent' => DefaultWelcomeIntent::class,
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

        $this->setResponseModel(ResponseModel::getFromRaw($httpBody));

        $intentName = $this->getResponseModel()->getQueryResult()->getIntent()['displayName'];

        if(array_key_exists($intentName, self::$intents)) {
            /** @var IntentInterface $intent */
            $intent = new self::$intents[$intentName];
        } else {
            // default intent
            $intent = new self::$intents['saveByContext'];
        }

        $this->setIntentPayLoad($intent->runIntent($this->getResponseModel()));
    }

    /**
     * @todo add some exporter for models :)
     */
    public function printResponse()
    {
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
