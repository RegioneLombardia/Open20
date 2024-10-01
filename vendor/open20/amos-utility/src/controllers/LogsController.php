<?php
namespace open20\amos\utility\controllers;

use open20\amos\utility\Module;
use yii\web\Controller;
use Yii;

class LogsController extends Controller
{
    public $layout = "main";
    public $log_file_path;


    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null){
        if ($layout === false){
            $this->layout = false;
            return true;
        }
        $module = \Yii::$app->getModule('layout');
        if(empty($module)){
            $this->layout =  '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }

    public function actionIndex() {

        if (!$this->log_file_path) {
            $this->log_file_path = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'app.log';
        }

        try {
            $log = file_get_contents($this->log_file_path);
        } catch (ErrorException $ex) {
            return Module::t('amosutility', 'Log File Not Found');
        }

        $matches = [];

        $rotfl = preg_match_all('/(((?!\]\n).)*\]\n)/s', $log, $matches, PREG_SET_ORDER);

        return $this->render('index', [
            'matches' => $matches,
            'log' => $log
        ]);
    }
}