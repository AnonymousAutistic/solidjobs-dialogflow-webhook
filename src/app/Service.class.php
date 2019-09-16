<?php
/**
 * Created by PhpStorm.
 * User: hans
 * Date: 16/09/19
 * Time: 10:19
 */

namespace Solidjobs\Intent;

/**
 * Class Service
 * @package Solidjobs\Intent
 */
class Service
{
    /**
     * @var static
     */
    static $instance;

    /**
     * Service constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }
}