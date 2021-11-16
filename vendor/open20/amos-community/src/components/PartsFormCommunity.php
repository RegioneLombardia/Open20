<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\components
 * @category   CategoryName
 */

namespace open20\amos\community\components;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\core\components\PartQuestionarioAbstract;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class PartsFormCommunity
 *
 * defines the steps for creation/update of community instances
 * @package open20\amos\community\components
 */
class PartsFormCommunity extends PartQuestionarioAbstract
{
    /**
     * constants PART_<part_name>
     * each constant is a step in the progress wizard of community creation form
     */
    const PART_INTRODUCTION = 'introduction';
    const PART_DETAILS = 'details';
    const PART_ACCESS_TYPE = 'access-type';
    const PART_TAG = 'tag';
    const PART_COMPLETION = 'completion';

    /**
     * @var Community $model
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
        $map = [
            self::PART_INTRODUCTION => [
                'label' => AmosCommunity::t('amoscommunity', 'Introduction'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_INTRODUCTION),
                'title' => $this->getTitle(self::PART_INTRODUCTION),
                'url' => $this->createUrl([self::PART_INTRODUCTION, 'id' => $this->model->id]),
            ],
            self::PART_DETAILS => [
                'label' => AmosCommunity::t('amoscommunity', 'Information'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_DETAILS),
                'title' => $this->getTitle(self::PART_DETAILS),
                'url' => $this->createUrl([self::PART_DETAILS, 'id' => $this->model->id]),
            ],
            self::PART_ACCESS_TYPE => [
                'label' => AmosCommunity::t('amoscommunity', 'Access type'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_ACCESS_TYPE),
                'title' => $this->getTitle(self::PART_ACCESS_TYPE),
                'url' => $this->createUrl([self::PART_ACCESS_TYPE, 'id' => $this->model->id]),
            ],
        ];
        $moduleTag = Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array(Community::className(), $moduleTag->modelsEnabled)) {
            $map = ArrayHelper::merge($map, [
                self::PART_TAG => [
                    'label' => AmosCommunity::t('amoscommunity', 'Tag'),
                    'index' => $i++,
                    'description' => '',
                    'status' => $this->getStatus(self::PART_TAG),
                    'title' => $this->getTitle(self::PART_TAG),
                    'url' => $this->createUrl([self::PART_TAG, 'id' => $this->model->id]),
                ]
            ]);
        }
        self::$map = ArrayHelper::merge($map, [
            self::PART_COMPLETION => [
                'label' => AmosCommunity::t('amoscommunity', 'Completion'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_COMPLETION),
                'title' => $this->getTitle(self::PART_COMPLETION),
                'url' => $this->createUrl([self::PART_COMPLETION, 'id' => $this->model->id]),
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function isCompleted($part)
    {
        switch ($part) {
            case self::PART_INTRODUCTION:
                return true;
                break;
            case self::PART_DETAILS:
                if (isset($this->model->id)) {
                    return true;
                }
                break;
            case self::PART_ACCESS_TYPE:
                if (isset($this->model->id)) {
                    return true;
                }
                break;
            case self::PART_TAG:
                if (isset($this->model->community_type_id)) {
                    return true;
                } else {
                    $this->model->addError('community_type_id',
                        $this->model->getAttributeLabel('community_type_id') . " " . AmosCommunity::t('amoscommunity',
                            "cannot be blank"));
                    return false;
                }
                break;
            case self::PART_COMPLETION:
                if (isset($this->model->community_type_id)) {
                    return true;
                }
                break;
        }
        if (!\Yii::$app->getRequest()->getIsPost()) {
            $this->model->clearErrors();
        }
        return false;
    }
}
