<?php

namespace open20\amos\partnershipprofiles\models;

use open20\amos\attachments\behaviors\FileBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "partnership_profiles_category".
 */
class PartnershipProfilesCategory extends \open20\amos\partnershipprofiles\models\base\PartnershipProfilesCategory
{
    public $profilesCategoryRoles;

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
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::class
            ]
        ]);
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
            ['categoryIcon','file'],
            ['profilesCategoryRoles', 'safe']
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'subtitle',
                'label' => $labels['subtitle'],
                'type' => 'string'
            ],
            [
                'slug' => 'short_description',
                'label' => $labels['short_description'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'color_text',
                'label' => $labels['color_text'],
                'type' => 'string'
            ],
            [
                'slug' => 'color_background',
                'label' => $labels['color_background'],
                'type' => 'string'
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
     */
    public function saveCategorieRolesMm(){
        PartnershipProfilesCategoryRoles::deleteAll(['partnership_profiles_category_id' => $this->id]);
        foreach ((Array) $this->profilesCategoryRoles as $role){
            $newsRoleMm = new PartnershipProfilesCategoryRoles();
            $newsRoleMm->partnership_profiles_category_id = $this->id;
            $newsRoleMm->role = $role;
            $newsRoleMm->save();
        }
    }
    /**
     *  load newsCategoryCommunities for Select2
     */
    public function loadCategoryRoles(){
        $roles = [];
        foreach ((Array) $this->partnershipProfilesCategoryRoles as $categoryRolesMms){
            $roles [$categoryRolesMms->role]= $categoryRolesMms->role;
        };
        $this->profilesCategoryRoles = $roles;
    }

    /**
     * @return null|string
     */
    public function colorText(){
        if(empty($this->color_background)){
            return null;
        }
        $color=$this->color_background;
        $white="#FFFFFF";
        $black="#000000";
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        list($rw, $gw, $bw) = sscanf($white, "#%02x%02x%02x");
        list($rb, $gb, $bb) = sscanf($black, "#%02x%02x%02x");

        $difWhite=  max($r,$rw) - min($r,$rw) +
            max($g,$gw) - min($g,$gw) +
            max($b,$bw) - min($b,$bw);
        $difBlack=  max($r,$rb) - min($r,$rb) +
            max($g,$gb) - min($g,$gb) +
            max($b,$bb) - min($b,$bb);
        return $difWhite>$difBlack?$white:$black;
    }

}
