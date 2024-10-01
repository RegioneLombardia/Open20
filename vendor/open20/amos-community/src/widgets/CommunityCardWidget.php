<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets
 * @category   CategoryName
 */

namespace open20\amos\community\widgets;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\notificationmanager\forms\NewsWidget;
use Yii;
use yii\base\Widget;

/**
 * Class CommunityCardWidget
 * @package open20\amos\community\widgets
 */
class CommunityCardWidget extends Widget
{
    /**
     * @var Community $model
     */
    public $model;

    /**
     * @var bool|false $imgStyleDisableHorizontalFix - do not use class full-height and dynamic margin calculation in case of horizontal img
     */
    public $imgStyleDisableHorizontalFix = false;

    /**
     * @var bool|true $onlyLogo displays only the img (logo) of community, no card tooltip
     */
    public $onlyLogo        = true;
    public $enableLink      = true;
    public $absoluteUrl     = false;
    public $inEmail         = false;
    public $enableHierarchy = true;
    public $avatarCropSize  = 'square_small';

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(AmosCommunity::t('amoscommunity', 'Missing model'));
        }
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $model   = $this->model;
        $html    = '';
       //$confirm = $this->getConfirm();
       $confirm = '';

        $url = $model->getAvatarUrl($this->avatarCropSize, $this->absoluteUrl, true);

        $htmlOptions = [
            'class' => !empty($class) ? 'img-responsive '.$class : 'img-responsive',
            'alt' => $model->getAttributeLabel('communityLogo')
        ];

        if ($this->inEmail) {
            $htmlOptions['style'] = 'width:50px; height:auto;';
        }
        elseif(\Yii::$app->controller->id === 'user-profile') {
            $htmlOptions['style'] = 'max-width:120px;';
        }

        $styleHierachy    = '';
        $htmlTagHierarchy = '';
        if ($this->enableHierarchy && $this->model->level > 1) {
            $styleHierachy = "margin-left: ".($this->model->level * 15)."px";
        }

        if ($this->enableHierarchy && $this->model->level > 0) {
            $htmlTagHierarchy = Html::tag('div', AmosIcons::show('long-arrow-return'),
                    ['class' => 'hierarchy', 'style' => $styleHierachy]);
        }

        $htmlTagImg = Html::img($url, $htmlOptions);
        $img        = Html::tag('div', $htmlTagImg, ['class' => 'container-img']);

        $urlRedirect     = null;
        $communityModule = Yii::$app->getModule('community');
        if (!empty($communityModule) && $communityModule->enableAutoLinkLanding == true && !empty($model->redirect_url)) {
            $urlRedirect = $model->redirect_url;
        }
        if ($this->onlyLogo) {
            $link = null;
            if ($this->enableLink) {
                $link = '/community/join/open-join?id='.$model->id;
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
                        <h4 class="modal-title text-danger" id="myModalLabel'.$model->id.'">'.AmosCommunity::t('amoscommunity', 'Conferma').'</h4>
                      </div>
                      <div class="modal-body">
                      '.AmosCommunity::t('amoscommunity','#confirm_exit_without_saving').'
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> '.BaseAmosModule::t('amoscore', 'Annulla').' </button>
                        <a class="btn btn-primary" href="'.$link.'" title="'.$model->name.'">'.AmosCommunity::t('amoscommunity', 'Conferma').'</a>
                      </div>
                    </div>
                  </div>
                </div>';
                $confirm = ['toggle' => 'modal', 'target' => '#myModal'.$model->id];
                $link = '#';
            }
            $html .=  $modal.  Html::a($img, $link, ['title' => $model->name, 'data' => $confirm]);
        
        } else {
            $modals = JoinCommunityWidget::widget([
                    'model' => $this->model,
                    'onlyModals' => true
            ]);

            $html = $modals.Html::a(
                    $img, null,
                    [
                    'data' => [
                        'toggle' => 'tooltip',
                        'html' => true,
                        'placement' => 'right',
                        'delay' => ['show' => 100, 'hide' => 5000],
                        'trigger' => 'hover',
                        'template' => '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color:transparent;min-width: 200px;"></div></div>'
                    ],
                        'data-toggle' => 'tooltip',
                    'title' => $this->getHtmlTooltip(),
                    'style' => 'border-color:transparent;'
                    ]
            );
        }

        return $html;
    }

    /**
     *
     * @return string
     */
    private function getHtmlTooltip()
    {
        $model = $this->model;

        $viewUrl = "/community/community/view?id=".$model->id;
        $url     = '/img/img_default.jpg';
        if (!is_null($model->communityLogo)) {
            $url = $model->communityLogo->getUrl('square_small', false, true);
        }

        Yii::$app->imageUtility->methodGetImageUrl = 'getUrl';

        $roundImage = Yii::$app->imageUtility->getRoundImage($model->communityLogo);
        $logo       = Html::img($url,
                [
                'class' => $roundImage['class'],
                'style' => ((!$this->imgStyleDisableHorizontalFix) ? "margin-left: ".$roundImage['margin-left']."%;" : "")."margin-top: ".$roundImage['margin-top']."%;",
                'alt' => $model->getAttributeLabel('communityLogo')
        ]);

        $tooltip    = '<div class="icon-view"><div class="card-container col-xs-12 nop">'.
            ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/community/community/update?id=".$model->id,
                'optionsModify' => [
                    'class' => 'community-modify',
                    'data-target' => (($model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED) ? '#visibleOnEditPopup'.$model->id
                        : ''),
                    'data-toggle' => 'modal'
                ],
                'mainDivClasses' => '',
                'disableDelete' => true
            ])
            .'<div class="icon-header grow-pict">
                         <div class="container-round-img">'.
            Html::a($logo, $viewUrl, ['data-toggle' => 'tooltip', 'data-html' => 'true', 'title' => \Yii::$app->formatter->asHtml($model->name)]).'</div>';
        $tooltip    .= JoinCommunityWidget::widget([
                'model' => $model,
                'divClassBtnContainer' => 'under-img',
                'onlyButton' => true
        ]);
        $tooltip    .= '</div><div class="icon-body">';
        $newsWidget = NewsWidget::widget([
                'model' => $model,
        ]);
        $tooltip    .= $newsWidget.'<h3>'.Html::a($model->name, $viewUrl,
                ['data-toggle' => 'tooltip', 'data-html' => 'true', 'title' => \Yii::$app->formatter->asHtml($model->name)]).'</h3>';


        if ($model->validated_once) {
            $icons = '';
            $color = "grey";
            $title = AmosCommunity::t('amoscommunity', 'Edit in progress');
            if ($model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED) {
                $color = "green";
                $title = AmosCommunity::t('amoscommunity', 'Validated');
            }
            $statusIcon = AmosIcons::show('check-all',
                    [
                    'class' => 'am-2 ',
                    'style' => 'color: '.$color,
                    'title' => $title
            ]);
            $icons      .= $statusIcon;
            $tooltip    .= Html::tag('div', $icons);
        }

        $tooltip .= '<p>'
            .AmosCommunity::t('amoscommunity', 'Access type: ')
            .AmosCommunity::t('amoscommunity', $model->getCommunityTypeName())
            .'</p></div></div></div>';

        return $tooltip;
    }

    /**
     * @return array|null
     */
    public function getConfirm()
    {
        $controller     = Yii::$app->controller;
        $isActionUpdate = ($controller->action->id == 'update');
        $confirm        = $isActionUpdate ?
            ['confirm' => AmosCommunity::t('amoscommunity','#confirm_exit_without_saving')] : null;

        return $confirm;
    }
}