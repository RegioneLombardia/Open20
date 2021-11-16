<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\operators\models 
 */

namespace open20\agid\person\controllers;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use open20\amos\core\helpers\Html;
use open20\agid\person\Module;
/**
 * Class AgidPersonController 
 * This is the class for controller "AgidPersonController".
 * @package backend\modules\operators\models 
 */
class AgidPersonController extends \open20\agid\person\controllers\base\AgidPersonController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'document-list',
                        ],
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    
                    'delete' => ['post', 'get'],
                    'document-list' => ['post', 'get']
                ]
            ]
        ]);
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosperson', '#menu_front_person');
            $urlLinkAll   = '';

           
        } else {
            $titleSection = Module::t('amosperson', '#menu_front_person');
            
        }

        $labelCreate = 'Nuova';
        $titleCreate = 'Crea una nuova persona';
        $urlCreate   = '/person/agid-person/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'persone',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'urlCreate' => $urlCreate,
            
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }
    
    /**
     * 
     * @param type $q
     * @param type $id
     * @return string
     */
    public function actionDocumentList($q = null, $id = null) {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, titolo AS text')
                ->from('documenti')
                ->andWhere(['like', 'titolo', $q])
                ->andWhere(['status' =>'DocumentiWorkflow/VALIDATO'])
                ->andWhere(['deleted_at' => null])
                ->limit(20);
            $command = $query->createCommand();
        
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => open20\amos\documenti\models\Documenti::find($id)->titolo];
        }
        return $out;
    }
}
