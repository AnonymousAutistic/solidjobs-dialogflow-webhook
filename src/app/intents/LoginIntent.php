<?php
/**
 * Created by PhpStorm.
 * User: hans
 * Date: 18/09/19
 * Time: 14:56
 */

namespace SolidJobs\Intent\Intents;


use SolidJobs\Intent\IntentInterface;
use SolidJobs\Intent\IntentModels\IntentPayLoadModel;
use SolidJobs\Intent\IntentModels\ResponseModel;
use SolidJobs\Intent\Services\SolidjobsAppService;

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

        try{
            $personalData = SolidjobsAppService::getInstance()->getPersonalData();

            $message = 'Hola ' . $personalData['firstName'] . ', soy Ote, tu asistente virtual.' .
                ' Voy a ayudarte a crear tu curriculum. ¿Has leído y aceptas la Política de privacidad y la Política de cookies? (Sí / No)';
        } catch (\Throwable $throwable) {
            $message = 'Ups, no te he entendido bien. ¿Podrías repetirlo?';
        }

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
