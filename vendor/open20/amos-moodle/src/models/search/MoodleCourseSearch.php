<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models\search
 * @category   CategoryName
 */

namespace open20\amos\moodle\models\search;

use open20\amos\moodle\models\MoodleCourse;
use open20\amos\moodle\models\ServiceCall;
use open20\amos\moodle\utility\MoodleUtility;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use Yii;
use yii\data\ActiveDataProvider;

use yii\base\Model;

/**
 * MoodleCourseSearch represents the model behind the search form about `open20\amos\moodle\models\MoodleCourse`.
 */
class MoodleCourseSearch
    extends MoodleCourse
    implements SearchModelInterface, ContentModelSearchInterface, CmsModelInterface
{

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

    /**
     * @param type $params
     * 
     * @return string
     */
    public function getScope($params) {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        
        return $scope;
    }

    /**
     * bypass scenarios() implementation in the parent class
     *
     */
    public function scenarios() {
        
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
        
        $allCoursesArray = $serviceCall->getCoursesList(
            $withImages,
            null,               // $courseId
            $categoryId,        // $categoryId
            false,              // $forceAll
            true                // $onlySelfEnrolment
        );

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
    
    /**
      * 
      * I miei corsi 
      * 
      * @param type $params
      * @param type $userId
      * @return \yii\data\ArrayDataProvider
      */
    public function searchOwnCourses($params, $userId) {
        $this->load($params);   
        $dataProvider = MoodleUtility::getUserCoursesList($userId);
        
        return $dataProvider;
    }
    
    /**
     * Search method useful to retrieve data to show in frontend (with cms)
     * 
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider 
     */
    public function cmsSearch($params, $limit)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = $this->homepageNewsQuery($params);
        $this->applySearchFilters($query);
        
        $query->orderBy(['data_pubblicazione' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_ASC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }
    
    /**
     * Return a list of fields that can be shown in frontend pages made by cms.
     * For each field , also the field type is specified. 
     * In "Backend modules" cms section, user can choose to show only some of these fields.
     * 
     * @return array An array of open20\amos\core\record\CmsField objects
    */
    public function cmsViewFields()
    {
        return [
            new CmsField('titolo', 'TEXT', 'amosmoodle', $this->attributeLabels()['titolo']),
            new CmsField('descrizione', 'TEXT', 'amosmoodle', $this->attributeLabels()['descrizione_breve']),
        ];
    }
    
     /**
      * return the list of fields to search for in frontend pages made by cms.
      * For each field , also the field type is specified. 
      * 
      * @return array An array of open20\amos\core\record\CmsField objects
     */
    public function cmsSearchFields()
    {
        return [
            new CmsField('titolo', 'TEXT'),
            new CmsField('descrizione', 'TEXT'),
        ];
    }
    
    /**
     * Method to know if the module can be viewed from the frontend
     * 
     * @param int $id
     * @return boolean 
     */
    public function cmsIsVisible($id)
    {
        $retValue = false;

        if (isset($id)) {
            $md = $this->findOne($id);
            if (!is_null($md)) {
                $retValue = true;
            }
        }

        return $retValue;
    }

    /**
     * This method define the search default order.
     * @param BaseDataProvider $dataProvider
     * @return BaseDataProvider
     */
    public function searchDefaultOrder($dataProvider)
    {
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort($this->createOrderClause());
        } else {
            // For widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]);
        }

        return $dataProvider;
    }

    /**
     * This method returns the ActiveQuery object that contains the query to retrieve
     * logged user own interest contents.
     * @param array $params
     * @return ActiveQuery
     */
    public function searchOwnInterestsQuery($params) {}

    /**
     * This method returns the ActiveQuery object that contains the query to retrieve
     * logged user all contents.
     * @param array $params
     * @return ActiveQuery
     */
    public function searchAllQuery($params) {}

    /**
     * This method returns the ActiveQuery object that contains the query to retrieve
     * created by logged user contents.
     * @param array $params
     * @return ActiveQuery
     */
    public function searchCreatedByMeQuery($params) {}

    /**
     * This method returns the ActiveQuery object that contains the query to retrieve
     * logged user to validate contents.
     * @param array $params
     * @return ActiveQuery
     */
    public function searchToValidateQuery($params) {}
    
    /**
     * @param array $searchParamsArray Array of search words
     * @param int|null $pageSize
     * @return ActiveDataProvider Do the search on all text fields
     */
    public function globalSearch($searchParamsArray, $pageSize) {}
    
    /**
     * @param object $model The model to convert into SearchResult
     * @return SearchResult 
     */
     public function convertToSearchResult($model) {}
}
