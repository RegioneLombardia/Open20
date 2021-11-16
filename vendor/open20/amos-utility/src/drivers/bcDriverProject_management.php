<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers;

use open20\amos\utility\drivers\base\bcDriver;

use open20\amos\projectmanagement\models\Projects;
use open20\amos\projectmanagement\models\search\ProjectsSearch;

use open20\amos\projectmanagement\widgets\icons\WidgetIconCreatedByProjects;
use open20\amos\projectmanagement\widgets\icons\WidgetIconMyProjects;
use open20\amos\projectmanagement\widgets\icons\WidgetIconProjectsActivities;

/**
 * 
 */
class bcDriverProject_management extends bcDriver
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName = Project::className(); // put here your model
        $this->widgetIconNames = [
//            WidgetIconCreatedByProjects::getWidgetIconName() => WidgetIconCreatedByProjects::classname(),
//            WidgetIconMyProjects::getWidgetIconName() => WidgetIconMyProjects::classname(),
//            WidgetIconProjectsActivities::getWidgetIconName() => WidgetIconProjectsActivities::classname(),
        ];
    }
    
    /**
     * Put here your search queries
     */
    public function searchWidgetIconCreatedByProjects() {
        $search = new ProjectsSearch();
        $this->query =  $search->searchCreatedByProjects([])->query;
        
//        $projectsSearch = new ProjectsSearch();
//        $this->setBulletCount(
//            $this->makeBulletCounter(
//                Yii::$app->getUser()->getId(),
//                Projects::className(),
//                $projectsSearch->searchCreatedByProjects([])->query
//            )
//        );
    }
    
    public function searchWidgetIconMyProjects() {
        $search = new ProjectsSearch();
        $this->query = $search->searchMyProjects([])->query;

        //        if ($this->disableBulletCounters == false) {
//            $projectsSearch = new ProjectsSearch();
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    Projects::className(),
//                    $projectsSearch->searchMyProjects([])->query
//                )
//            );
//        }
    }
    
    public function searchWidgetIconProjectsActivities() {
        $search = new ProjectsSearch();
        $this->query = $search->searchMyProjects([])->query;

        //        if ($this->disableBulletCounters == false) {
//            $projectsSearch = new ProjectsSearch();
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    Projects::className(),
//                    $projectsSearch->searchMyProjects([])->query
//                )
//            );
//        }
    }
    
}
