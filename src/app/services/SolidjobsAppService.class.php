<?php

namespace Solidjobs\Intent\Services;

use Solidjobs\Intent\Service;

/**
 * This class has region directives for be able the quick navigation on it on Structure feature for IDEs
 *
 * Class SolidjobsAppService
 * @package Solidjobs\Intent\Services
 */
class SolidjobsAppService extends Service
{
    /** @todo move it to configuration system */
    const SERVICE_URL = 'https://app.solidjobs.org/api/';

    const SERVICE_DIALOGFLOW_LOGIN = 'login/dialogflow';

    const SERVICE_PERSONAL_DATA = 'cv';
    const SERVICE_JOB_EXPERIENCE = 'cv/job_experience';
    const SERVICE_TRAINING = 'cv/training';
    const SERVICE_ABILITY = 'cv/ability';
    const SERVICE_LANGUAGE = 'cv/language';

    /**
     * @var string|null
     */
    private $token;

    // region login

    /**
     * @param string $dialogFlowSession
     * @throws \Exception
     */
    public function login(string $dialogFlowSession)
    {
        $httpsService = HttpsService::getInstance();

        /**
         *
         * {dialogflow-session} -> link to bind GET https://app.solidjobs.org/login/bind-dialogflow#session=$session
         *                                      (Panel) PUT https://app.solidjobs.org/api/login/dialogflow -> $session
                                                Token -> bind -> dialogflow-session (unique)
         *                                      GET https://app.solidjobs.org/api/login/dialogflow HTTP HEADER dialogflow-token: {Dialogflow-Session}
         *                                      ['token' => '']
         *
         */

        /**
         * POST Request
         */
        $out = $httpsService->get(
            self::SERVICE_URL . self::SERVICE_DIALOGFLOW_LOGIN,
            [
                'Content-Type: application/json',
                'User-Agent: DialogFlow WebHook',
                'Dialogflow-Session: ' . $dialogFlowSession
            ]
        );

        /**
         * $out can be false in case of fail, in this case json_decode will return NULL
         * so it won't be validated after, avoiding error displaying.
         */
        $out = json_decode($out, true);

        /**
         * @todo set a property for logged or anonymous (so we don't get error on an intent to login :)
         *
         * If response is wrong, throw an exception for be handled on caller
         */
        if (!is_array($out) || !array_key_exists('token', $out)) {
            throw new \Exception('LOGIN_FAILED'); // @todo create exception hierarchy
        }

        /**
         * Set token on service
         */
        $this->setToken($out['token']);
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    // endregion login

    // region get

    /**
     * Generic GET request in order to get Object from API PANEL from SolidJobs
     *
     * @param string $serviceObject
     * @return array|mixed
     * @throws \Exception
     */
    private function get(string $serviceObject)
    {
        $httpsService = HttpsService::getInstance();

        /**
         * GET Request
         */
        $out = $httpsService->get(
            self::SERVICE_URL . $serviceObject,
            [
                'Content-Type: application/json',
                'token: ' . $this->getToken(),
                'User-Agent: DialogFlow WebHook'
            ]
        );

        /**
         * $out can be false in case of fail, in this case json_decode will return NULL
         * so it won't be validated after, avoiding error displaying.
         */
        /** @var array $out */
        $out = json_decode($out, true);

        /**
         * If response is wrong, throw an exception for be handled on caller
         */
        if (!is_array($out)) {
            throw new \Exception('REQUEST_FAILED'); // @todo create exception hierarchy
        }

        return $out;
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getPersonalData()
    {
        return $this->get(self::SERVICE_PERSONAL_DATA);
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getJobExperiences()
    {
        return $this->get(self::SERVICE_JOB_EXPERIENCE);
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getTraining()
    {
        return $this->get(self::SERVICE_TRAINING);
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getAbilities()
    {
        return $this->get(self::SERVICE_ABILITY);
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getLanguages()
    {
        return $this->get(self::SERVICE_LANGUAGE);
    }

    // endregion get

    // region edit

    /**
     * Generic PUT request in order to edit Object from API PANEL from SolidJobs
     *
     * @param string $serviceObject
     * @param array $data
     * @return array|mixed
     * @throws \Exception
     */
    private function edit(string $serviceObject, array $data)
    {
        $httpsService = HttpsService::getInstance();

        /**
         * PUT Request
         */
        $out = $httpsService->put(
            self::SERVICE_URL . $serviceObject,
            [
                'Content-Type: application/json',
                'token: ' . $this->getToken(),
                'User-Agent: DialogFlow WebHook'
            ],
            $data
        );

        /**
         * $out can be false in case of fail, in this case json_decode will return NULL
         * so it won't be validated after, avoiding error displaying.
         */
        /** @var array $out */
        $out = json_decode($out, true);

        /**
         * If response is wrong, throw an exception for be handled on caller
         */
        if (!is_array($out)) {
            throw new \Exception('REQUEST_FAILED'); // @todo create exception hierarchy
        }

        return $out;
    }

    /**
     * @param array $personalData
     * @return array
     */
    public function editPersonalData(array $personalData)
    {
        return $this->edit(self::SERVICE_PERSONAL_DATA, $personalData);
    }

    /**
     * @param int $id
     * @param array $jobExperience
     * @return array
     */
    public function editJobExperience(int $id, array $jobExperience)
    {
        return $this->edit(self::SERVICE_JOB_EXPERIENCE . '/' . $id, $jobExperience);
    }

    /**
     * @param int $id
     * @param array $training
     * @return array
     */
    public function editTraining(int $id, array $training)
    {
        return $this->edit(self::SERVICE_TRAINING . '/' . $id, $training);
    }

    /**
     * @param int $id
     * @param array $ability
     * @return array ['ability' => 'value']
     */
    public function editAbility(int $id, array $ability)
    {
        return $this->edit(self::SERVICE_ABILITY . '/' . $id, $ability);
    }

    /**
     * @param int $id
     * @param array $language
     * @return array ['language' => 'value']
     */
    public function editLanguage(int $id, array $language)
    {
        return $this->edit(self::SERVICE_LANGUAGE . '/' . $id, $language);
    }

    // endregion edit

    // region add


    /**
     * Generic POST request in order to create Object from API PANEL from SolidJobs
     *
     * @param string $serviceObject
     * @param array $data
     * @return array|mixed
     * @throws \Exception
     */
    private function add(string $serviceObject, array $data)
    {
        $httpsService = HttpsService::getInstance();

        /**
         * POST Request
         */
        $out = $httpsService->post(
            self::SERVICE_URL . $serviceObject,
            [
                'Content-Type: application/json',
                'token: ' . $this->getToken(),
                'User-Agent: DialogFlow WebHook'
            ],
            $data
        );

        /**
         * $out can be false in case of fail, in this case json_decode will return NULL
         * so it won't be validated after, avoiding error displaying.
         */
        /** @var array $out */
        $out = json_decode($out, true);

        /**
         * If response is wrong, throw an exception for be handled on caller
         */
        if (!is_array($out)) {
            throw new \Exception('REQUEST_FAILED'); // @todo create exception hierarchy
        }

        return $out;
    }

    /**
     * @param array $jobExperience
     * @return array|mixed
     */
    public function addJobExperience(array $jobExperience)
    {
        return $this->add(self::SERVICE_JOB_EXPERIENCE, $jobExperience);
    }

    /**
     * @param array $training
     * @return array
     */
    public function addTraining(array $training)
    {
        return $this->add(self::SERVICE_TRAINING, $training);
    }

    /**
     * @param array $ability
     * @return array ['ability' => 'value']
     */
    public function addAbility(array $ability)
    {
        return $this->add(self::SERVICE_ABILITY, $ability);
    }

    /**
     * @param array $language
     * @return array ['language' => 'value']
     */
    public function addLanguage(array $language)
    {
        return $this->add(self::SERVICE_LANGUAGE, $language);
    }

    // endregion add

    // region remove

    /**
     * Generic DELETE request in order to remove Object from API PANEL from SolidJobs
     *
     * @param string $serviceObject
     * @return array|mixed
     * @throws \Exception
     */
    private function remove(string $serviceObject)
    {
        $httpsService = HttpsService::getInstance();

        /**
         * POST Request
         */
        $out = $httpsService->delete(
            self::SERVICE_URL . $serviceObject,
            [
                'Content-Type: application/json',
                'token: ' . $this->getToken(),
                'User-Agent: DialogFlow WebHook'
            ]
        );

        /**
         * $out can be false in case of fail, in this case json_decode will return NULL
         * so it won't be validated after, avoiding error displaying.
         */
        /** @var array $out */
        $out = json_decode($out, true);

        /**
         * If response is wrong, throw an exception for be handled on caller
         */
        if (!is_array($out)) {
            throw new \Exception('REQUEST_FAILED'); // @todo create exception hierarchy
        }

        return $out;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeJobExperience(int $id)
    {
        return $this->remove(self::SERVICE_JOB_EXPERIENCE . '/' . $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeTraining(int $id)
    {
        return $this->remove(self::SERVICE_TRAINING . '/' . $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeAbility(int $id)
    {
        return $this->remove(self::SERVICE_ABILITY . '/' . $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeLanguage(int $id)
    {
        return $this->remove(self::SERVICE_LANGUAGE . '/' . $id);
    }

    // endregion remove
}
