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
use Solidjobs\Intent\Services\SolidjobsAppService;

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

        /**
         * Message it's the same than the message got by user
         */
        $message = $this->getIntentModel()->getQueryResult()->getText();

        /**
         * Got session from dialog flow
         */
        $dialogFlowSession = $this->getIntentModel()->getSession();

        // good faith test
        $token = str_replace(['token: ', 'Token: '], '', $message);

        /**
         * Send POST to /login/dialogflow with dialogflow session and token for binding
         */
        SolidjobsAppService::getInstance()->setToken($token);
        SolidjobsAppService::getInstance()->bindTokenWithDialogFlowSession($token, $dialogFlowSession);

        /** @var string $message Message for bind the dialogflow session with the website login */
        $message = 'Hola, te doy la bienvenida a SolidJobs. Por el momento únicamente puedo hacer CVs, pero el equipo está trabajando en más cosas. ¿Quieres que creemos tu CV?';

        /**
         * Set output
         */
        $intentPayLoad->setFulfillmentText($message);
        $intentPayLoad->addResponseMessage($message);
        // $intentPayLoad->setFulfillmentMessages([['text' => ['message' => $message]]]);

        /**
         * Return the response from DialogFlow
         */
        return $intentPayLoad;
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
