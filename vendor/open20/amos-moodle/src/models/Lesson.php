<?php

namespace open20\amos\moodle\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use open20\amos\moodle\AmosMoodle;

/**
 * This is the model class for table Moodle Lesson.
 */
class Lesson extends Model {

    public $id;
    public $name;
    public $uservisible;
    public $modname;
    public $instance;
    public $url;
    public $moodleActivitiesCompletionStatus;

    public function rules() {
        return [
           /* [['name', 'modname'], 'string', 'max' => 255],
            [['uservisible', 'moodleActivitiesCompletionStatus'], 'integer'],*/
        ];
    }

    public function attributeLabels() {
        return [
            //'id' => AmosMoodle::t('amosmoodle', 'ID'),
            'modname' => AmosMoodle::t('amosmoodle', 'Tipologia'),
            'name' => AmosMoodle::t('amosmoodle', 'Attività'),
            'moodleActivitiesCompletionStatus' => AmosMoodle::t('amosmoodle', 'Stato'),
        ];
    }

    public function representingColumn() {
        return [
                //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints() {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute) {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function getLessonList($contentsList) {

        $modulesList = $contentsList[0]["modules"];
        //pr($modulesList);
        $lessonList = array();
        foreach ($modulesList as $lesson) {
            $newLesson = new Lesson();
            $newLesson->id = $lesson["id"];
            $newLesson->name = $lesson["name"];
            $newLesson->url = $lesson["url"];
            $newLesson->instance = $lesson["instance"];
            /* if($lesson["modname"]=="resource"){
              foreach ($lesson["contents"] as $content){
              if($content["mimetype"]=="text/html"){//TODO: Controllare questa condizione
              $newLesson->url =$content["fileurl"]."&token=463772ddd25856f990ce6ad4c60ff70f";
              break;
              }
              }
              } */ //TODO: Per le risorse vedere se si riesce ad aprire direttamente la risorsa

            $newLesson->uservisible = $lesson["uservisible"];
            $newLesson->modname = $lesson["modname"];
            $newLesson->moodleActivitiesCompletionStatus = $lesson["moodleActivitiesCompletionStatus"];
            array_push($lessonList, $newLesson);
        }
        return $lessonList;
    }

    public function __toString() {
        return "";
    }

}
