<?php


namespace Solidjobs\Intent\IntentModels;


class IntentPayLoadModel
{
    /**
     * @var string
     */
    private $fulfillmentText;
    /**
     * 'fulfillmentMessages' => [
     *  [
     *  'text' => ['message' => file_get_contents('php://input')]
     *  ]
     * ]
     *
     * @var array
     */
    private $fulfillmentMessages = [];

    /**
     * @return string
     */
    public function getFulfillmentText()
    {
        return $this->fulfillmentText;
    }

    /**
     * @param string $fulfillmentText
     */
    public function setFulfillmentText($fulfillmentText)
    {
        $this->fulfillmentText = $fulfillmentText;
    }

    /**
     * @return array
     */
    public function getFulfillmentMessages()
    {
        return $this->fulfillmentMessages;
    }

    /**
     * @param array $fulfillmentMessages
     */
    public function setFulfillmentMessages($fulfillmentMessages)
    {
        $this->fulfillmentMessages = $fulfillmentMessages;
    }

    /**
     * @param $text string
     */
    public function addResponseMessage($text)
    {
        $this->fulfillmentMessages[] = [
            'text' => ['message' => $text]
        ];
    }
}
