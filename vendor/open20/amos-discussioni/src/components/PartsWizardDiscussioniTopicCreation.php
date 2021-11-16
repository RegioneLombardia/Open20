<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\components
 * @category   CategoryName
 */

namespace open20\amos\discussioni\components;

use open20\amos\attachments\models\File;
use open20\amos\core\components\PartQuestionarioAbstract;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use Yii;
use yii\web\UploadedFile;

/**
 * Class PartsWizardDiscussioniTopicCreation
 * @package open20\amos\discussioni\components
 */
class PartsWizardDiscussioniTopicCreation extends PartQuestionarioAbstract
{
    const PART_INTRODUCTION = 'introduction';
    const PART_DETAILS = 'details';
    const PART_PUBLICATION = 'publication';
    const PART_SUMMARY = 'summary';
    const PART_FINISH = 'finish';
    
    /**
     * @var DiscussioniTopic $model The model.
     */
    public $model;
    
    /**
     * @inheritdoc
     */
    public function getStatus($part)
    {
        if ($part == $this->current || $part == $this->currentChild) {
            if (Yii::$app->getRequest()->getIsPost() && !$this->isCompleted($part)) {
                self::$HAS_ERROR = true;
                return self::STATUS_ERROR;
            }
            return self::STATUS_CURRENT;
        } elseif ($this->partIsPostCurrent($part)) {
            return self::STATUS_UNDONE;
        } elseif ($this->isCompleted($part)) {
            return self::STATUS_COMPLETED;
        }
        return self::STATUS_UNDONE;
    }
    
    /**
     * @inheritdoc
     */
    public function initMap()
    {
        $i = 1;
        self::$map = [
            self::PART_INTRODUCTION => [
                'label' => AmosDiscussioni::t('amosdiscussioni', 'Introduction'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_INTRODUCTION),
                'title' => $this->getTitle(self::PART_INTRODUCTION),
                'url' => $this->createUrl([self::PART_INTRODUCTION, 'id' => $this->model->id]),
            ],
            self::PART_DETAILS => [
                'label' => AmosDiscussioni::t('amosdiscussioni', 'Details'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_DETAILS),
                'title' => $this->getTitle(self::PART_DETAILS),
                'url' => $this->createUrl([self::PART_DETAILS, 'id' => $this->model->id]),
            ],
            self::PART_PUBLICATION => [
                'label' => AmosDiscussioni::t('amosdiscussioni', 'Publication'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_PUBLICATION),
                'title' => $this->getTitle(self::PART_PUBLICATION),
                'url' => $this->createUrl([self::PART_PUBLICATION, 'id' => $this->model->id]),
            ],
            self::PART_SUMMARY => [
                'label' => AmosDiscussioni::t('amosdiscussioni', 'Summary'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_SUMMARY),
                'title' => $this->getTitle(self::PART_SUMMARY),
                'url' => $this->createUrl([self::PART_SUMMARY, 'id' => $this->model->id]),
            ],
            self::PART_FINISH => [
                'label' => AmosDiscussioni::t('amosdiscussioni', 'Finish'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_FINISH),
                'title' => $this->getTitle(self::PART_FINISH),
                'url' => $this->createUrl([self::PART_FINISH, 'id' => $this->model->id]),
            ]
        ];
    }
    
    /**
     * @param string $part
     * @return bool
     */
    public function isCompleted($part)
    {
        switch ($part) {
            case self::PART_INTRODUCTION:
                return true;
                break;
            case self::PART_DETAILS:
                $this->model->setScenario(DiscussioniTopic::SCENARIO_DETAILS);
                $ok = true;
                if (!is_null($this->model->discussionsTopicImage)) {
                    $ok = (($this->model->discussionsTopicImage instanceof UploadedFile) || ($this->model->discussionsTopicImage instanceof File));
                }
                $cwhBehavior = $this->model->getBehavior('cwhBehavior');
                if (!empty($cwhBehavior)) {
                    $this->model->detachBehavior('cwhBehavior');
                }
                $fields = array_diff($this->model->scenarios()[DiscussioniTopic::SCENARIO_DETAILS], ['discussionsTopicImage']);
                // Do not redo validation of field discussionsTopicImage, causes error
                $ok = $ok && $this->model->id && $this->model->validate($fields, false);
                if (!empty($cwhBehavior)) {
                    $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
                }
                if ($ok) {
                    return true;
                }
                break;
            case self::PART_PUBLICATION:
                $this->model->setScenario(DiscussioniTopic::SCENARIO_PUBLICATION);
                if ($this->model->validate() && $this->model->id) {
                    return true;
                }
                break;
            case self::PART_SUMMARY:
                $previousCompleted = (
                    $this->isCompleted(self::PART_INTRODUCTION) &&
                    $this->isCompleted(self::PART_DETAILS) &&
                    $this->isCompleted(self::PART_PUBLICATION)
                );
                $this->model->setScenario(DiscussioniTopic::SCENARIO_SUMMARY);
                $cwhBehavior = $this->model->getBehavior('cwhBehavior');
                if (!empty($cwhBehavior)) {
                    $this->model->detachBehavior('cwhBehavior');
                }
                $ok = ($previousCompleted && $this->model->id && $this->model->validate());
                if (!empty($cwhBehavior)) {
                    $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
                }
                if ($ok) {
                    return true;
                }
                break;
            case self::PART_FINISH:
                return $this->isCompleted(self::PART_SUMMARY);
                break;
        }
        
        if (!Yii::$app->getRequest()->getIsPost()) {
            $this->model->clearErrors();
        }
        return false;
    }
}
