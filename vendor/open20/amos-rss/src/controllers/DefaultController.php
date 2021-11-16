<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility
 * @category   CategoryName
 */

namespace amos\rss\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use amos\rss\utility\FeedUtility;
use Yii;

class DefaultController extends Controller
{
    public  $layout = "main";
    
    private $feedUtility = null;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
        $this->feedUtility = new FeedUtility();
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
        $module = Yii::$app->getModule('layout');
        if(empty($module)){
            $this->layout =  '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }

    /**
     * 
     * @return type
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    /**
     * 
     * @param string $url
     * @return type
     */
    public function actionView($url)
    {
        
        $feed = null;
        try{
            $feed = Yii::$app->rss->reader()->import($url);
        }catch(Exception $ex){
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        } catch (\Zend\Feed\Reader\Exception\RuntimeException $zex){
            Yii::getLogger()->log($zex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        
        
        return $this->render('view', ['feed' => $feed]);
    }
    
}