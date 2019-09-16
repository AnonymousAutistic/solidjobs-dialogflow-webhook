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
     * @param string $secretPhrase
     * @throws \Exception
     */
    public function login(string $secretPhrase)
    {
        $httpsService = HttpsService::getInstance();

        /**
         * POST Request
         */
        $out = $httpsService->post(
            self::SERVICE_URL . self::SERVICE_DIALOGFLOW_LOGIN,
            [
                'Content-Type: application/json'
            ],
            [
                'phrase' => $secretPhrase
            ]
        );

        /**
         * $out can be false in case of fail, in this case json_decode will return NULL
         * so it won't be validated after, avoiding error displaying.
         */
        $out = json_decode($out, true);

        /**
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

    public function getPersonalData()
    {
        // @todo
        return [];
    }

    public function getJobExperiences()
    {
        // @todo
        return [];
    }

    public function getTraining()
    {
        // @todo
        return [];
    }

    public function getAbilities()
    {
        // @todo
        return [];
    }

    public function getLanguages()
    {
        // @todo
        return [];
    }

    // endregion get

    // region edit

    public function editPersonalData()
    {
        // @todo
        return [];
    }

    // endregion edit

    // region add

    /**
     * @param array $jobExperience
     * @return array
     */
    public function addJobExperience(array $jobExperience)
    {
        // @todo
        return $jobExperience;
    }

    /**
     * @param array $training
     * @return array
     */
    public function addTraining(array $training)
    {
        // @todo
        return $training;
    }

    /**
     * @param array $ability
     * @return array ['ability' => 'value']
     */
    public function addAbility(array $ability)
    {
        // @todo
        return $ability;
    }

    /**
     * @param array $language
     * @return array ['language' => 'value']
     */
    public function addLanguage(array $language)
    {
        // @todo
        return $language;
    }

    // endregion add

    // region remove

    /**
     * @param int $id
     * @return bool
     */
    public function removeJobExperience(int $id)
    {
        // @todo
        return is_int($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeTraining(int $id)
    {
        // @todo
        return is_int($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeAbility(int $id)
    {
        // @todo
        return is_int($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeLanguage(int $id)
    {
        // @todo
        return is_int($id);
    }

    // endregion remove
}
