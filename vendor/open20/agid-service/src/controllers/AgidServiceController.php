<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\controllers
 * @category   CategoryName
 */

namespace open20\agid\service\controllers;

use open20\amos\core\helpers\Html;
use open20\agid\person\Module;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class AgidServiceController
 * This is the class for controller "AgidServiceController".
 * @package open20\agid\service\controllers
 */
class AgidServiceController extends \open20\agid\service\controllers\base\AgidServiceController
{

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosservice', 'grammar_model_plural');
            $urlLinkAll   = '/service/agid-service/index';
        } else {
            $titleSection = Module::t('amosservice', 'grammar_model_plural');
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea un nuovo servizio';
        $urlCreate   = '/service/agid-service/create';

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'servizi',
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

    public function actionGlobal($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out                         = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {

            $query = new Query;
            $query->select(new \yii\db\Expression("id, name AS text"))
                ->from('agid_service')
                ->andWhere(['status' => \open20\agid\service\models\AgidService::AGID_SERVICE_STATUS_VALIDATED])
                ->andWhere(['like', 'name', $q])
                ->andWhere(['deleted_at' => null]);

            if (!empty($id)) {
                $query->andWhere(['<>', 'id', $id]);
            }
            $command = $query->createCommand();

            $data = $command->queryAll();

            $out['results'] = array_values($data);
        }
        return $out;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $result = ArrayHelper::merge(
                parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className(),
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'global',
                            ],
                            'roles' => ['BASIC_USER', 'ADMIN']
                        ],
                    ],
                ],
                ]
        );

        return $result;
    }
}