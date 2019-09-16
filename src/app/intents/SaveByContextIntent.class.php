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
     * @param ResponseModel $intentModel
     * @return IntentPayLoadModel
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
     * @param $context
     * @param $value
     */
    private function dispatch($context, $value)
    {
        /**
         * @todo improve it with an index and dynamic methods
         */
        if (strpos($context[1], 'got_cv_personal_data_') === 0) {
            $this->saveCVPersonalData($context, $value);
        } else if(strpos($context[1], 'add_cv_job_experience') === 0) {
            $this->addCVJobExperience();
        } else if(strpos($context[1], 'got_cv_job_experience_') === 0) {
            $this->saveCVJobExperience($context, $value);
        }
    }

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
     * OUTPUT_CONTEXT = add_cv_job_experience
     */
    private function addCVJobExperience()
    {
        /** Create a new empty job experience */
        SolidjobsAppService::getInstance()->addJobExperience([]);
    }

    /**
     * OUTPUT_CONTEXT = got_cv_job_experience_
     *
     * @param $context
     * @param $value
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
}