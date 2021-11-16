<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\widgets;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class SubcommunitiesWidget
 * @package open20\amos\community\widgets
 */
class SubcommunitiesWidget extends Widget
{
    /**
     * @var bool $isUpdate  - true if in community edit form, false otherwise
     */
    public $isUpdate = false;

    /**
     * @var Community $model
     */
    public $model = null;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
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
        $model = $this->model;

        $enableWizard = Yii::$app->getModule('community')->enableWizard;
        $urlCreation = '/community/community/create?parentId='. $model->id;
        $urlAssociate = '/community/community-wizard/new-subcommunity';

        $itemsMittente = [
            'logo_id' => [
                'label' => AmosCommunity::t('amoscommunity', 'Logo'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Community $model */
                    $url = (!is_null($model->communityLogo) ? $model->communityLogo->getUrl('square_small', false, true) : '/img/img_default.jpg');
                    return Html::img($url, ['class' => 'gridview-image', 'alt' => $model->getAttributeLabel('communityLogo')]);
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($model) {
                    /** @var Community $model */
                    return Html::a($model->name, ['/community/community/view', 'id' => $model->id], [
                        'title' => AmosCommunity::t('amoscommunity', 'Apri il profilo della community {community_name}', ['community_name' => $model->name])
                    ]);
                }
            ],
            'communityType' => [
                'attribute' => 'communityType',
                'format' => 'html',
                'value' => function($model){
                    /** @var Community $model */
                    if(!is_null($model->community_type_id)) {
                        return AmosCommunity::t('amoscommunity', $model->communityType->name);
                    }else{
                        return '-';
                    }
                }
            ],
            'status' => [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->hasWorkflowStatus() ? AmosCommunity::t('amoscommunity', $model->getWorkflowStatus()->getLabel()) : '-';
                }
            ]
        ];
        $subcommunitiesQuery = $model->getSubcommunities();
        if(!$model->isCommunityManager()){
            $subcommunitiesQuery->joinWith('communityUsers')->andWhere([CommunityUserMm::tableName().'.user_id' => Yii::$app->user->id]);
        }
        $widget = M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->getCommunityModel()->id,
            'modelData' => $subcommunitiesQuery,
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'createAssociaButtonsEnabled' => $this->isUpdate && $model->isCommunityManager(),
            'createNewTargetUrl' => $urlCreation,
            'disableCreateButton' => !$this->isUpdate || $enableWizard,
            'disableAssociaButton' => !$this->isUpdate || !$enableWizard ,
            'createNewBtnLabel' => AmosCommunity::t('amoscommunity', '#new_subcommunity'),
            'btnAssociaLabel' => AmosCommunity::t('amoscommunity', '#new_subcommunity'),
            'actionColumnsTemplate' =>  !$this->isUpdate ? '' : '{view}{update}',
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
            'actionColumnsButtons' => [
                'view',
                'update'
            ],
        ]);

        return $widget;
    }
}
