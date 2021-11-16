<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\components
 * @category   CategoryName
 */

namespace open20\amos\admin\components;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\models\File;
use open20\amos\core\components\PartQuestionarioAbstract;
use Yii;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * Class FirstAccessWizardParts
 * @package open20\amos\admin\components
 */
class FirstAccessWizardParts extends PartQuestionarioAbstract
{
    const PART_INTRODUCTION = 'introduction';
    const PART_INTRODUCING_MYSELF = 'introducing-myself';
    const PART_ROLE_AND_AREA = 'role-and-area';
    const PART_INTERESTS = 'interests';
    const PART_PARTNERSHIP = 'partnership';
    const PART_SUMMARY = 'summary';
    const PART_FINISH = 'finish';
    
    /**
     * @var UserProfile $model
     */
    public $model;
    
    /**
     * @inheritdoc
     */
    public function initMap()
    {
        $i = 1;
        self::$map = [
            self::PART_INTRODUCTION => [
                'label' => AmosAdmin::t('amosadmin', 'Introduction'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_INTRODUCTION),
                'title' => $this->getTitle(self::PART_INTRODUCTION),
                'url' => $this->createUrl([self::PART_INTRODUCTION])
            ],
            self::PART_INTRODUCING_MYSELF => [
                'label' => AmosAdmin::t('amosadmin', 'Introducing myself'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_INTRODUCING_MYSELF),
                'title' => $this->getTitle(self::PART_INTRODUCING_MYSELF),
                'url' => $this->createUrl([self::PART_INTRODUCING_MYSELF])
            ],
            self::PART_ROLE_AND_AREA => [
                'label' => AmosAdmin::t('amosadmin', 'Role and Area'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_ROLE_AND_AREA),
                'title' => $this->getTitle(self::PART_ROLE_AND_AREA),
                'url' => $this->createUrl([self::PART_ROLE_AND_AREA])
            ],
            self::PART_INTERESTS => [
                'label' => AmosAdmin::t('amosadmin', 'Interests'),
                'index' => (!array_key_exists('tag', \Yii::$app->modules)) ? 0 : $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_INTERESTS),
                'title' => $this->getTitle(self::PART_INTERESTS),
                'url' => $this->createUrl([self::PART_INTERESTS])
            ],
            self::PART_PARTNERSHIP => [
                'label' => AmosAdmin::t('amosadmin', 'Partnership'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_PARTNERSHIP),
                'title' => $this->getTitle(self::PART_PARTNERSHIP),
                'url' => $this->createUrl([self::PART_PARTNERSHIP])
            ],
//            self::PART_SUMMARY => [
//                'label' => AmosAdmin::t('amosadmin', 'Summary'),
//                'index' => $i++,
//                'description' => '',
//                'status' => $this->getStatus(self::PART_SUMMARY),
//                'title' => $this->getTitle(self::PART_SUMMARY),
//                'url' => $this->createUrl([self::PART_SUMMARY])
//            ],
            self::PART_FINISH => [
                'label' => AmosAdmin::t('amosadmin', 'Finish'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_FINISH),
                'title' => $this->getTitle(self::PART_FINISH),
                'url' => $this->createUrl([self::PART_FINISH])
            ]
        ];

        if (!array_key_exists('tag', \Yii::$app->modules)) {
            unset(self::$map[self::PART_INTERESTS]);
        }
    }

    /**
     * @param string $part
     * @return bool
     */
    public function isCompleted($part)
    {
        switch ($part) {
            case self::PART_INTRODUCTION:
                return true && $this->getAccessFirstTime(self::PART_INTRODUCTION);;
                break;
            case self::PART_INTRODUCING_MYSELF:
                $this->model->setScenario(UserProfile::SCENARIO_INTRODUCING_MYSELF);
                $logoOk = true;
                if (!is_null($this->model->userProfileImage)) {
                    $logoOk = (($this->model->userProfileImage instanceof UploadedFile) || ($this->model->userProfileImage instanceof File));
                }
                if ($this->model->validate() && $logoOk) {
                    return true && $this->getAccessFirstTime(self::PART_INTRODUCING_MYSELF);
                }
                break;
            case self::PART_ROLE_AND_AREA:
                $this->model->setScenario(UserProfile::SCENARIO_ROLE_AND_AREA);
                if ($this->model->validate()) {
                    return true && $this->getAccessFirstTime(self::PART_ROLE_AND_AREA);
                }
                break;
            case self::PART_INTERESTS:
                //$this->model->setScenario(UserProfile::SCENARIO_INTERESTS);
                if ($this->model->validate()) {
                    return true && $this->getAccessFirstTime(self::PART_INTERESTS);
                }
                break;
            case self::PART_PARTNERSHIP:
                $validate = (
                    $this->isCompleted(self::PART_INTRODUCTION) &&
                    $this->isCompleted(self::PART_INTRODUCING_MYSELF) &&
                    $this->isCompleted(self::PART_ROLE_AND_AREA) &&
                    $this->isCompleted(self::PART_INTERESTS) &&
                    $this->getAccessFirstTime(self::PART_PARTNERSHIP));
                if($validate){
                    $this->model->setScenario(UserProfile::SCENARIO_PARTNERSHIP);
                    if ($this->model->validate()) {
                        return true;
                    }
                }
                break;
//            case self::PART_SUMMARY:
//                return (
//                    $this->isCompleted(self::PART_INTRODUCING_MYSELF) &&
//                    $this->isCompleted(self::PART_ROLE_AND_AREA) &&
//                    $this->isCompleted(self::PART_INTERESTS) &&
//                    $this->isCompleted(self::PART_PARTNERSHIP)
//                );
//                break;
            case self::PART_FINISH:
                return (
                    $this->isCompleted(self::PART_INTRODUCTION) &&
                    $this->isCompleted(self::PART_INTRODUCING_MYSELF) &&
                    $this->isCompleted(self::PART_ROLE_AND_AREA) &&
                    $this->isCompleted(self::PART_INTERESTS) &&
                    $this->getAccessFirstTime(self::PART_FINISH) /*&&
                    $this->isCompleted(self::PART_PARTNERSHIP)*/
                );
//                return $this->isCompleted(self::PART_SUMMARY);
                break;
        }
        
        if (!Yii::$app->getRequest()->getIsPost()) {
            $this->model->clearErrors();
        }
        
        return false;
    }

    /**
     * Checks if a step has been accessed for the first time at least
     * @param string $step
     * @return bool
     */
    public function getAccessFirstTime($step){

        if($this->model->first_access_wizard_steps_accessed != null && $this->model->first_access_wizard_steps_accessed!="") {
            $stepsAccessed = Json::decode($this->model->first_access_wizard_steps_accessed);
            return $stepsAccessed[$step];
        } else {
            return true;
        }

    }

}
