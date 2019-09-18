<?php
/**
 * Created by PhpStorm.
 * User: hans
 * Date: 18/09/19
 * Time: 14:56
 */

namespace Solidjobs\Intent\Intents;


use Solidjobs\Intent\IntentInterface;
use Solidjobs\Intent\IntentModels\IntentPayLoadModel;
use Solidjobs\Intent\IntentModels\ResponseModel;

class LoginIntent implements IntentInterface
{
    /**
     * @var ResponseModel
     */
    private $intentModel;

    /**
     * @param ResponseModel $intentModel
     * @return IntentPayLoadModel
     */
    public function runIntent(ResponseModel $intentModel)
    {
        /**
         * Set model as property
         */
        $this->setIntentModel($intentModel);

        /**
         * Create output model
         */
        $intentPayLoad = new IntentPayLoadModel();

        /** @var string $message Message for bind the dialogflow session with the website login */
        $message = 'Hola, para iniciar sesión necesito que entres en un link, le des a "Vincular", y luego vuelvas a esta ventana. El link es: ' . $this->getLoginUrl();

        /**
         * Set output
         */
        $intentPayLoad->setFulfillmentText($message);
        $intentPayLoad->setFulfillmentMessages(['text' => ['message' => $message]]);

        /**
         * Return the response from DialogFlow
         */
        return $intentPayLoad;
    }

    /**
     * @todo don't to hardcode urls, add to a config system :)
     *
     * @return string
     */
    private function getLoginUrl()
    {
        return 'https://app.solidjobs.org/login/bind-dialogflow#session=' . $this->getIntentModel()->getSession();
    }

    /**
     * @return ResponseModel
     */
    public function getIntentModel(): ResponseModel
    {
        return $this->intentModel;
    }

    /**
     * @param ResponseModel $intentModel
     */
    public function setIntentModel(ResponseModel $intentModel)
    {
        $this->intentModel = $intentModel;
    }
}