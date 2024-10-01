<?php
namespace open20\amos\cwh\models;

use open20\design\components\bootstrapitalia\interfaces\CheckBoxListTopicsIconInterface;
use Yii;
use yii\base\Model;

/**
 * Model without table
 */
class Topic extends Model implements CheckBoxListTopicsIconInterface
{
    public $id;
    public $label;
    public $description;
    public $icon;

    public function rules()
    {
        return [
            [['label', 'description', 'icon'], 'string'],
            [['id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferenceuser', 'ID'),
            'label' => Yii::t('preferenceuser', 'Label'),
            'description' => Yii::t('preferenceuser', 'Description'),
            'icon' => Yii::t('preferenceuser', 'Icon'),
        ];
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

}