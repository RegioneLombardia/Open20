<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\thematics\base
 */


namespace backend\modules\thematics\controllers\base;


use backend\modules\thematics\models\Operator;
use backend\modules\thematics\models\Thematic;
use backend\modules\thematics\models\search\ThematicSearch;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\porting\console\models\AuthAssignment;
use preference\userprofile\models\Tag;
use preference\userprofile\models\UserChannel;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use preference\userprofile\models\PreferenceChannel;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


/**
 * Class OperatorController
 * OperatorController implements the CRUD actions for Operator model.
 *
 * @property \backend\modules\thematics\models\Operator $model
 * @property \backend\modules\thematics\models\search\thematicsearch $modelSearch
 *
 * @package backend\modules\thematics\base
 */
class ThematicController extends CrudController

{


    /**
     * @var string $layout
     */

    public $layout = 'main';


    public function init()
    {

        $this->setModelObj(new Thematic());

        $this->setModelSearch(new ThematicSearch());

        $this->setAvailableViews([

            'grid' => [

                'name' => 'grid',

                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),

                'url' => '?currentView=grid'

            ],


        ]);


        parent::init();

    }


    /**
     * Lists all Operator models.
     * @return mixed
     */

    public function actionIndex($layout = NULL)

    {

        Url::remember();
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar', []);
        $transaction = \Yii::$app->db->beginTransaction();

        if ($_POST['topic']) {
            try {
                TopicTagUtility::disableAllChannels();
                TopicTagUtility::disableAllTopicTag();
                foreach ($_POST['topic'] as $topic => $id) {
                    $tag = Tag::findOne(['id' => $topic]);
                    $tag->pc_active = 1;
                    $tag->save();
                }
                foreach ($_POST['canale'] as $channels => $id) {
                    $channel = PreferenceChannel::findOne(['id' => $channels]);
                    $channel->active = 1;
                    $channel->save();
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

            return $this->render('index', [

                'model' => $this->model,

            ]);
        }


   
}
