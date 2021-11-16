<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\controllers\base
 */

namespace backend\modules\campains\controllers\base;

use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\models\search\PreferenceCampainSearch;
use backend\modules\campains\Module;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\helpers\Url;

/**
 * Class PreferenceCampainController
 * PreferenceCampainController implements the CRUD actions for PreferenceCampain model.
 *
 * @property \backend\modules\campains\models\PreferenceCampain $model
 * @property \backend\modules\campains\models\search\PreferenceCampainSearch $modelSearch
 *
 * @package backend\modules\campains\controllers\base
 */
class PreferenceCampainController extends CrudController
{
    

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {

        $this->setModelObj(new PreferenceCampain());
        $this->setModelSearch(new PreferenceCampainSearch());

        $this->setAvailableViews([

                'list' => [
                    'name' => 'list',
                    'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                    'url' => '?currentView=list'
                    ],
            
            /*
            'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                'url' => '?currentView=list'
                ],
                'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),
                'url' => '?currentView=icon'
                ],
                'map' => [
                'name' => 'map',
                'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),
                'url' => '?currentView=map'
                ],
                'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),
                'url' => '?currentView=calendar'
                ],
            */
        ]);

        parent::init();

        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');    
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {

        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        \Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amosnews', 'Nuova campagna'),
            'urlCreateNew' => ['/campains/wizard'],
        ];
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }

        return true;
    }



    /**
     * Lists all PreferenceCampain models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {   
        $layout = 'bootstrap-italia-layout-with-sidebar';
        \Yii::$app->view->params['createNewBtnParams'] = ['urlCreateNew'=> ['/events/wizard' ]];
        \Yii::$app->view->params['enableLayoutList'] = true;
        \Yii::$app->view->params['enableChangeView'] = true;

        Url::remember();
        $this->setDataProvider($this->modelSearch->search(\Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }
    
    /**
     * TODO - Il delete deve eliminare anche tutte le entità collegate... Per adesso ok, mi sparisce dall'elenco e va bene così
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        /** @var PreferenceCampain $model */
        $model = $this->model;
        if ($model) {
            $model->delete();
            if (! $model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }

    protected function getColorChoices($colorChoiches)
    {
        $toret = [];

        foreach ($colorChoiches as $key => $value) {
            $toret[$key] = $value['label'];
        }

        return $toret;
    }

    protected function getTitleChoices($colorChoiches)
    {
        $toret = [];

        foreach ($colorChoiches as $key => $value) {
            $toret[$key] = $value['default_text'];
        }

        return $toret;
    }


}
