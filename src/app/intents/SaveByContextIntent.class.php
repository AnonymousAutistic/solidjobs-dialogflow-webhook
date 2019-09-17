<?php
/**
 * Created by PhpStorm.
 * User: hans
 * Date: 16/09/19
 * Time: 13:29
 */

namespace Solidjobs\Intent\Intents;


use Solidjobs\Intent\IntentInterface;
use Solidjobs\Intent\IntentModels\IntentPayLoadModel;
use Solidjobs\Intent\IntentModels\ResponseModel;
use Solidjobs\Intent\Services\SolidjobsAppService;

class SaveByContextIntent implements IntentInterface
{

    const ACTION_RELATIONSHIP = [
        'add_cv_job_experience' =>
            ['method' => 'addCVJobExperience', 'parameters' => false],
        'add_cv_training' =>
            ['method' => 'addCVTraining', 'parameters' => false],
        'add_cv_ability' =>
            ['method' => 'addCVAbility', 'parameters' => false],
        'add_cv_language' =>
            ['method' => 'addCVLanguage', 'parameters' => false],
        'got_cv_personal_data_' =>
            ['method' => 'saveCVPersonalData', 'parameters' => true],
        'got_cv_job_experience_' =>
            ['method' => 'saveCVJobExperience', 'parameters' => true],
        'got_cv_training_' =>
            ['method' => 'saveCVTraining', 'parameters' => true],
        'got_cv_ability_' =>
            ['method' => 'saveCVAbility', 'parameters' => true],
        'got_cv_language_' =>
            ['method' => 'saveCVLanguage', 'parameters' => true],
        'delete_all_cv_job_experience' =>
            ['method' => 'deleteAllJobExperience', 'parameters' => false],
        'delete_all_cv_training' =>
            ['method' => 'deleteAllTraining', 'parameters' => false],
        'delete_all_cv_ability' =>
            ['method' => 'deleteAllAbility', 'parameters' => false],
        'delete_all_cv_language' =>
            ['method' => 'deleteAllLanguage', 'parameters' => false]
    ];

    /**
     * @param ResponseModel $intentModel
     * @return IntentPayLoadModel
     * @throws \Throwable
     */
    public function runIntent(ResponseModel $intentModel)
    {
        /**
         * Output contexts will give a condition for manage
         */
        $contexts = $intentModel->getQueryResult()->getOutputContexts();

        /**
         *
         * input: expecting_cv_job_experience
         * output: -expecting_cv_job_experience- expecting_cv_job_experience_startDate got_cv_job_experience <<<<---- yeah!
         *
         * got_cv_job_experience -> save response :D
         * got_cv_personal_data_firstName -> save response :D
         * got_cv_personal_data_lastName -> save response :D
         *
         *
         * _add_cv_job_experience
         * _current_cv_job_experience_{id} ?? // can i modify contexts with webhook? :)
         * OR => get last CV JOB EXPERIENCE :)))))) ?
         *
         *
         * ...
         *
         */

        $data = $intentModel->getQueryResult()->getParameters();

        $value = $data['value'];

        $this->dispatch($contexts, $value);

        return new IntentPayLoadModel();
    }

    /**
     * @param $contexts
     * @param $value
     * @throws \Throwable
     */
    private function dispatch($contexts, $value)
    {
        /**
         * Dynamic call method actions based on a collection indexed by key
         */
        foreach ($contexts as $context) {
            /** @var string $key Clean parameters on key like git_cv_personal_data_firstName where firstName is the parameter */
            $key = preg_replace('/(' . implode('|', array_keys(self::ACTION_RELATIONSHIP)) . ')[a-zA-Z0-9_]{0,}/', '$1', $context);
            /**
             * Check if the cleaned key matches with some key on action's key
             */
            if (array_key_exists($key, self::ACTION_RELATIONSHIP)) {
                /** @var string $method store the method in a variable */
                $method = self::ACTION_RELATIONSHIP[$key]['method'];

                /**
                 * Dynamic call to method with $context and $value parameters
                 */
                self::ACTION_RELATIONSHIP[$key]['parameters'] ? $this->$method($context, $value) : $this->$method();
            }
        }
    }

