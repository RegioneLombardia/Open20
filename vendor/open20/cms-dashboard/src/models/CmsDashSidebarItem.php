<?php

namespace open20\cms\dashboard\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\EachValidator;

/**
 * This is the model class for table "cms_dash_sidebar_item".
 */
class CmsDashSidebarItem extends \open20\cms\dashboard\models\base\CmsDashSidebarItem
{
    const TYPE_WHITELIST = 0;
    const TYPE_BLACKLIST = 1;
    public $roles_mm;

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
            ['roles_mm', EachValidator::class, 'rule' => ['string']]
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                    'roles_mm' => Yii::t('amosdashboards', 'Roles'),
                ]);
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'link',
                'label' => $labels['link'],
                'type' => 'text'
            ],
            [
                'slug' => 'link_shortcut',
                'label' => $labels['link_shortcut'],
                'type' => 'text'
            ],
            [
                'slug' => 'label',
                'label' => $labels['label'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'string'
            ],
            [
                'slug' => 'shortcut_description',
                'label' => $labels['shortcut_description'],
                'type' => 'string'
            ],
            [
                'slug' => 'icon_name',
                'label' => $labels['icon_name'],
                'type' => 'string'
            ],
            [
                'slug' => 'id_container',
                'label' => $labels['id_container'],
                'type' => 'string'
            ],
            [
                'slug' => 'class_container',
                'label' => $labels['class_container'],
                'type' => 'string'
            ],
            [
                'slug' => 'isVisible',
                'label' => $labels['isVisible'],
                'type' => 'tinyint'
            ],
            [
                'slug' => 'isTargetBlank',
                'label' => $labels['isTargetBlank'],
                'type' => 'tinyint'
            ],
            [
                'slug' => 'position',
                'label' => $labels['position'],
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

    public static function getSidebarItems() {
        $sidebarItems = self::find()
            ->where(['isVisible' => true])
            ->andWhere(['deleted_at' => null])
            ->orderBy(['position' => SORT_ASC])
            ->all();
        return $sidebarItems;
    }

    public static function getCount() {
        return self::find()->count();
    }

    public function getActive() {
        $currentUrl = \yii\helpers\Url::current();

        return (strpos($currentUrl, $this->link) !== false) ? 'active' : '';
    }

    public function afterFind()
    {
        $this->roles_mm = ArrayHelper::map(CmsDashSidebarItemRolesMm::find()->andWhere(['id_item' => $this->id])->asArray()->all(), 'role', 'role');
        return parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->updateRolesMm();
    }

    /**
     * @return void
     */
    public function updateRolesMm()
    {
        $this->deleteRolesMm();
        $this->createRolesMm();
    }

    /**
     * @return void
     */
    public function deleteRolesMm()
    {
        $roles = CmsDashSidebarItemRolesMm::find()->andWhere(['id_item' => $this->id])->all();
        foreach ($roles as $role) {
            $role->delete();
        }
    }

    /**
     * @return void
     */
    public function createRolesMm()
    {
        if (!empty($this->roles_mm)) {
            foreach ($this->roles_mm as $value) {
                $rolesMm = new CmsDashSidebarItemRolesMm();
                $rolesMm->id_item = $this->id;
                $rolesMm->role = $value;
                $rolesMm->save();
            }
        }
    }
}
