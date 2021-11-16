<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;

use open20\amos\core\module\BaseAmosModule;
use open20\amos\cwh\models\CwhConfig;
use yii\base\Widget;

/**
 * Class UserNetworkWidget
 * @package open20\amos\cwh\widgets
 *
 * Get User networks as table list
 * Foreach network type configured in cwh_config (except user), prints the list of networks of which the user is member
 */
class UserNetworkWidget extends Widget
{
    /**
     * @var int $userId - if null, logged user Id is considered
     */
    public $userId = null;

    /** @var bool|false $isUpdate - true if it edit mode, false otherwise */
    public $isUpdate = false;

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->userId)) {
            $this->userId = \Yii::$app->user->id;
        }

        if (is_null($this->userId)) {
            throw new \Exception(BaseAmosModule::t('amoscwh', 'Missing user id'));
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $networks = CwhConfig::find()->andWhere(['<>', 'tablename', 'user'])->all();

        $html = '';
        //foreach enabled network (cwhConfig) except user
        foreach ($networks as $network) {
            $networkClassname = $network->classname;
            $networkObject = \Yii::createObject($networkClassname);
            //find network widget printing the list of user networks (of which user is member of)
            if ($networkObject->hasMethod('getUserNetworkWidget')) {
                $html .= $networkObject->getUserNetworkWidget($this->userId, $this->isUpdate);
            }
        }
        return $html;
    }
}
