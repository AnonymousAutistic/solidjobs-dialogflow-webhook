<?php

require_once __DIR__ .'/../vendor/autoload.php';

use Solidjobs\Intent\IntentChain;
use Solidjobs\Intent\IntentModels\IntentPayLoadModel;

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
