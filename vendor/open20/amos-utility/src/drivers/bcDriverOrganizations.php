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
use openinnovation\organizations\models\Organizations;
use openinnovation\organizations\models\OrganizationsCreationRequest;

use openinnovation\organizations\widgets\icons\WidgetIconOrganizationsCreatedBy;
use openinnovation\organizations\widgets\icons\WidgetIconOrganizationsCreationRequestDashboard;

/**
 * 
 */
class bcDriverOrganizations extends bcDriver {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->modelClassName = Organizations::classname(); // put here your model
        $this->widgetIconNames = [
            WidgetIconOrganizationsCreatedBy::getWidgetIconName() => WidgetIconOrganizationsCreatedBy::classname(),
            WidgetIconOrganizationsCreationRequestDashboard::getWidgetIconName()  => WidgetIconOrganizationsCreationRequestDashboard::classname(),
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconOrganizationsCreatedBy() {
        $this->query = Organizations::find()
            ->andWhere([
                'status' => Organizations::ORGANIZATIONS_WORKFLOW_STATUS_DRAFT,
                'created_by' =>  Yii::$app->user->getId()
            ]);
        
//        if ($this->disableBulletCounters == false) {
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->user->getId(),
//                    Organizations::className(),
//                    Organizations::find()
//                        ->andWhere([
//                            'status' => Organizations::ORGANIZATIONS_WORKFLOW_STATUS_DRAFT,
//                            'created_by' =>  Yii::$app->user->getId()
//                        ])
//                )
//            );
//        }
    }
    
    public function searchWidgetIconOrganizationsCreationRequestDashboard() {
        $this->query = OrganizationsCreationRequest::find()
            ->andWhere(["state" => "presented"]);
        
//        if ($this->disableBulletCounters == false) {
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    OrganizationsCreationRequest::className(),
//                    OrganizationsCreationRequest::find()
//                        ->andWhere(["state" => "presented"])
//                )
//            );
//        }
    }

}
