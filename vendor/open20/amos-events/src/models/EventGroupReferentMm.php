<?php

namespace open20\amos\events\models;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\behaviors\AttributesChangeLogBehavior;
use open20\amos\events\events\AssociateGroupMmEvent;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_group_referent_mm".
 */
class EventGroupReferentMm extends \open20\amos\events\models\base\EventGroupReferentMm
{
    const STATUS_ACTIVE = 'active';
    const STATUS_REQUEST_ACTIVATION = 'request_activation';
    const STATUS_COURSE_INCOMPLETED = 'course_incompleted';

    public $denominazione;
    public $email;
    public $nomeCognome;

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }


    public function init()
    {
        parent::init();

        $this->on(self::EVENT_AFTER_INSERT, [new AssociateGroupMmEvent(), 'associateRoleCandidatoOperator'], $this);

    }


    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['status', 'email', 'denominazione'], 'string'],
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(), [
            ]);
    }

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'event_group_referent_id',
                'label' => $labels['event_group_referent_id'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     *
     * @return type
     */
    public static function getOperatoriDg($group_id = null)
    {


        $module = \Yii::$app->getModule('events');
        if ($module) {
            $groupReferentAdministration = $module->groupReferentAdministration;
            if (!empty($groupReferentAdministration)) {
                $administrator_group_id = $groupReferentAdministration['id'];
                $role = $groupReferentAdministration['role'];
                $ids = \Yii::$app->authManager->getUserIdsByRole($role);

                if ($group_id == $administrator_group_id) {
                    $ret = \open20\amos\admin\models\UserProfile::find()
                        ->andWhere(['in', 'user_id', $ids]);
                    $alreadyPresent = self::find()->select('user_id')->all();
                    $ret->andWhere(['not in', 'user_id', $alreadyPresent]);
                    return $ret;
                }
            }
        }


        $ids = \Yii::$app->authManager->getUserIdsByRole('EVENT_DG_OPERATOR');
        $ret = \open20\amos\admin\models\UserProfile::find()
            ->andWhere(['in', 'user_id', $ids]);
        $alreadyPresent = self::find()->select('user_id')->all();
        $ret->andWhere(['not in', 'user_id', $alreadyPresent]);

//        if (!empty($group_id)) {
//            $alreadyPresent = self::find()->andWhere(['event_group_referent_id' => $group_id])->select('user_id')->all();
//            $ret->andWhere(['not in', 'user_id', $alreadyPresent]);
//        }

        return $ret;
    }

    /**
     * @return type
     * @throws \yii\base\InvalidConfigException
     */
    public function getCandidatiOperatoriAndOperators($group_id = null)
    {
        $module = \Yii::$app->getModule('events');
        if ($module) {
            if ($module->operatorCandidate && !empty($module->operatorCandidate['enabled'])) {
                $ids = \Yii::$app->authManager->getUserIdsByRole($module->operatorCandidate['role']);
                $ret = \open20\amos\admin\models\UserProfile::find()
                    ->andWhere(['in', 'user_id', $ids]);
                $alreadyPresent = self::find()->select('user_id')->all();
                $ret->andWhere(['not in', 'user_id', $alreadyPresent]);
                $ret = $ret->union(self::getOperatoriDg($group_id));
                return $ret;

            }
        }
        return self::getOperatoriDg($group_id);
    }


    /**
     * @param $model EventGroupReferentMm
     * @param $enableOperatorCandidate
     * @param $roleCadidate
     * @return string
     */
    public static function getUserRoleString($model)
    {
        $module = \Yii::$app->getModule('events');
        if ($module) {
            $groupReferentAdministration = $module->groupReferentAdministration;
            $enableOperatorCandidate = false;
            $roleCadidate = '';
            if (!empty($module->operatorCandidate['enabled']) && !empty($module->operatorCandidate['role'])) {
                $enableOperatorCandidate = true;
                $roleCadidate = $module->operatorCandidate['role'];
            }

            $role = self::getUserRole($model);
            if (!empty($groupReferentAdministration['roleAdmin']) && $groupReferentAdministration['roleAdmin'] == $role) {
                return Yii::t('amoscore', 'Super user');
            } else if (!empty($groupReferentAdministration['role']) && $groupReferentAdministration['role'] == $role) {
                return Yii::t('amoscore', 'Operator');
            }

            if ($role == 'EVENT_DG') {
                return Yii::t('amoscore', 'DG');
            } else if ($role == 'EVENT_DG_OPERATOR') {
                return Yii::t('amoscore', 'Operatore');
            } else if ($enableOperatorCandidate && ($role == $roleCadidate)) {
                return Yii::t('amoscore', 'Cadidato Operatore');
            }
        }
        return '';
    }

    /**
     * @param $model
     * @param $enableOperatorCandidate
     * @param $roleCadidate
     * @return string
     */
    public static function getUserRole($model, $exeption = false)
    {
        $module = \Yii::$app->getModule('events');
        if ($module) {
            $groupReferentAdministration = $module->groupReferentAdministration;
            $enableOperatorCandidate = false;
            $roleCadidate = '';
            if (!$exeption && !empty($module->operatorCandidate['enabled']) && !empty($module->operatorCandidate['role'])) {
                $enableOperatorCandidate = true;
                $roleCadidate = $module->operatorCandidate['role'];
            }

            if (!empty($groupReferentAdministration['roleAdmin']) && \Yii::$app->authManager->checkAccess($model->user_id, $groupReferentAdministration['roleAdmin'])) {
                return $groupReferentAdministration['roleAdmin'];
            } else if (!empty($groupReferentAdministration['role']) && \Yii::$app->authManager->checkAccess($model->user_id, $groupReferentAdministration['role'])) {
                return $groupReferentAdministration['role'];
            }

            if (\Yii::$app->authManager->checkAccess($model->user_id, 'EVENT_DG')) {
                return 'EVENT_DG';
            } else if (\Yii::$app->authManager->checkAccess($model->user_id, 'EVENT_DG_OPERATOR')) {
                return 'EVENT_DG_OPERATOR';
            } else if ($enableOperatorCandidate && \Yii::$app->authManager->checkAccess($model->user_id, $roleCadidate)) {
                return $roleCadidate;
            }
        }
        return '';
    }


}