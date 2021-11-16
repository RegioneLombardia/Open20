<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets
 * @category   CategoryName
 */

namespace open20\amos\admin\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\notificationmanager\forms\NewsWidget;
use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\web\Application;

/**
 * Class UserCardWidget
 * @package open20\amos\admin\widgets
 */
class UserCardWidget extends Widget
{
    /**
     * @var UserProfile $model
     */
    public $model;
    public $onlyAvatar = true;
    public $absoluteUrl = false;
    public $avatarXS = false;
    public $enableLink = true;
    public $containerAdditionalClass = '';
    public $avatarDimension = 'square_small';

    /**
     * @var AmosAdmin $adminModule
     */
    private $adminModule = null;

    /**
     * @var bool $checkReadPermissionForUserLink If true check if the logged user can access the view of the content creator. If false the view link is always enabled.
     */
    public $checkReadPermissionForUserLink = true;

    /**
     * @var bool $hideCreatorNameSurname If true hide the name and surname of the user.
     */
    public $hideNameSurname = false;

    /**
     * @var string $customUserAvatarImageAlt Custom avatar image alt or link title
     */
    public $customUserAvatarImageAlt = '';

    /**
     * @var string $customUserAvatarUrl Custom creator avatar url.
     */
    public $customUserAvatarUrl = null;

    /**
     * @var bool $squareAvatar
     */
    public $squareAvatar = false;
    
