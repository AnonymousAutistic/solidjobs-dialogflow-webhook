<?php


namespace Solidjobs\Intent\IntentModels;


class QueryResultModel
{
    /**
     * @var string
     */
    private $text;
    /**
     * @var string
     */
    private $action;
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var bool
     */
    private $allRequiredParamsPresent;
    /**
     * @var string
     */
    private $fullfillmentText;
    /**
     * [
     *  {
     *   "text": {
     *    "text": [
     *      "Text"
     *     ]
     *   }
     *  }
     * ]
     *
     * @var array
     */
    private $fullfillmentMessages;
    /**
     * [
     *  {
     *   "name": "projects/solidjobs-xxxx/agent/sessions/xx-xx-xx-xxx-xxxxx-/contexts/dp_city",
     *   "lifespanCount": 5
     *  }
     * ]
     *
     * @var array
     */
    private $outputContexts;
    /**
     * {
     *  "name": "projects/solidjobs-1566281418724/agent/intents/1762189c-79a9-40e1-b2a1-08f375d46d71",
     *  "displayName": "Default Welcome Intent"
     * }
     *
     * @var array
     */
    private $intent;
    /**
     * @var float
     */
    private $intentDetectionConfidence;
    /**
     * @var string
     */
    private $languageCode;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return bool
     */
    public function isAllRequiredParamsPresent()
    {
        return $this->allRequiredParamsPresent;
    }

    /**
     * @param bool $allRequiredParamsPresent
     */
    public function setAllRequiredParamsPresent($allRequiredParamsPresent)
    {
        $this->allRequiredParamsPresent = $allRequiredParamsPresent;
    }

    /**
     * @return string
     */
    public function getFullfillmentText()
    {
        return $this->fullfillmentText;
    }

    /**
     * @param string $fullfillmentText
     */
    public function setFullfillmentText($fullfillmentText)
    {
        $this->fullfillmentText = $fullfillmentText;
    }

    /**
     * @return array
     */
    public function getFullfillmentMessages()
    {
        return $this->fullfillmentMessages;
    }

    /**
     * @param array $fullfillmentMessages
     */
    public function setFullfillmentMessages($fullfillmentMessages)
    {
        $this->fullfillmentMessages = $fullfillmentMessages;
    }

    /**
     * @return array
     */
    public function getOutputContexts()
    {
        return $this->outputContexts;
    }

    /**
     * @param array $outputContexts
     */
    public function setOutputContexts($outputContexts)
    {
        $this->outputContexts = $outputContexts;
    }

    /**
     * @return array
     */
    public function getIntent()
    {
        return $this->intent;
    }

    /**
     * @param array $intent
     */
    public function setIntent($intent)
    {
        $this->intent = $intent;
    }

    /**
     * @return float
     */
    public function getIntentDetectionConfidence()
    {
        return $this->intentDetectionConfidence;
    }

    /**
     * @param float $intentDetectionConfidence
     */
    public function setIntentDetectionConfidence($intentDetectionConfidence)
    {
        $this->intentDetectionConfidence = $intentDetectionConfidence;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
    }
}
