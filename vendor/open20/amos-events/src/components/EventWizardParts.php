<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\components
 * @category   CategoryName
 */

namespace open20\amos\events\components;

use open20\amos\attachments\models\File;
use open20\amos\core\components\PartQuestionarioAbstract;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use Yii;
use yii\web\UploadedFile;

/**
 * Class PartsWizardEventCreation
 * @package open20\amos\events\components
 */
class EventWizardParts extends PartQuestionarioAbstract
{
    const PART_BASEINFO = 'base-info';
    const PART_WHERE_AND_WHEN = 'where-and-when';
    const PART_IMAGE = 'image';
    const PART_LANDING = 'landing';
    const PART_COMMUNITY = 'community';
    const PART_EMAILS = 'emails';
    const PART_INVITE = 'invite';
    const PART_FINISH = 'finish';

    /**
     * @var Event $event The event model.
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
            self::PART_BASEINFO => [
                'label' => AmosEvents::t('amosevents', 'Base Info'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_BASEINFO),
                'title' => $this->getTitle(self::PART_BASEINFO),
                'url' => $this->createUrl([self::PART_BASEINFO, 'id' => $this->model->id]),
            ],
           /** self::PART_WHERE_AND_WHEN => [
                'label' => AmosEvents::t('amosevents', 'Where and When'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_WHERE_AND_WHEN),
                'title' => $this->getTitle(self::PART_WHERE_AND_WHEN),
                'url' => $this->createUrl([self::PART_WHERE_AND_WHEN, 'id' => $this->model->id]),
            ],
            self::PART_IMAGE => [
                'label' => AmosEvents::t('amosevents', 'Images'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_IMAGE),
                'title' => $this->getTitle(self::PART_IMAGE),
                'url' => $this->createUrl([self::PART_IMAGE, 'id' => $this->model->id]),
            ],
            self::PART_LANDING => [
                'label' => AmosEvents::t('amosevents', 'Landing'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_LANDING),
                'title' => $this->getTitle(self::PART_LANDING),
                'url' => $this->createUrl([self::PART_LANDING, 'id' => $this->model->id]),
            ],
            self::PART_COMMUNITY => [
                'label' => AmosEvents::t('amosevents', 'Community'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_COMMUNITY),
                'title' => $this->getTitle(self::PART_COMMUNITY),
                'url' => $this->createUrl([self::PART_COMMUNITY, 'id' => $this->model->id]),
            ],
**/
            self::PART_EMAILS => [
                'label' => AmosEvents::t('amosevents', 'Emails'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_EMAILS),
                'title' => $this->getTitle(self::PART_EMAILS),
                'url' => $this->createUrl([self::PART_EMAILS, 'id' => $this->model->id]),
            ],
            self::PART_INVITE => [
                'label' => AmosEvents::t('amosevents', 'Invite'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_INVITE),
                'title' => $this->getTitle(self::PART_INVITE),
                'url' => $this->createUrl([self::PART_INVITE, 'id' => $this->model->id]),
            ],
            self::PART_FINISH => [
                'label' => AmosEvents::t('amosevents', 'Finish'),
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
        return true;
//        switch ($part) {
//            case self::PART_INTRODUCTION:
//                $oldScenario = $this->model->getScenario();
//                $cwhBehavior = $this->model->getBehavior('cwhBehavior');
//                if (!empty($cwhBehavior)) {
//                    $this->model->detachBehavior('cwhBehavior');
//                }
//                $this->model->setScenario(Event::SCENARIO_INTRODUCTION);
//                if ($this->model->validate()) {
//                    $this->model->setScenario($oldScenario);
//                    if (!empty($cwhBehavior)) {
//                        $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                    }
//                    return true;
//                }
//                $this->model->setScenario($oldScenario);
//                if (!empty($cwhBehavior)) {
//                    $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                }
//                break;
//            case self::PART_DESCRIPTION:
//                $oldScenario = $this->model->getScenario();
//                $cwhBehavior = $this->model->getBehavior('cwhBehavior');
//                if (!empty($cwhBehavior)) {
//                    $this->model->detachBehavior('cwhBehavior');
//                }
//                $this->model->setScenario(Event::SCENARIO_DESCRIPTION);
//                $logoOk = true;
//                if (!is_null($this->model->modelType) && $this->model->modelType->logoRequested) {
//                    $logoOk = (($this->model->modelLogo instanceof UploadedFile) || ($this->model->modelLogo instanceof File));
//                }
//                if ($this->model->validate() && $logoOk) {
//                    $this->model->setScenario($oldScenario);
//                    if (!empty($cwhBehavior)) {
//                        $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                    }
//                    return true;
//                }
//                $this->model->setScenario($oldScenario);
//                if (!empty($cwhBehavior)) {
//                    $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                }
//                break;
//            case self::PART_ORGANIZATIONALDATA:
//                $oldScenario = $this->model->getScenario();
//                $cwhBehavior = $this->model->getBehavior('cwhBehavior');
//                if (!empty($cwhBehavior)) {
//                    $this->model->detachBehavior('cwhBehavior');
//                }
//                $this->model->setScenario(Event::SCENARIO_ORGANIZATIONALDATA);
//                if ($this->model->validate()) {
//                    $this->model->setScenario($oldScenario);
//                    if (!empty($cwhBehavior)) {
//                        $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                    }
//                    return true;
//                }
//                $this->model->setScenario($oldScenario);
//                if (!empty($cwhBehavior)) {
//                    $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                }
//                break;
//            case self::PART_PUBLICATION:
//                $oldScenario = $this->model->getScenario();
//                $this->model->setScenario(Event::SCENARIO_PUBLICATION);
//                if ($this->model->validate()) {
//                    $this->model->setScenario($oldScenario);
//                    return true;
//                }
//                $this->model->setScenario($oldScenario);
//                break;
//            case self::PART_SUMMARY:
//                $cwhBehavior = $this->model->getBehavior('cwhBehavior');
//                if (!empty($cwhBehavior)) {
//                    $this->model->detachBehavior('cwhBehavior');
//                }
//                $oldScenario = $this->model->getScenario();
//                $ok = (
//                    $this->isCompleted(self::PART_INTRODUCTION) &&
//                    $this->isCompleted(self::PART_DESCRIPTION) &&
//                    $this->isCompleted(self::PART_ORGANIZATIONALDATA) &&
//                    $this->isCompleted(self::PART_PUBLICATION) &&
//                    $this->model->validate()
//                );
//                $this->model->setScenario($oldScenario);
//                if (!empty($cwhBehavior)) {
//                    $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                }
//                return $ok;
//                break;
//            case self::PART_FINISH:
//                $cwhBehavior = $this->model->getBehavior('cwhBehavior');
//                if (!empty($cwhBehavior)) {
//                    $this->model->detachBehavior('cwhBehavior');
//                }
//                $oldScenario = $this->model->getScenario();
//                $ok = $this->isCompleted(self::PART_SUMMARY);
//                $this->model->setScenario($oldScenario);
//                if (!empty($cwhBehavior)) {
//                    $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
//                }
//                return $ok;
//                break;
//        }

        if (!Yii::$app->getRequest()->getIsPost()) {
            $this->model->clearErrors();
        }

        return false;
    }
}
