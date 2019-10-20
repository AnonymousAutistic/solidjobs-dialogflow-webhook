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

    /**
     * Actions relation with its methods and its parameters
     *
     * This is used for link the context key from dialogFlow to the belonged method
     */
    const ACTION_RELATIONSHIP = [
        'add_cv_job_experience_' =>
            ['method' => 'addCVJobExperience', 'parameters' => true],
        'add_cv_training_' =>
            ['method' => 'addCVTraining', 'parameters' => true],
        'add_cv_ability_' =>
            ['method' => 'addCVAbility', 'parameters' => true],
        'add_cv_language_' =>
            ['method' => 'addCVLanguage', 'parameters' => true],
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

        /**
         * @todo move parameters to properties... do it properly! ;)
         */

        /**
         * why parameters ? shouldn't be the phrase?
         */
        // $data = $intentModel->getQueryResult()->getParameters();
        $value = $intentModel->getQueryResult()->getText();

        // $value = $data['value'];

        $methods = $this->dispatch($contexts, $value);

        /**
         * Output is the same response than the model got, since this Intent is only for save/edit/delete
         */
        $intentPayLoad = new IntentPayLoadModel();

        // $intentPayLoad->setFulfillmentText(json_encode($methods));
        // $intentPayLoad->addResponseMessage(json_encode($methods));
        $intentPayLoad->setFulfillmentText($intentModel->getQueryResult()->getFullfillmentText());
        $intentPayLoad->setFulfillmentMessages($intentModel->getQueryResult()->getFullfillmentMessages());

        return $intentPayLoad;
    }

    /**
     * @param $contexts
     * @param $value
     * @throws \Throwable
     * @return array
     */
    private function dispatch($contexts, $value)
    {
        $methods = [];
        /**
         * Dynamic call method actions based on a collection indexed by key
         */
        foreach ($contexts as $context) {
            /**
             * Explode the context name
             */
            $context = explode('/', $context['name']);
            $context = $context[count($context)-1];

            /** @var string $key Clean parameters on key like git_cv_personal_data_firstName where firstName is the parameter */
            $key = preg_replace('/(' . implode('|', array_keys(self::ACTION_RELATIONSHIP)) . ')[a-zA-Z0-9_]{0,}/', '$1', $context);
            /**
             * Check if the cleaned key matches with some key on action's key
             */
            if (array_key_exists($key, self::ACTION_RELATIONSHIP)) {
                /** @var string $method store the method in a variable */
                $method = self::ACTION_RELATIONSHIP[$key]['method'];

                $methods[] = $method;

                /**
                 * Dynamic call to method with $context and $value parameters
                 */
                $methods[] = self::ACTION_RELATIONSHIP[$key]['parameters'] ? $this->$method($context, $value) : $this->$method();
            }
        }

        return $methods;
    }

    /**
     * Parse field name to capital letter
     *
     * @param $field
     * @return string
     */
    private static function parseFieldName($field)
    {
        $fieldParts = explode('_', $field);
        for($i = 1; $i < count($fieldParts); $i++) {
            /**
             * Set first letter capital letter
             */
            $firstLetter = strtoupper(substr($fieldParts[$i], 0, 1));
            $fieldParts[$i] = $firstLetter . substr($fieldParts[$i], 1);
        }

        /**
         * Join parts from field
         */
        $field = implode('', $fieldParts);

        return $field;
    }

    // region add

    /**
     * OUTPUT_CONTEXT = add_cv_job_experience_
     *
     * @param $context
     * @param $value
     */
    private function addCVJobExperience($context, $value)
    {
        /** Remove prefix */
        $field = str_replace('add_cv_job_experience_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

        /** Create a new job experience */
        SolidjobsAppService::getInstance()->addJobExperience([$field => $value]);
    }

    /**
     * OUTPUT_CONTEXT = add_cv_training_
     *
     * @param $context
     * @param $value
     */
    private function addCVTraining($context, $value)
    {
        /** Remove prefix */
        $field = str_replace('add_cv_training_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

        /** Create new training */
        SolidjobsAppService::getInstance()->addTraining([$field => $value]);
    }

    /**
     * OUTPUT_CONTEXT = add_cv_ability_
     *
     * @param $context
     * @param $value
     */
    private function addCVAbility($context, $value)
    {
        /** Remove prefix */
        $field = str_replace('add_cv_ability_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

        /** Create new empty CVAbility */
        SolidjobsAppService::getInstance()->addAbility([$field => $value]);
    }

    /**
     * OUTPUT_CONTEXT = add_cv_language_
     *
     * @param $context
     * @param $value
     */
    private function addCVLanguage($context, $value)
    {
        /** Remove prefix */
        $field = str_replace('add_cv_language_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

        /** Create new CVLanguage */
        SolidjobsAppService::getInstance()->addLanguage([$field => $value]);
    }

    // endregion add

    // region edit

    /**
     * OUTPUT_CONTEXT = got_cv_personal_data_
     *
     * @param $context
     * @param $value
     * @throws \Exception
     * @return array
     */
    private function saveCVPersonalData($context, $value)
    {
        /** Remove prefix */
        $field = str_replace('got_cv_personal_data_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

        /**
         * Send field edit to backend
         */
        return SolidjobsAppService::getInstance()->editPersonalData([$field => $value]);
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
        $field = str_replace('got_cv_job_experience_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

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
        $field = str_replace('got_cv_training_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

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
        /** Get id from last CVAbility */
        $abilities = SolidjobsAppService::getInstance()->getAbilities();
        $lastAbility = $abilities[count($abilities) - 1];
        $id = $lastAbility['id'];

        /** Remove prefix */
        $field = str_replace('got_cv_ability_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

        SolidjobsAppService::getInstance()->editAbility($id, [$field => $value]);
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
        /** Get id from last CVLanguage */
        $languages = SolidjobsAppService::getInstance()->getLanguages();
        $lastLanguage = $languages[count($languages) - 1];
        $id = $lastLanguage['id'];

        /** Remove prefix */
        $field = str_replace('got_cv_language_', '', $context);

        /**
         * Parse field for transform to capital (pascal notation)
         */
        $field = self::parseFieldName($field);

        SolidjobsAppService::getInstance()->editLanguage($id, [$field => $value]);
    }

    // endregion edit

    // region delete

    /**
     * OUTPUT_CONTEXT = delete_all_cv_job_experience
     */
    private function deleteAllJobExperience()
    {
        /** Get id from last CVLanguage */
        $jobExperiences = SolidjobsAppService::getInstance()->getJobExperiences();

        foreach ($jobExperiences as $jobExperience) {
            SolidjobsAppService::getInstance()->removeJobExperience($jobExperience['id']);
        }
    }

    /**
     * OUTPUT_CONTEXT = delete_all_cv_training
     */
    private function deleteAllTraining()
    {
        /** Get id from last CVLanguage */
        $trainings = SolidjobsAppService::getInstance()->getTraining();

        foreach ($trainings as $training) {
            SolidjobsAppService::getInstance()->removeTraining($training['id']);
        }
    }

    /**
     * OUTPUT_CONTEXT = delete_all_cv_ability
     */
    private function deleteAllAbility()
    {
        /** Get id from last CVLanguage */
        $abilities = SolidjobsAppService::getInstance()->getAbilities();

        foreach ($abilities as $ability) {
            SolidjobsAppService::getInstance()->removeAbility($ability['id']);
        }
    }

    /**
     * OUTPUT_CONTEXT = delete_all_cv_language
     */
    private function deleteAllLanguage()
    {
        /** Get id from last CVLanguage */
        $languages = SolidjobsAppService::getInstance()->getLanguages();

        foreach ($languages as $language) {
            SolidjobsAppService::getInstance()->removeLanguage($language['id']);
        }
    }

    // endregion delete
}
