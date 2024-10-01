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

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\models\ProfiloSediUserMm;
use open20\amos\organizzazioni\Module;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

/**
 * Class JoinProfiloSediWidget
 * @package open20\amos\organizzazioni\widgets
 */
class JoinProfiloSediWidget extends Widget
{
    const MODAL_CONFIRM_BTN_OPTIONS = ['class' => 'btn btn-navigation-primary'];
    const MODAL_CANCEL_BTN_OPTIONS = [
        'class' => 'btn btn-secondary',
        'data-dismiss' => 'modal'
    ];
    const BTN_CLASS_DFL = 'btn btn-navigation-primary';
    
    /**
     * @var Module $organizzazioniModule
     */
    protected $organizzazioniModule = null;

    /**
     * @var ProfiloSedi $model
     */
    public $model = null;
    
    /**
     * @var int $userId
     */
    public $userId = 0;

    /**
     * @var bool|false true if we are in edit mode, false if in view mode or otherwise
     */
    public $modalButtonConfirmationStyle = '';
    public $modalButtonConfirmationOptions = [];
    public $modalButtonCancelStyle = '';
    public $modalButtonCancelOptions = [];
    public $divClassBtnContainer = '';
    public $customBtnLabel = '';
    public $btnClass = '';
    public $btnStyle = '';
    public $btnOptions = [];
    public $isProfileView = false;
    public $isGridView = false;
    public $useIcon = false;

    public $onlyModals = false;
    public $onlyButton = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->organizzazioniModule = Module::instance();
    
        parent::init();
    
        if (is_null($this->model)) {
            throw new \Exception(Module::t('amosorganizzazioni', '#missing_model'));
        }

        if (empty($this->modalButtonConfirmationOptions)) {
            $this->modalButtonConfirmationOptions = self::MODAL_CONFIRM_BTN_OPTIONS;
            if (empty($this->modalButtonConfirmationStyle)) {
                if ($this->isProfileView) {
                    $this->modalButtonConfirmationOptions['class'] = $this->modalButtonConfirmationOptions['class'] . ' modal-btn-confirm-relative';
                }
            } else {
                $this->modalButtonConfirmationOptions = ArrayHelper::merge(self::MODAL_CONFIRM_BTN_OPTIONS, ['style' => $this->modalButtonConfirmationStyle]);
            }
        }
        if (empty($this->modalButtonCancelOptions)) {
            $this->modalButtonCancelOptions = self::MODAL_CANCEL_BTN_OPTIONS;
            if (empty($this->modalButtonCancelStyle)) {
                if ($this->isProfileView) {
                    $this->modalButtonCancelOptions['class'] = $this->modalButtonCancelOptions['class'] . ' modal-btn-cancel-relative';
                }
            } else {
                $this->modalButtonCancelOptions = ArrayHelper::merge(self::MODAL_CANCEL_BTN_OPTIONS, ['style' => $this->modalButtonCancelStyle]);
            }
        }

        if (empty($this->btnOptions)) {
            if (empty($this->btnClass)) {
                if ($this->isProfileView) {
                    $this->btnClass = 'btn btn-secondary';
                } elseif ($this->useIcon) {
                    $this->btnClass = 'btn btn-tool-secondary';
                } else {
                    $this->btnClass = self::BTN_CLASS_DFL;
                }
            }
            $this->btnOptions = ['class' => $this->btnClass . (($this->isGridView && !$this->useIcon) ? ' font08' : '')];
            if (!empty($this->btnStyle)) {
                $this->btnOptions = ArrayHelper::merge($this->btnOptions, ['style' => $this->btnStyle]);
            }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var ProfiloSediUserMm $model */
        $model = $this->model;
        $buttonUrl = null;
        $title = '';
        $dataTarget = '';
        $dataToggle = '';
        $userId = (($this->userId > 0) ? $this->userId : Yii::$app->getUser()->getId());
        
        if ($model instanceof ProfiloSediUserMm) {
            $userHeadquarter = $model;
            
            /** @var Profilo $modelProfilo */
            $modelProfilo = $this->organizzazioniModule->createModel('ProfiloSedi');
            $model = $modelProfilo::findOne($userHeadquarter->profilo_sedi_id);
        } else {
            /** @var ProfiloSediUserMm $modelMm */
            $modelMm = $this->organizzazioniModule->createModel('ProfiloSediUserMm');
            $userHeadquarter = $modelMm::findOne(['profilo_sedi_id' => $model->id, 'user_id' => $userId]);
        }
        
        if (is_null($userHeadquarter)) {
            $icon = 'plus';
            $title = (!empty($this->customBtnLabel) ? $this->customBtnLabel : Module::t('amosorganizzazioni', '#join'));
            $dataToggle = 'modal';
            $dataTarget = '#joinPopup-' . $model->id;
            $buttonUrl = null;
            Modal::begin([
                'id' => 'joinPopup-' . $model->id,
                'header' => $title
            ]);
            echo Html::tag('div',
                Module::t('amosorganizzazioni', "#do_you_wish_add_headquarter") .
                " <strong>" . $model->name . "</strong> " . Module::t('amosorganizzazioni', "#to_your_network"));
            echo Html::tag('div',
                Html::a(Module::t('amosorganizzazioni', '#cancel'), null,
                    $this->modalButtonCancelOptions)
                . Html::a(Module::t('amosorganizzazioni', '#yes'),
                    ['/organizzazioni/profilo-sedi/join-headquarter', 'headquarterId' => $model->id, 'userId' => $userId],
                    $this->modalButtonConfirmationOptions),
                ['class' => 'pull-right m-15-0']
            );
            Modal::end();
        }
        
        if (empty($title) || $this->onlyModals) {
            return '';
        } else {
            $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
                'title' => $title
            ]);
        }
        if (isset($disabled)) {
            $this->btnOptions['class'] = $this->btnOptions['class'] . ' disabled';
        }
        if (!empty($dataTarget) && !empty($dataToggle)) {
            $this->btnOptions = ArrayHelper::merge($this->btnOptions, [
                'data-target' => $dataTarget,
                'data-toggle' => $dataToggle
            ]);
        }
        if ($this->useIcon) {
            $this->btnOptions['class'] = $this->btnOptions['class'] . ' m-r-5';
            $btn = Html::a(AmosIcons::show($icon), $buttonUrl, $this->btnOptions);
        } else {
            $btn = Html::a($title, $buttonUrl, $this->btnOptions);
        }
        if (!empty($this->divClassBtnContainer)) {
            $btn = Html::tag('div', $btn, ['class' => $this->divClassBtnContainer]);
        }
        
        return $btn;
    }
}
