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

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\Module;
use Yii;
use yii\base\Widget;

/**
 * Class ProfiloCardWidget
 * @package open20\amos\organizzazioni\widgets
 */
class ProfiloCardWidget extends Widget
{
    /**
     * @var Profilo $model
     */
    public $model;

    /**
     * @var bool|false $imgStyleDisableHorizontalFix - do not use class full-height and dynamic margin calculation in case of horizontal img
     */
    public $imgStyleDisableHorizontalFix = false;

    /**
     * @var bool|true $onlyLogo displays only the img (logo) of organization, no card tooltip
     */
    public $onlyLogo = true;

    /**
     * @var bool $enableLink
     */
    public $enableLink = true;

    /**
     * @var bool $absoluteUrl
     */
    public $absoluteUrl = false;

    /**
     * @var bool $inEmail
     */
    public $inEmail = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(Module::t('amosorganizzazioni', '#missing_model'));
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $model = $this->model;
        $html = '';
        //$confirm = $this->getConfirm();
        $confirm = '';

        $url = $model->getModelImageUrl('square_small', false, '/img/img_default.jpg', $this->absoluteUrl);
        $htmlOptions = [
            'class' => !empty($class) ? 'img-responsive ' . $class : 'img-responsive',
            'style' => 'max-width:100px;',
            'alt' => $model->getAttributeLabel('logoOrganization')
        ];

        if ($this->inEmail) {
            $htmlOptions['style'] = 'width:50px; height:auto;';
        }

        $htmlTag = Html::img($url, $htmlOptions);
        $img = Html::tag('div', $htmlTag, ['class' => 'container-img']);

        if ($this->onlyLogo) {
            $link = null;
            if ($this->enableLink) {
                $link = '/organizzazioni/profilo/view?id=' . $model->id;
                if ($this->absoluteUrl) {
                    $link = Yii::$app->getUrlManager()->createAbsoluteUrl($link);
                }
            } 
            $modal = '';
            $controller = Yii::$app->controller;
            if($controller->action->id == 'update'){
                $modal='
                <!-- Modal -->
                <div class="modal fade" id="myModal'.$model->id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel'.$model->id.'">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-danger" id="myModalLabel'.$model->id.'">'.Module::t('amosorganizzazioni', 'Conferma').'</h4>
                      </div>
                      <div class="modal-body">
                      '.Module::t('amosorganizzazioni','#confirm_exit_without_saving').'
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> '.BaseAmosModule::t('amoscore', 'Annulla').' </button>
                        <a class="btn btn-primary" href="'.$link.'" title="'.$model->name.'">'.Module::t('amosorganizzazioni', 'Conferma').'</a>
                      </div>
                    </div>
                  </div>
                </div>';
                $confirm = ['toggle' => 'modal', 'target' => '#myModal'.$model->id];
                $link = '#';
            }
            $html .=  $modal.  Html::a($img, $link, ['title' => $model->name, 'data' => $confirm]);
        } else {
            $img = Html::tag('div', $img, ['class' => 'container-round-img-sm']);
            $modals = JoinProfiloWidget::widget([
                'model' => $this->model,
                'onlyModals' => true
            ]);
            $html = $modals . Html::a(
                    $img,
                    null,
                    [
                        'data' => [
                            'toggle' => 'tooltip',
                            'html' => 'true',
                            'placement' => 'right',
                            'delay' => ['show' => 100, 'hide' => 5000],
                            'trigger' => 'hover',
                            'template' => '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color:transparent;min-width: 200px;"></div></div>'
                        ],
                        'title' => $this->getHtmlTooltip(),
                        'style' => 'border-color:transparent;'
                    ]
                );
        }
        return $html;
    }

    /**
     * @return string
     */
    private function getHtmlTooltip()
    {
        $model = $this->model;

        $viewUrl = "/organizzazioni/profilo/view?id=" . $model->id;
        $url = '/img/img_default.jpg';
        if (!is_null($model->logoOrganization)) {
            $url = $model->logoOrganization->getUrl('original', [
                'class' => 'img-responsive'
            ]);
        }
        Yii::$app->imageUtility->methodGetImageUrl = 'getUrl';
        $roundImage = Yii::$app->imageUtility->getRoundImage($model->logoOrganization);
        $logo = Html::img($url, [
            'class' => $roundImage['class'],
            'style' => ((!$this->imgStyleDisableHorizontalFix) ? "margin-left: " . $roundImage['margin-left'] . "%;" : "") . "margin-top: " . $roundImage['margin-top'] . "%;",
            'alt' => $model->getAttributeLabel('logoOrganization')
        ]);
        $tooltip = '<div class="icon-view"><div class="card-container col-xs-12 nop">' .
            ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/organizzazioni/profilo/update?id=" . $model->id,
                'optionsModify' => [
                    'class' => 'community-modify',
                    'data-target' => '',
                    'data-toggle' => 'modal'
                ],
                'mainDivClasses' => '',
                'disableDelete' => true
            ])
            . '<div class="icon-header grow-pict">
                         <div class="container-round-img">' .
            Html::a($logo, $viewUrl, ['title' => $model->name]) . '</div>';
        $tooltip .= '</div><div class="icon-body">';
        $newsWidget = \open20\amos\notificationmanager\forms\NewsWidget::widget([
            'model' => $model,
        ]);
        $tooltip .= $newsWidget . '<h3>' . Html::a($model->name, $viewUrl, ['title' => $model->name]) . '</h3>';

        $tooltip .= '</div></div></div>';

        return $tooltip;
    }

    /**
     * @return array|null
     */
    public function getConfirm()
    {
        $controller = Yii::$app->controller;
        $isActionUpdate = ($controller->action->id == 'update');
        $confirm = $isActionUpdate ? [
            'confirm' => BaseAmosModule::t('amoscore', '#confirm_exit_without_saving')
        ] : null;
        return $confirm;
    }
}
