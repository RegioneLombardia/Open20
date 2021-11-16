<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\widgets\mini;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class SubcommunitiesWidget
 * @package open20\amos\community\widgets
 */
class SubcommunitiesMiniWidget extends Widget
{
    /**
     * @var bool $isUpdate - true if in community edit form, false otherwise
     */
    public $isUpdate = false;
    
    /**
     * @var Community $model
     */
    public $model = null;
    
    /**
     * @var string $title The widget title
     */
    public $title = '';
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
    
        if (!$this->title) {
            $this->title = AmosCommunity::t('amoscommunity', 'Subcommunities');
        }
        
        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }
    }
    
    protected function throwErrorMessage($field)
    {
        return AmosCommunity::t('amoscommunity', 'Wrong widget configuration: missing field {field}', [
            'field' => $field
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->model->isNewRecord) {
            return $this->renderWidget();
        } else {
            return Html::tag('h2', $this->title) . Html::tag('div', AmosCommunity::t('amoscommunity', '#subcommunity_mini_widget_new_form'), ['class' => 'no-items']);
        }
    }
    
    /**
     * @return string
     */
    protected function renderWidget()
    {
        $model = $this->model;
        
        $enableWizard = Yii::$app->getModule('community')->enableWizard;
        $urlCreation = '/community/community/create?parentId=' . $model->id;
        $urlAssociate = '/community/community-wizard/new-subcommunity';
        
        $itemsMittente = [
            'logo_id' => [
                'label' => '',
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Community $model */
                    $url = (!is_null($model->communityLogo) ? $model->communityLogo->getUrl('square_small', false, true) : '/img/img_default.jpg');
                    return Html::a(Html::img($url, ['class' => 'gridview-image', 'alt' => $model->getAttributeLabel('communityLogo')]), ['/community/community/view', 'id' => $model->id], [
                        'alt' => $model->getAttributeLabel('communityLogo')
                    ]);
                }
            ],
            'Item' => [
                'value' => function ($model) {
                    return $this->render('_item_subcom', [
                        'model' => $model
                    ]);
                },
                'format' => 'raw'
            ],
        ];
        $subcommunitiesQuery = $model->getSubcommunities();
        if (!$model->isCommunityManager()) {
            $subcommunitiesQuery->joinWith('communityUsers')->andWhere([CommunityUserMm::tableName() . '.user_id' => Yii::$app->user->id]);
        }
        $widget = \open20\amos\core\forms\editors\m2mWidget\M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->getCommunityModel()->id,
            'titleWidget' => $this->title,
            'modelData' => $subcommunitiesQuery,
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'showPageSummary' => false,
            'createAssociaButtonsEnabled' => ($this->isUpdate && $model->isCommunityManager() && ($model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED)),
            'createNewTargetUrl' => $urlCreation,
            'disableCreateButton' => !$this->isUpdate || $enableWizard,
            'disableAssociaButton' => !$this->isUpdate || !$enableWizard,
            'createNewBtnLabel' => AmosCommunity::t('amoscommunity', '#subcommunity_mini_widget_btn_label'),
            'btnAssociaLabel' => AmosCommunity::t('amoscommunity', '#subcommunity_mini_widget_btn_label'),
            'btnAssociaClass' => 'btn btn-primary btn-m2m',
            'layoutMittente' => "{toolbarMittenteMini}\n{itemsMittente}\n{footerMittente}",
            'actionColumnsTemplate' => '{update}{delete}',
            'actionColumnsButtons' => [
                'delete' => function ($url, $model) {
                    $url = '/community/community/delete';
                    $community = $model;
                    $urlDelete = Yii::$app->urlManager->createUrl([
                        $url,
                        'id' => $community->id,
                        'communityParentId' => $community->parent_id
                    ]);
                    $loggedUser = Yii::$app->getUser();
                    if ($loggedUser->can('COMMUNITY_DELETE', ['model' => $this->model])) {
                        $btnDelete = Html::a(
                            AmosIcons::show('close', ['class' => 'btn btn-icon']),
                            $urlDelete,
                            ['title' => AmosCommunity::t('amoscommunity', 'Delete'),
                                'data-confirm' => Yii::t('amoscommunity', 'Are you sure to remove this Sub-community?'),
                                'class' => '',
                            ]
                        );
                        
                    } else {
                        $btnDelete = '';
                    }
                    return $btnDelete;
                },
                'update' => function ($url, $model) {
                    $url = '/community/community/update';
                    $community = $model;
                    $urlDelete = Yii::$app->urlManager->createUrl([
                        $url,
                        'id' => $community->id,
                    ]);
                    $loggedUser = Yii::$app->getUser();
                    if ($loggedUser->can('COMMUNITY_UPDATE', ['model' => $this->model])) {
                        $btnDelete = Html::a(
                            AmosIcons::show('edit', ['class' => 'btn btn-icon']),
                            $urlDelete,
                            ['title' => AmosCommunity::t('amoscommunity', 'Update'),
                                'class' => ''
                            ]
                        );
                    } else {
                        $btnDelete = '';
                    }
                    return $btnDelete;
                },
                'view' => function ($url, $model) {
                    $url = '/community/community/view';
                    $community = $model;
                    $urlDelete = Yii::$app->urlManager->createUrl([
                        $url,
                        'id' => $community->id,
                    ]);
                    $btnDelete = Html::a(
                        AmosIcons::show('file', ['class' => 'btn btn-icon']),
                        $urlDelete,
                        ['title' => AmosCommunity::t('amoscommunity', 'View'),
                            'class' => ''
                        ]
                    );
                    return $btnDelete;
                },
            ],
            'targetUrl' => $urlAssociate,
            'moduleClassName' => AmosCommunity::className(),
            'targetUrlController' => 'community',
            'postName' => 'Community',
            'postKey' => 'community',
            'permissions' => [
                'add' => 'COMMUNITY_CREATE',
                'manageAttributes' => $this->isUpdate
            ],
            'itemsMittente' => $itemsMittente,
        ]);
        
        return $widget;
    }
}
