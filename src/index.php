<?php

require_once __DIR__ .'/../vendor/autoload.php';

use RestApiLogsClient\Common\Client\RestApiLogsClientFactory;
use RestApiLogsClient\Common\Configuration\GenericRestApiLogsConfiguration;
use RestApiLogsClient\Common\Log\GenericLog;
use Solidjobs\Intent\IntentChain;


/**
 * @param Throwable $exception
 * @param string $errorLabel
 * @param string|array|object $eventTrace
 * @param string|array|object $observations
 * @return GenericLog
 */
function getDefaultGenericLog(\Throwable $exception, $errorLabel = 'ERROR', $eventTrace = [], $observations = __FILE__.''.__LINE__): GenericLog {
    $genericLog = new GenericLog();

    $genericLog->setApplication('SOLIDJOBS');
    $genericLog->setLevel(GenericLog::ERROR_LEVEL);
    $genericLog->setEnvironment('pro');
    $genericLog->setScope('DIALOGFLOW_WEBHOOK');
    $genericLog->setFilename($exception->getFile());
    $genericLog->setLine($exception->getLine()); // __LINE__ or $exception
    $genericLog->setDebugBackTrace($exception->getTrace()); // or $exception get trace
    $genericLog->setSession($_SERVER['HTTP_TOKEN']);
    $genericLog->setClient([$_SERVER]); // user-agent...
    $genericLog->setException(get_class($exception));
    $genericLog->setError($errorLabel);
    $genericLog->setMessage($exception->getMessage());
    $genericLog->setVars(['REQUEST_BODY' => file_get_contents('php://input')]);
    $genericLog->setEventTrace($eventTrace);
    $genericLog->setObservations($observations);

    return $genericLog;
}


/**
 * Application run
 */
try{

    error_log(0);
    /**
     * Instantiate IntentChain
     */
    $intentChain = new IntentChain();

    /**
     * Evaluate request
     */
    $intentChain->evaluateRequest();

    /**
     * Print response
     */
    $intentChain->printResponse();

} catch (\Throwable $throwable) {
    /* Add configurations to Factory*/

    $restApiLogsConfiguration = new GenericRestApiLogsConfiguration();

    /**
     * @todo move it to configuration
     */
    $restApiLogsConfiguration->setSsl(true);
    $restApiLogsConfiguration->setDomain('logs.solidjobs.org');
    $restApiLogsConfiguration->setPath('/');

    RestApiLogsClientFactory::addConfiguration($restApiLogsConfiguration);

    RestApiLogsClientFactory::get()->log(getDefaultGenericLog($throwable, 'EVALUATE'));

    /*
    $out = json_encode([
        'error' => $throwable->getMessage(),
        'on' => $throwable->getFile() . ':' . $throwable->getLine(),
        'trace' => $throwable->getTrace()]);

    $intentPayLoad = new IntentPayLoadModel();
    $intentPayLoad->setFulfillmentText($out);
    $intentPayLoad->addResponseMessage($out);

    echo json_encode([
        'fulfillmentText' => $intentPayLoad->getFulfillmentText(),
        'fulfillmentMessages' => $intentPayLoad->getFulfillmentMessages()
    ]);*/
}
