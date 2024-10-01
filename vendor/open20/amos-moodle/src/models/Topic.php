<?php

namespace open20\amos\moodle\models;

use open20\amos\moodle\AmosMoodle;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

/**
 * This is the model class for Moodle Topic
 */
class Topic extends Model
{
    /**
     * 
     */
    const TOPIC_STATUS_COMPLETED = 1;
    const TOPIC_STATUS_UNCOMPLETED = 0;

    /**
     *
     * @var type 
     */
    public $id;
    public $courseId;
    public $nome;
    public $num_attivita_tot;
    public $num_attivita_completate;
    public $avanzamento_attivita;
    public $stato;
    public $created_by = null;
    
    /**
     *
     * @var type 
     */
    public $description;

    /**
     * 
     * @return type
     */
    public function rules()
    {
        return [
            /*  [['$num_attivita_tot', 'completato', 'courseId'], 'integer'],
              [['nome'], 'string', 'max' => 255], */
        ];
    }

    /**
     * 
     */
    public function getWorkflowStatus() {}
    
    /**
     * 
     * @return type
     */
    public function attributeLabels()
    {
        return [
            //'id' => AmosMoodle::_t('ID'),
            'nome' => AmosMoodle::_t('Argomento'),
            'avanzamento_attivita' => AmosMoodle::_t('Numero attività completate / numero attività totali'),
            'stato' => AmosMoodle::_t('Stato'),
            //'courseId' => AmosMoodle::_t('Id Corso'),
        ];
    }

    /**
     * inserire il campo o i campi rappresentativi del modulo
     * @return type
     */
    public function representingColumn()
    {
        return [];
    }

    /**
     * 
     * @return type
     */
    public function attributeHints()
    {
        return [];
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

    /**
     * 
     * @param type $contentsList
     * @return array
     */
    public function getTopicList($contentsList)
    {
        $topicList = array();
        foreach ($contentsList as $contents) {
            $newTopic = new Topic();
            $newTopic->id = $contents["id"];
            $newTopic->courseId = $this->courseId;
            $newTopic->nome = $contents["name"];
            $newTopic->num_attivita_tot = sizeof($contents["modules"]);
            $newTopic->num_attivita_completate = $contents["moodleActivitiesCompleted"];
            $newTopic->avanzamento_attivita = $newTopic->num_attivita_completate
                . ' / '
                . $newTopic->num_attivita_tot;

            if ($newTopic->num_attivita_completate == $newTopic->num_attivita_tot) {
                $newTopic->stato = self::TOPIC_STATUS_COMPLETED;
            } else {
                $newTopic->stato = self::TOPIC_STATUS_UNCOMPLETED;
            }
            
            array_push($topicList, $newTopic);
        }

        return $topicList;
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return '';
    }

}
