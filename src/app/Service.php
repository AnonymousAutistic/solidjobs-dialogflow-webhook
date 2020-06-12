<?php
/**
 * Created by PhpStorm.
 * User: hans
 * Date: 16/09/19
 * Time: 10:19
 */

namespace SolidJobs\Intent;

/**
 * Class Service
 * @package Solidjobs\Intent
 */
class Service
{
    /**
     * @var static
     */
    protected static $instance;

    /**
     * Service constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return static
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
