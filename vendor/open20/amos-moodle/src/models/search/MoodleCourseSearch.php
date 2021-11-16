<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models\search
 * @category   CategoryName
 */

namespace open20\amos\moodle\models\search;

use open20\amos\moodle\models\MoodleCourse;
use open20\amos\moodle\models\ServiceCall;

use yii\base\Model;

/**
 * MoodleCourseSearch represents the model behind the search form about `open20\amos\moodle\models\MoodleCourse`.
 */
class MoodleCourseSearch extends MoodleCourse {

    /**
     */
    public function rules() {
        return [
            [['moodle_categoryid'], 'integer'],
            [
                [
                    'name',
                ],
                'safe'
            ],
        ];
       
    }

    public function getScope($params) {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        return $scope;
    }

    /**
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Metodo search da utilizzare per recuperare i corsi aperti con i dati di iscrizione relativi ad un certo utente
     *
     * @param array $params Array di parametri
     * @param int $userId utente per cui si vogliono sapere le info sull'iscrizione
     * @return ArrayDataProvider
     */
    public function searchOpenCoursesForUser($params, $userId) {
        $this->load($params);
       
        $serviceCall = new ServiceCall();
        $serviceCall->setUserMoodle($userId);

        $withImages = true;
        $categoryId = null;
        if(!empty($this->moodle_categoryid)){
            $categoryId = $this->moodle_categoryid;
        }

        $allCoursesArray = $serviceCall->getCoursesList($withImages, /* $courseId = */ null, /* $categoryId = */$categoryId , /* $forceAll = */ false, /* $onlySelfEnrolment = */ true);

        $coursesUserEnrolledArray = $serviceCall->getCoursesUserEnrolled();
        //pr($coursesUserEnrolledArray);exit();
        $coursesList = $this->getCourseList($allCoursesArray, $coursesUserEnrolledArray);
        //pr($coursesList);

        if (!empty($this->name)) {
            $coursesListTmp = [];
            foreach ($coursesList as $corso) {
                if (strpos(strtolower($corso->name), strtolower($this->name)) !== false) {
                    //pr($corso->name);
                    array_push($coursesListTmp, $corso);
                }
            }
            $coursesList = $coursesListTmp;
        }

        $dataProvider = new \yii\data\ArrayDataProvider(['allModels' => $coursesList]);

        return $dataProvider;
    }

}