    /**
     * @var array $creatorLinkOptions
     */
    public $creatorLinkOptions = [];

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(AmosAdmin::t('amosadmin', 'Missing model'));
        }

        $this->adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $html = '';
        $confirm = $this->getConfirm();

        if ($this->customUserAvatarUrl) {
            $url = $this->customUserAvatarUrl;
        } else if (isset(\Yii::$app->params['customUserCardWidgetAvatarUrl']) && \Yii::$app->params['customUserCardWidgetAvatarUrl']) {
            $url = \Yii::$app->params['customUserCardWidgetAvatarUrl'];
        } else if ($this->absoluteUrl) {
            $url = $this->model->getAvatarWebUrl($this->avatarDimension);
        } else {
            $url = $this->model->getAvatarUrl($this->avatarDimension);
        }

        $model = $this->model;
        $roundImage = Yii::$app->imageUtility->getRoundImage($model);

        if ($this->customUserAvatarImageAlt) {
            $alt = $this->customUserAvatarImageAlt;
        } else if (isset(\Yii::$app->params['customUserAvatarImageAlt']) && is_string(\Yii::$app->params['customUserAvatarImageAlt'])) {
            $alt = \Yii::$app->params['customUserAvatarImageAlt'];
        } else if ($this->hideNameSurname || (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true))) {
            $alt = '';
        } else {
            $alt = $model->getNomeCognome();
        }

        if ($this->absoluteUrl) {
            $class = $roundImage['class'];
            if ($class == 'full-width') {
                $style = "width: 100%; height: auto; margin-top:" . $roundImage['margin-top'] . "%;";
            } elseif ($class == 'full-height') {
                $style = "height: 100%; width: auto; margin-left: " . $roundImage['margin-left'] . "%;";
            } else {
                $style = " width: 100%; height: auto;";
            }

            $htmlOptions = [
                'style' => $style,
                'alt' => $alt
            ];
        } else {
            $htmlOptions = [
                'class' => $roundImage['class'],
                'style' => "margin-left: " . $roundImage['margin-left'] . "%; margin-top: " . $roundImage['margin-top'] . "%;",
                'alt' => $alt
            ];
        }

        $htmlTag = Html::img($url, $htmlOptions);
        $img = ($this->absoluteUrl) ? $htmlTag : Html::tag(
            'div', $htmlTag,
            [
                'class' => ((!$this->squareAvatar && !(isset(\Yii::$app->params['userCardWidgetSquareAvatar']) && (\Yii::$app->params['userCardWidgetSquareAvatar'] === true))) ? 'container-round-img-'
                    . (($this->avatarXS) ? 'xs' : 'sm')
                    . ' ' : '')
                    . $this->containerAdditionalClass
            ]
        );

        if ($this->onlyAvatar) {
            if ($this->creatorLinkEnabled()) {
                $link = null;
                if ($this->enableLink) {
                    $link = $this->getCreatorLink();
                    if ($this->absoluteUrl) {
                        $link = Yii::$app->getUrlManager()->createAbsoluteUrl($link);
                    }
                }
                if ($this->customUserAvatarImageAlt) {
                    $title = $this->customUserAvatarImageAlt;
                } else if (isset(\Yii::$app->params['customUserAvatarImageAlt']) && is_string(\Yii::$app->params['customUserAvatarImageAlt'])) {
                    $title = \Yii::$app->params['customUserAvatarImageAlt'];
                } else if ($this->hideNameSurname || (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true))) {
                    $title = '';
                } else {
                    $title = AmosAdmin::t('amosadmin', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->getNomeCognome()]);
                }

                $defaultLinkOptions = [
                    'title' => $title,
                    'data' => $confirm
                ];
                if (!empty($this->creatorLinkOptions)) {
                    $linkOptions = ArrayHelper::merge($defaultLinkOptions, $this->creatorLinkOptions);
                } else if (isset(\Yii::$app->params['customUserCardWidgetCreatorLinkOptions']) && is_array(\Yii::$app->params['customUserCardWidgetCreatorLinkOptions'])) {
                    $linkOptions = ArrayHelper::merge($defaultLinkOptions, \Yii::$app->params['customUserCardWidgetCreatorLinkOptions']);
                } else {
                    $linkOptions = $defaultLinkOptions;
                }
                
                $html .= Html::a(
                    $img,
                    $link,
                    $linkOptions
                );
            } else {
                $html .= $img;
            }
        } else {
            $modals = ConnectToUserWidget::widget([
                'model' => $this->model,
                'onlyModals' => true
            ]);
    
            $defaultLinkOptions = [
                'data' => [
                    'toggle' => 'tooltip',
                    'html' => true,
                    'placement' => 'right',
                    'delay' => ['show' => 100, 'hide' => 5000],
                    'trigger' => 'hover',
                    'template' => '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color:transparent"></div></div>',
                ],
                'title' => $this->getHtmlTooltip(),
                'style' => 'border-color:transparent;'
            ];
            if (!empty($this->creatorLinkOptions)) {
                $linkOptions = ArrayHelper::merge($defaultLinkOptions, $this->creatorLinkOptions);
            } else if (isset(\Yii::$app->params['customUserCardWidgetCreatorLinkOptions']) && is_array(\Yii::$app->params['customUserCardWidgetCreatorLinkOptions'])) {
                $linkOptions = ArrayHelper::merge($defaultLinkOptions, \Yii::$app->params['customUserCardWidgetCreatorLinkOptions']);
            } else {
                $linkOptions = $defaultLinkOptions;
            }

            $html = $modals . Html::a(
                    $img, null,
                    $linkOptions
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

        $nomeCognome = '';
        if ($this->customUserAvatarImageAlt) {
            $nomeCognome = $this->customUserAvatarImageAlt;
        } else if (isset(\Yii::$app->params['customUserAvatarImageAlt']) && is_string(\Yii::$app->params['customUserAvatarImageAlt'])) {
            $nomeCognome = \Yii::$app->params['customUserAvatarImageAlt'];
        } else if ($this->hideNameSurname || (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true))) {
            $nomeCognome = '';
        } else {
            if ($this->adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) {
                if ($this->adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) {
                    $nomeCognome .= $model->nome;
                }
                if ($this->adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)) {
                    $nomeCognome .= ' ' . $model->cognome;
                }
            }
        }

        $viewUrl = $this->getCreatorLink();
        $url = $model->getAvatarUrl(
            'original', [
                'class' => 'img-responsive'
            ]
        );

        $roundImage = Yii::$app->imageUtility->getRoundImage($model);
        $logoOptions = [
            'class' => $roundImage['class'],
            'style' => "margin-left: " . $roundImage['margin-left'] . "%; margin-top: " . $roundImage['margin-top'] . "%;",
        ];
        $logoLinkOptions = [];
        $options = [];
        if (strlen($nomeCognome)) {
            $logoOptions['alt'] = $nomeCognome;
            $logoLinkOptions['title'] = $nomeCognome;
            $options['title'] = $nomeCognome;
        }
        $logo = Html::img($url, $logoOptions);
        Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
        $tooltip = '<div class="icon-view"><div class="card-container col-xs-12 nop">' .
            ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/'.AmosAdmin::getModuleName().'/user-profile/update?id=" . $model->id,
                'disableDelete' => true
            ])
            . '<div class="icon-header grow-pict">
                         <div class="container-round-img">' .
            ($this->creatorLinkEnabled() ? Html::a($logo, $viewUrl, $logoLinkOptions) : $logo) .
            '</div>';

        if (Yii::$app->user->id != $model->user_id) {
            $tooltip .= ConnectToUserWidget::widget([
                'model' => $model,
                'divClassBtnContainer' => 'under-img',
                'onlyButton' => true
            ]);
        }

        $tooltip .= '</div><div class="icon-body">';
        $newsWidget = NewsWidget::widget([
            'model' => $model,
        ]);

        $tooltip .= $newsWidget . '<h3>' .
            ($this->creatorLinkEnabled() ? Html::a($nomeCognome, $viewUrl, $options) : $nomeCognome) .
            '</h3>';

        if ($model->validato_almeno_una_volta) {
            $icons = '';
            $color = "grey";
            $title = AmosAdmin::t('amosadmin', 'Profile Active');
            if ($model->status == UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) {
                $color = "green";
                $title = AmosAdmin::t('amosadmin', 'Profile Validated');
            }
            //TODO replace check-all with cockade
            $statusIcon = AmosIcons::show('check-all',
                [
                    'class' => 'am-2 ',
                    'style' => 'color: ' . $color,
                    'title' => $title
                ]);
            $icons .= $statusIcon;
            $facilitatorUserIds = Yii::$app->getAuthManager()->getUserIdsByRole("FACILITATOR");
            if (in_array($model->user_id, $facilitatorUserIds)) {
                //TODO replace account with man dressing tie and jacket
                $facilitatorIcon = AmosIcons::show('account',
                    [
                        'class' => 'am-2',
                        'style' => 'color: green',
                        'title' => AmosAdmin::t('amosadmin', 'Facilitator')
                    ]);
                $icons .= $facilitatorIcon;
            }
            $tooltip .= Html::tag('div', $icons);
        }

        if ((Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI')) && $model->user->email) {
            $tooltip .= '<p>' .
                AmosIcons::show('email')
                . '<span>' . $model->user->email . '</span>' .
                '</p>';
        }

        if (
            ($this->adminModule->confManager->isVisibleBox('box_prevalent_partnership',
                ConfigurationManager::VIEW_TYPE_VIEW)) &&
            ($this->adminModule->confManager->isVisibleField('prevalent_partnership_id',
                ConfigurationManager::VIEW_TYPE_VIEW))
        ) {
            $tooltip .= '<p>' . (!is_null($model->prevalentPartnership) ? $model->prevalentPartnership->name : AmosAdmin::t('amosadmin', 'Prevalent partnership not specified')) . '</p>';
        }

        $tooltip .= '</div></div></div>';

        return $tooltip;
    }

    /**
     * @return array|null
     */
    public function getConfirm()
    {
        $controller = Yii::$app->controller;
        $action = $controller->action->id;
        $isActionUpdate = ($action != 'view') && ($action == 'update' || $action == 'associa-m2m');
        $confirm = $isActionUpdate ? [
            'confirm' => BaseAmosModule::t('amoscore', '#confirm_exit_without_saving')
        ] : null;

        return $confirm;
    }

    /**
     * @return bool
     */
    public function creatorLinkEnabled()
    {
        if (!$this->enableLink || (isset(\Yii::$app->params['disableLinkContentCreator']) && (\Yii::$app->params['disableLinkContentCreator'] === true))) {
            return false;
        }
        if (\Yii::$app instanceof Application) {
            return (!$this->checkReadPermissionForUserLink || (\Yii::$app instanceof \yii\console\Application) || \Yii::$app->user->can('USERPROFILE_READ', $this->model));
        } else return true;
    }

    /**
     * @return string
     */
    public function getCreatorLink()
    {
        return ($this->creatorLinkEnabled() ? $this->model->getFullViewUrl() : null);
    }
}
