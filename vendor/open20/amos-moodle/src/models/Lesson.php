<?php

namespace open20\amos\moodle\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use open20\amos\moodle\AmosMoodle;

/**
 * This is the model class for table Moodle Lesson.
 */
class Lesson extends Model
{
    /**
     * 
     * @var type
     */
    public $id;
    /**
     * 
     * @var type
     */
    public $name;
    /**
     * 
     * @var type
     */
    public $uservisible;
    /**
     * 
     * @var type
     */
    public $modname;
    /**
     * 
     * @var type
     */
    public $instance;
    /**
     * 
     * @var type
     */
    public $url;
    /**
     * 
     * @var type
     */
    public $moodleActivitiesCompletionStatus;
    /**
     * 
     * @var type
     */
    public $created_by = null;
    /**
     *
     * @var type 
     */
    public $description;

    /**
     * 
     */
    public function getWorkflowStatus() {}

    /**
     * 
     * @return type
     */
    public function rules() {
        return [
           /* [['name', 'modname'], 'string', 'max' => 255],
            [['uservisible', 'moodleActivitiesCompletionStatus'], 'integer'],*/
        ];
    }

    /**
     * 
     * @return type
     */
    public function attributeLabels() {
        return [
            //'id' => AmosMoodle::_t('ID'),
            'modname' => AmosMoodle::_t('Tipologia'),
            'name' => AmosMoodle::_t('Attività'),
            'moodleActivitiesCompletionStatus' => AmosMoodle::_t('Stato'),
        ];
    }

    /**
     * 
     * @return type
     */
    public function representingColumn() {
        return [
                //inserire il campo o i campi rappresentativi del modulo
        ];
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
    public function getLessonList($contentsList)
    {
        $modulesList = $contentsList[0]["modules"];
        //pr($modulesList);
        $lessonList = array();
        $visible = true;
        $i = 0;
        foreach ($modulesList as $key => $lesson) {
            $newLesson = new Lesson();
            $newLesson->id = $lesson['id'];
            $newLesson->name = $lesson['name'];
            $newLesson->url = $lesson['url'];
            $newLesson->instance = $lesson['instance'];
            /* if($lesson["modname"]=="resource"){
              foreach ($lesson["contents"] as $content){
              if($content["mimetype"]=="text/html"){//TODO: Controllare questa condizione
              $newLesson->url =$content["fileurl"]."&token=463772ddd25856f990ce6ad4c60ff70f";
              break;
              }
              }
              } */ //TODO: Per le risorse vedere se si riesce ad aprire direttamente la risorsa

            $newLesson->uservisible = $lesson['uservisible'];
            $newLesson->modname = $lesson['modname'];
            $newLesson->moodleActivitiesCompletionStatus = $lesson['moodleActivitiesCompletionStatus'];

            array_push($lessonList, $newLesson);
        }
        
        return $lessonList;
    }

    /**
     * 
     * @return string
     */
    public function __toString() {
        return '';
    }

}
