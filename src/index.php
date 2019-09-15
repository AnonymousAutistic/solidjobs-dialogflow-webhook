<?php

require_once __DIR__ .'/../vendor/autoload.php';


use Solidjobs\Intent\IntentChain;

/**
 * Application run
 */
try{

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
    // OOPS! @todo exception hierarchy
}
