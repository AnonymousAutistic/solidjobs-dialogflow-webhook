<?php

namespace Solidjobs\Intent;

use Solidjobs\Intent\Intents\DefaultWelcomeIntent;

class IntentChain
{

    /**
     * Intent collection indexed by its intent name
     * @var array
     */
    private static $intents = [
        'DefaultWelcomeIntent' => DefaultWelcomeIntent::class
    ];

    /**
     *
     */
    public function evaluateRequest()
    {
        // @todo get from request body, populate the model and send to the belonged intent to get a response
    }

    /**
     *
     */
    public function printResponse()
    {
        echo 'Working'; // @todo print the response got on self::evaluateRequest();
    }
}
