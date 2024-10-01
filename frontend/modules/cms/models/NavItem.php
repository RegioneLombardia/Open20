<?php

namespace app\modules\cms\models;

use app\modules\cms\admin\Module;
use open20\cms\dashboard\Module as DashboardModule;
use open20\amos\attachments\behaviors\FileBehavior;
use yii\helpers\ArrayHelper;
use Yii;

class NavItem extends \luya\cms\models\NavItem {

    /**
     * Before create event.
     */
    public function beforeCreate() {
        $this->timestamp_create = time();
        $this->timestamp_update = 0;
        $this->create_user_id = Module::getAuthorUserId();
        $this->update_user_id = Module::getAuthorUserId();
        $this->slugifyAlias();
    }

    /**
     * Before update event.
     */
    public function eventBeforeUpdate() {
        $this->timestamp_update = time();
        $this->update_user_id = Module::getAuthorUserId();
        $this->slugifyAlias();
    }

    public function rules() {
        /** @var DashboardModule $dashboardModule */
        $dashboardModule = DashboardModule::instance();
        $rules =  [
            ['nav_item_type_id', 'required', 'isEmpty' => function($value) {
                return empty($value);
                }, 'when' => function() {
                    return !$this->isNewRecord;
                }
            ],
            [['description', 'keywords'], 'string'],
            [['title'], 'string', 'max' => (isset($dashboardModule->defaultTitlePageLength)) ? $dashboardModule->defaultTitlePageLength : 180],
            [['alias'], 'string', 'max' => (isset($dashboardModule->defaultTitlePageLength)) ? $dashboardModule->defaultTitlePageLength : 80],
            [['title_tag'], 'string', 'max' => (isset($dashboardModule->defaultTitlePageLength)) ? $dashboardModule->defaultTitlePageLength : 255],
            [['lang_id', 'title', 'alias', 'nav_item_type'], 'required'],
            [['nav_id', 'lang_id', 'nav_item_type', 'nav_item_type_id', 'create_user_id', 'update_user_id', 'timestamp_create', 'timestamp_update', 'image_id', 'is_url_strict_parsing_disabled', 'is_cacheable'], 'integer'],
            [['alias'], 'match', 'pattern' => '/\_|\/|\\\/i', 'not' => true],
            [['seo_image'], 'file', 'extensions' => 'jpg,jpeg,png,gif,webp'],
        ];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
                    'fileBehavior' => [
                        'class' => FileBehavior::className()
                    ],
        ]);
    }

    public function attributeLabels() {
        return ArrayHelper::merge(
                        parent::attributeLabels(),
                        ['seo_image' => \Yii::t('amosapp', 'Immagine')]);
    }

}
