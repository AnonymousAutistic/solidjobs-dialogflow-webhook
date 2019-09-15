<?php

namespace Solidjobs\Intent\IntentModels;

class ResponseModel
{
    /**
     * @var string
     */
    private $raw;
    /**
     * @var array
     */
    private $properties;
    /**
     * @var string
     */
    private $id;
    /**
     * @var QueryResultModel
     */
    private $queryResult;
    /**
     * @var array
     */
    private $originalDetectIntentRequest;
    /**
     * @var string
     */
    private $session;

    /**
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @param string $raw
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return QueryResultModel
     */
    public function getQueryResult()
    {
        return $this->queryResult;
    }

    /**
     * @param QueryResultModel $queryResult
     */
    public function setQueryResult($queryResult)
    {
        $this->queryResult = $queryResult;
    }

    /**
     * @return array
     */
    public function getOriginalDetectIntentRequest()
    {
        return $this->originalDetectIntentRequest;
    }

    /**
     * @param array $originalDetectIntentRequest
     */
    public function setOriginalDetectIntentRequest($originalDetectIntentRequest)
    {
        $this->originalDetectIntentRequest = $originalDetectIntentRequest;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
}
