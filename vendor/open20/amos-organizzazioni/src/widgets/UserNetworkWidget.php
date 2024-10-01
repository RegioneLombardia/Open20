<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\widgets
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\widgets;

use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\Module;
use yii\base\Widget;

/**
 * Class UserNetworkWidget
 * @package open20\amos\organizzazioni\widgets
 */
class UserNetworkWidget extends Widget
{
    /**
     * @var int $userId
     */
    public $userId = null;
    
    /**
     * @var bool|false true if we are in edit mode, false if in view mode or otherwise
     */
    public $isUpdate = false;
    
    /**
     * @var string $gridId
     */
    public $gridId = 'user-organizzazioni-grid';
    
    /**
     * @var string $gridSediId
     */
    public $gridSediId = 'user-sedi-grid';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (is_null($this->userId)) {
            throw new \Exception(Module::t('amosorganizzazioni', '#Missing_user_id'));
        }
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var Module $organizationsModule */
        $organizationsModule = Module::instance();
        
        /** @var Profilo $modelProfilo */
        $modelProfilo = $organizationsModule->createModel('Profilo');
        
        /** @var ProfiloSedi $modelProfiloSedi */
        $modelProfiloSedi = $organizationsModule->createModel('ProfiloSedi');
        
        /** @var UserNetworkWidgetOrganizzazioni $userNetworkWidgetOrganizzazioniClassName */
        $userNetworkWidgetOrganizzazioniClassName = $modelProfilo::getUserNetworkWidgetOrganizzazioniClassName();
        
        /** @var UserNetworkWidgetSedi $userNetworkWidgetSediClassName */
        $userNetworkWidgetSediClassName = $modelProfiloSedi::getUserNetworkWidgetSediClassName();
        
        $post = \Yii::$app->request->post();
        $organizzazioniPostName = $userNetworkWidgetOrganizzazioniClassName::getSearchPostName();
        $sediPostName = $userNetworkWidgetSediClassName::getSearchPostName();
        
        $organizzazioniWidget = '';
        $sediWidget = '';
        $sedi_enabled = true;
        
        if (
            $organizationsModule->enabled_widget_organizzazioni === true
            && (
                !$post
                || (
                    $post
                    && isset($post[$organizzazioniPostName])
                )
            )
        ) {
            $organizzazioniWidget = $userNetworkWidgetOrganizzazioniClassName::widget([
                'userId' => $this->userId,
                'isUpdate' => $this->isUpdate,
                'gridId' => $this->gridId,
            ]);
        }
        
        if (!is_null($organizationsModule->enabled_widget_sedi)) {
            $sedi_enabled = $organizationsModule->enabled_widget_sedi;
        }
        
        if ($sedi_enabled && (!$post || ($post && isset($post[$sediPostName])))) {
            $sediWidget = $userNetworkWidgetSediClassName::widget([
                'userId' => $this->userId,
                'isUpdate' => $this->isUpdate,
                'gridId' => $this->gridSediId,
            ]);
        }
    
        return $organizzazioniWidget
            . $sediWidget;
    }
}
