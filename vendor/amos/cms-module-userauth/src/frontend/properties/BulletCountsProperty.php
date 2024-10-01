<?php

namespace amos\userauth\frontend\properties;

use amos\userauth\frontend\Module;
use amos\userauth\frontend\utility\cmspageblock\CmsDataPageBlock;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\models\ModelsClassname;
use open20\amos\dashboard\models\AmosWidgets;
use luya\admin\base\CheckboxProperty;
use luya\admin\models\Config;
use luya\cms\frontend\events\BeforeRenderEvent;
use luya\cms\helpers\Url;
use luya\cms\menu\QueryOperatorFieldInterface;
use luya\cms\models\NavItem;
use mdm\admin\models\AuthItem;
use Yii;
use yii\helpers\ArrayHelper;

class BulletCountsProperty extends CheckboxProperty
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

//        $this->on(self::EVENT_BEFORE_RENDER, [$this, 'ensureUserAccess']);
    }

    /**
     * @inheritdoc
     */
    public function varName()
    {
        return 'bulletCounts';
    }

    public function type()
    {
        return self::TYPE_SELECT;
    }

    /**
     * @inheritdoc
     */
    public function label()
    {
        return Module::t('#bullet_counts_propety_label');
    }

    /**
     * @return mixed
     */
    public function getValuesTable()
    {
        $table      = null;
        $bulletType = null;

        $value   = $this->value;
        $explode = explode('-', $value);
        if (count($explode) == 2) {
            $table      = $explode[0];
            $bulletType = $explode[1];
        }
        return [
            'table' => $table,
            'bulletType' => $bulletType,
        ];
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        $values = $this->getValuesTable();
        $count  = 0;
//        pr($values);
        if (!empty($values['table']) && !empty($values['bulletType'])) {
            $count = \open20\amos\core\record\Record::getStaticBullet($values['bulletType'], false,
                    $values['table'], true);
        }
//        pr($count, 'count ' . $values['table']);
        return $count;
    }

    /**
     * @return array|mixed
     */
    public function options()
    {
        $options = [];

        $whiteListTables = \open20\amos\core\record\Record::getWhiteListBulletCount();
//        $widgetIcons = \open20\amos\utility\models\BulletCounters::getAllBulletCountWidgets();
        foreach ($whiteListTables as $table) {
            $options [] = ['value' => $table.'-'.\open20\amos\core\record\Record::BULLET_TYPE_OWN, 'label' => ucfirst($table)." - own interest"];
            $options [] = ['value' => $table.'-'.\open20\amos\core\record\Record::BULLET_TYPE_ALL, 'label' => ucfirst($table)." - all"];
        }

        return $options;
    }
}