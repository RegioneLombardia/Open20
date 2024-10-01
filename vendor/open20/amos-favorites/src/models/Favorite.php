<?php

namespace open20\amos\favorites\models;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\ModuleInterface;
use open20\amos\favorites\AmosFavorites;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "favorite".
 */
class Favorite extends \open20\amos\favorites\models\base\Favorite
{
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


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'url',
                'label' => $labels['url'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_classname',
                'label' => $labels['content_classname'],
                'type' => 'string'
            ],
            [
                'slug' => 'content_id',
                'label' => $labels['content_id'],
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


    public function getFavoriteType($formatText = true){
        $label =  '';
        $module = \Yii::$app->getModule($this->module);
        switch ($this->module) {
            case 'news':
                $label =  AmosFavorites::t('amosfavorites',"Notizie");
                break;
            case 'cms':
                $label =  AmosFavorites::t('amosfavorites',"Pagine");
                break;
            case 'events':
                $label =  AmosFavorites::t('amosfavorites',"Eventi");
                break;
            case 'attachments':
                if($this->content_classname == 'open20\amos\attachments\models\AttachGalleryImage' || $this->controller == 'attach-gallery-image'){
                    $label =  AmosFavorites::t('amosfavorites',"Immagini");
                }else if($this->content_classname == 'open20\amos\attachments\models\AttachDatabankFile' || $this->controller == 'attach-databank-file'){
                    $label =  AmosFavorites::t('amosfavorites',"Allegati");
                }

                break;
        }
        if(!empty($label) && $formatText){
            $label.=': ';
        }
        return $label;
    }

}
