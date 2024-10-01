<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\controllers
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\controllers;

use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\record\Record;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloGroups;
use open20\amos\organizzazioni\models\ProfiloTypesPmi;
use open20\amos\organizzazioni\Module;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class ProfiloGroupsController
 * @package open20\amos\organizzazioni\controllers
 */
class ProfiloGroupsController extends \open20\amos\organizzazioni\controllers\base\ProfiloGroupsController
{
    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setMmTableName($this->organizzazioniModule->model('ProfiloGroupsMm'));
        $this->setStartObjClassName($this->organizzazioniModule->model('ProfiloGroups'));
        $this->setMmStartKey('profilo_group_id');
        $this->setTargetObjClassName($this->organizzazioniModule->model('Profilo'));
        $this->setMmTargetKey('profilo_id');
        $this->setTargetUrl('associa-m2m');
        $this->setRedirectAction('update');
        $this->setModuleClassName(Module::className());
        $this->setCustomQuery(true);
        
        $this->setUpLayout('main');
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'associa-m2m',
                            'elimina-m2m',
                            'annulla-m2m',
                            'group-organizations',
                        ],
                        'roles' => ['PROFILOGROUPS_UPDATE']
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
    }
    
    /**
     * @param ProfiloGroups $model
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociaM2mQuery($model)
    {
        /** @var ActiveQuery $query */
        $query = $model->getAssociationTargetQuery($model->id);
        $post = \Yii::$app->request->post();
        if (isset($post['genericSearch']) && (strlen($post['genericSearch']) > 0)) {
            $profiloTable = Profilo::tableName();
            $profiloTypesPmiTable = ProfiloTypesPmi::tableName();
            $query->innerJoinWith('tipologiaDiOrganizzazione');
            $query->andWhere([
                'or',
                ['like', $profiloTable . '.name', $post['genericSearch']],
                ['like', $profiloTable . '.partita_iva', $post['genericSearch']],
                ['like', $profiloTable . '.codice_fiscale', $post['genericSearch']],
                ['like', $profiloTypesPmiTable . '.name', $post['genericSearch']],
            ]);
        }
        return $query;
    }
    
    /**
     * Organizations of a group m2m widget - Ajax call to redraw the widget
     *
     * @param int $id
     * @param string $classname
     * @param array $params
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGroupOrganizations($id, $classname, array $params)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);
            
            /** @var Record $object */
            $object = \Yii::createObject($classname);
            $model = $object->findOne($id);
            $isUpdate = $params['isUpdate'];
            
            return $this->render('group-organizations', [
                'model' => $model,
                'isUpdate' => $isUpdate,
            ]);
        }
        return null;
    }
}