    // region add

    /**
     * OUTPUT_CONTEXT = add_cv_job_experience
     */
    private function addCVJobExperience()
    {
        /** Create a new empty job experience */
        SolidjobsAppService::getInstance()->addJobExperience([]);
    }

    /**
     * OUTPUT_CONTEXT = add_cv_training
     */
    private function addCVTraining()
    {
        /** Create new empty training */
        SolidjobsAppService::getInstance()->addTraining([]);
    }

    /**
     * OUTPUT_CONTEXT = add_cv_ability
     */
    private function addCVAbility()
    {
        /** Create new empty CVAbility */
        SolidjobsAppService::getInstance()->addAbility([]);
    }

    /**
     * OUTPUT_CONTEXT = add_cv_language
     */
    private function addCVLanguage()
    {
        /** Create new CVLanguage */
        SolidjobsAppService::getInstance()->addLanguage([]);
    }

    // endregion add

    // region edit

    /**
     * OUTPUT_CONTEXT = got_cv_personal_data_
     *
     * @param $context
     * @param $value
     */
    private function saveCVPersonalData($context, $value)
    {
        /** Remove prefix */
        $field = str_replace('got_cv_personal_data_', '', $context);

        SolidjobsAppService::getInstance()->editPersonalData([$field => $value]);
    }

    /**
     * OUTPUT_CONTEXT = got_cv_job_experience_
     *
     * @param $context
     * @param $value
     * @throws \Throwable
     */
    private function saveCVJobExperience($context, $value)
    {
        /** Get id from last CVJobExperience */
        $jobExperiences = SolidjobsAppService::getInstance()->getJobExperiences();
        $lastJobExperience = $jobExperiences[count($jobExperiences) - 1];
        $id = $lastJobExperience['id'];

        /** Remove prefix */
        $field = str_replace('got_cv_personal_data_', '', $context);

        SolidjobsAppService::getInstance()->editJobExperience($id, [$field => $value]);
    }

    /**
     * OUTPUT_CONTEXT = got_cv_training_
     *
     * @param $context
     * @param $value
     * @throws \Throwable
     */
    private function saveCVTraining($context, $value)
    {
        /** Get id from last CVTrainingExperience */
        $training = SolidjobsAppService::getInstance()->getTraining();
        $lastTraining = $training[count($training) - 1];
        $id = $lastTraining['id'];

        /** Remove prefix */
        $field = str_replace('got_cv_personal_data_', '', $context);

        SolidjobsAppService::getInstance()->editTraining($id, [$field => $value]);
    }

    /**
     * OUTPUT_CONTEXT = got_cv_ability_
     *
     * @param $context
     * @param $value
     * @throws \Exception
     */
    private function saveCVAbility($context, $value)
    {
        // @todo
        throw new \Exception();
    }

    /**
     * OUTPUT_CONTEXT = got_cv_language_
     *
     * @param $context
     * @param $value
     * @throws \Exception
     */
    private function saveCVLanguage($context, $value)
    {
        // @todo
        throw new \Exception();
    }

    // endregion edit

    // region delete

    /**
     * OUTPUT_CONTEXT = delete_all_cv_job_experience
     */
    private function deleteAllJobExperience()
    {
        // @todo
    }

    /**
     * OUTPUT_CONTEXT = delete_all_cv_training
     */
    private function deleteAllTraining()
    {
        // @todo
    }

    /**
     * OUTPUT_CONTEXT = delete_all_cv_ability
     */
    private function deleteAllAbility()
    {
        // @todo
    }

    /**
     * OUTPUT_CONTEXT = delete_all_cv_language
     */
    private function deleteAllLanguage()
    {
        // @todo
    }

    // endregion delete
}
