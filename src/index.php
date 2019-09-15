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
    echo '<pre>' . json_encode([
            'error' => $throwable->getMessage(),
            'on' => $throwable->getFile() . ':' . $throwable->getLine(),
            'trace' => $throwable->getTrace()
        ], JSON_PRETTY_PRINT);
}
