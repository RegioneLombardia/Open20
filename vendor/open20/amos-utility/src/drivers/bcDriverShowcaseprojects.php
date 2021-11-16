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

use open20\amos\showcaseprojects\models\ShowcaseProject;
use open20\amos\showcaseprojects\models\search\ShowcaseProjectSearch;
use open20\amos\showcaseprojects\models\search\ShowcaseProjectProposalSearch;
use open20\amos\showcaseprojects\models\search\InitiativeSearch;

use open20\amos\showcaseprojects\widgets\icons\WidgetIconInitiativesCreatedBy;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconInitiativesProposalToValidate;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconInitiativesToValidate;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconShowcaseProjectProposalsCreatedBy;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconShowcaseProjectProposalsToValidate;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconShowcaseProjects;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconShowcaseProjectsAll;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconShowcaseProjectsCreatedBy;
use open20\amos\showcaseprojects\widgets\icons\WidgetIconShowcaseProjectsToValidate;

/**
 * 
 */
class bcDriverShowcaseprojects extends bcDriver
{
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName = ShowcaseProject::className();
        $this->widgetIconNames = [
            WidgetIconInitiativesCreatedBy::getWidgetIconName() => WidgetIconInitiativesCreatedBy::classname(),
            WidgetIconInitiativesProposalToValidate::getWidgetIconName() => WidgetIconInitiativesProposalToValidate::classname(),
            WidgetIconInitiativesToValidate::getWidgetIconName() => WidgetIconInitiativesToValidate::classname(),
            WidgetIconShowcaseProjectProposalsCreatedBy::getWidgetIconName() => WidgetIconShowcaseProjectProposalsCreatedBy::classname(),
            WidgetIconShowcaseProjectProposalsToValidate::getWidgetIconName() => WidgetIconShowcaseProjectProposalsToValidate::classname(),
            WidgetIconShowcaseProjects::getWidgetIconName() => WidgetIconShowcaseProjects::classname(),
            WidgetIconShowcaseProjectsAll::getWidgetIconName() => WidgetIconShowcaseProjectsAll::classname(),
            WidgetIconShowcaseProjectsCreatedBy::getWidgetIconName() => WidgetIconShowcaseProjectsCreatedBy::classname(),
            WidgetIconShowcaseProjectsToValidate::getWidgetIconName() => WidgetIconShowcaseProjectsToValidate::classname(),
        ];
    }
    
    /**
     * Put here your search queries
     */
    public function searchWidgetIconInitiativesCreatedBy() {
        $search = new InitiativeSearch();
        $this->query = $search->buildQuery([], 'created-by');

        //        if ($this->disableBulletCounters == false) {
//            $search = new InitiativeSearch();
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    Initiative::className(),
//                    $search->buildQuery([], 'created-by')
//                )
//            );
//        }
    }
    
    public function searchWidgetIconInitiativesProposalToValidate() {
        $search = new InitiativeSearch();
        if (Yii::$app->user->can($search->getValidatorRole())) {
            $this->query = Initiative::find()
                ->andWhere([
                    'status' => Initiative::INITIATIVE_WORKFLOW_STATUS_PROSPOSALTOVALIDATE
                ]);
        }
        
            //        if ($this->disableBulletCounters == false) {
//            $count = 0;
//            $search = new InitiativeSearch();
//            if (Yii::$app->user->can($search->getValidatorRole())) {
//                $dataProvider = new ActiveDataProvider([
//                    'query' => Initiative::find()
//                        ->andWhere(['status' => Initiative::INITIATIVE_WORKFLOW_STATUS_PROSPOSALTOVALIDATE])
//                ]);
//                $count = $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    Initiative::className(),
//                    $dataProvider
//                );
//            }
//
//            $this->setBulletCount($count);
//        }
    }
    
    public function searchWidgetIconInitiativesToValidate() {
        $search = new InitiativeSearch();
        if (Yii::$app->user->can($search->getValidatorRole())) {
            $this->query = $search->buildQuery([], 'to-validate');
        }

        //        if ($this->disableBulletCounters == false) {
//            $count = 0;
//            $search = new InitiativeSearch();
//            if (Yii::$app->user->can($search->getValidatorRole())) {
//                $count = $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    Initiative::className(),
//                    $search->buildQuery([], 'to-validate')
//                );
//            }
//
//            $this->setBulletCount($count);
//        }
    }
    
    public function searchWidgetIconShowcaseProjectProposalsCreatedBy() {
        $search = new ShowcaseProjectProposalSearch();
        $this->query = $search->buildQuery([], 'created-by');
        
        //        $search = new ShowcaseProjectProposalSearch();
//        $this->setBulletCount(
//            $this->makeBulletCounter(
//                Yii::$app->getUser()->getId(),
//                ShowcaseProjectProposal::className(),
//                $search->buildQuery([], 'created-by')
//            )
//        );
    }
    
    public function searchWidgetIconShowcaseProjectProposalsToValidate() {
        $search = new ShowcaseProjectProposalSearch();
        if (Yii::$app->user->can($search->getValidatorRole())) {
            $this->query = $search->buildQuery([], 'to-validate');
        }

//            $this->setBulletCount($count);
//        }
        //        if ($this->disableBulletCounters == false) {
//            $count = 0;
//            $search = new ShowcaseProjectProposalSearch();
//            if (Yii::$app->user->can($search->getValidatorRole())) {
//                $count = $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    ShowcaseProjectProposal::className(),
//                    $search->buildQuery([], 'to-validate')
//                );
//            }
//
//            $this->setBulletCount($count);
//        }
    }
    
    public function searchWidgetIconShowcaseProjects() {
        $search = new ShowcaseProjectSearch();
        $this->query = $search->buildQuery([], 'own-interest');

//        if ($this->disableBulletCounters == false) {
//            $search = new ShowcaseProjectSearch();
//            $search->setEventAfterCounter();
//            $query = $search->buildQuery([], 'own-interest');
//            
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    ShowcaseProject::className(),
//                    $query
//                )
//            );
//            
//            \Yii::$app->session->set('_offQuery', $query);
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }
    }
    
    public function searchWidgetIconShowcaseProjectsAll() {
        $search = new ShowcaseProjectSearch();
        $this->query = $search->buildQuery([], 'all');
        //        if ($this->disableBulletCounters == false) {
//            $search = new ShowcaseProjectSearch();
//            $search->setEventAfterCounter();
//            $query = $search->buildQuery([], 'all');
//            
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    ShowcaseProject::class,
//                    $query
//                )
//            );
//            
//            \Yii::$app->session->set('_offQuery', $query);
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }
    }
    
    public function searchWidgetIconShowcaseProjectsCreatedBy() {
        $search = new ShowcaseProjectSearch();
        $this->query = $search->buildQuery([], 'created-by');
        
        //        if ($this->disableBulletCounters == false) {
//            $search = new ShowcaseProjectSearch();
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    ShowcaseProject::className(),
//                    $search->buildQuery([], 'created-by')
//                )
//            );
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }
    }
    
    public function searchWidgetIconShowcaseProjectsToValidate() {
        $search = new ShowcaseProjectSearch();
        $this->query = $search->buildQuery([], 'to-validate');

        //        if ($this->disableBulletCounters == false) {
//            $search = new ShowcaseProjectSearch();
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    ShowcaseProject::className(),
//                    $search->buildQuery([], 'to-validate')
//                )
//            );
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }
    }
                    
}
