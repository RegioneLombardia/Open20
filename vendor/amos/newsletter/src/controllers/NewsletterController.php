<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\newsletter\controllers 
 */
 
namespace amos\newsletter\controllers;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class NewsletterController 
 * This is the class for controller "NewsletterController".
 * @package amos\newsletter\controllers 
 */
class NewsletterController extends \amos\newsletter\controllers\base\NewsletterController
{
    public function behaviors() {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'get-groups-by-list-ajax',
                        ],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }
}
