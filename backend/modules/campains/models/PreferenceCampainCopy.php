<?php

namespace backend\modules\campains\models;


use yii\base\Model;
use backend\modules\campains\Module;
use Yii;

/**
 * This is the base-model class for table "preference_campain".
 *
 * @property integer $titolo
 * @property string $type_copy
 * 
 * 
 */

class PreferenceCampainCopy extends Model {

    public $titolo;
    public $type_copy;
    public $preference_campain_id;

    const SCENARIO_COPY_CHANNEL = 'copy_channel';
    const SCENARIO_COPY_CAMPAIN = 'copy_campain';


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type_copy'], 'safe'],
            [['titolo'], 'string', 'max' => 255, 'on' => self::SCENARIO_COPY_CAMPAIN],
            [['titolo', 'type_copy', 'preference_campain_id'], 'required', 'on' => self::SCENARIO_COPY_CAMPAIN],
            [['preference_campain_id'], 'integer'],

            
            [['preference_campain_id', 'type_copy'], 'required', 'on' => self::SCENARIO_COPY_CHANNEL],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'titolo' => Module::t('campains', 'Nome nuova campagna'),
            'type_copy' => Module::t('campains', 'Tipo copia'),
            'preference_campain_id' => Module::t('campains', 'Preference Campain id'),
            'preference_campain_section_id' => Module::t('campains', 'Preference Campain App section id')
        ];
    }




    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription($truncate)
    {
        return [];
    }
   
}
