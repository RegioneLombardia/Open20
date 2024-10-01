<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

$dataProvider->setSort([
    'attributes' => [
        'publication_date' => [
            'asc' => ['socialwall_posts.publication_date' => SORT_ASC],
            'desc' => ['socialwall_posts.publication_date' => SORT_DESC],
        ],
        'type' => [
            'asc' => ['socialwall_posts.type' => SORT_ASC],
            'desc' => ['socialwall_posts.type' => SORT_DESC],
        ],
    ],
    'defaultOrder' => [
        'publication_date' => SORT_DESC
    ]
]);

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\socialwall\models\SocialwallPostsSearch $model
 */

$this->title = \open20\socialwall\Module::t('amoscore', 'Asset Social');
$this->params['titleSection'] = $this->title;
$this->params['canCreate'] = true;
$this->params['createNewBtnParams'] = [
    'urlCreateNew' => '/socialwall/socialwall-posts/load-post-from-social',
    'createNewBtnLabel' => \open20\socialwall\Module::t('socialwall', 'Carica'),
];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="socialwall-select-posts-grid" class="socialwall-posts-index">
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
            'url' => '?currentView=grid'
        ],
        'gridView' => [
            'columns' => [
                [
                    'attribute' => 'publication_date',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate(
                            strtotime($model->publication_date),
                            isset(
                                \Yii::$app->components['formatter']['datetimeFormat']) ?
                                \Yii::$app->components['formatter']['datetimeFormat'] :
                                'd-m-Y H:i:s');
                    }
                ],
                [
                    'attribute' => 'text',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return \yii\helpers\StringHelper::truncate(str_replace("&amp;", "&", strip_tags($model->text)), 200, '...');
                    },
                ],
                [
                    'attribute' => 'type',
                    'format' => 'raw',
                    'label' => $model->attributeLabels()['type'],
                    'value' => function ($model) {
                        switch ($model->type) {
                            case \open20\socialwall\utility\Social::FACEBOOK:
                                return '<span class="icon icon-lg icon-white mdi mdi-facebook" style="font-size: 40px;"></span>';
                            case \open20\socialwall\utility\Social::INSTAGRAM:
                                return '<span class="icon icon-lg icon-white mdi mdi-instagram" style="font-size: 40px;"></span>';
                            case \open20\socialwall\utility\Social::TWITTER:
                                return '<span class="icon icon-lg icon-white mdi mdi-twitter" style="font-size: 40px;"></span>';
                        }
                    },
                ],
                [
                    'format' => 'raw',
                    'label' => $model->attributeLabels()['post_image'],
                    'value' => function ($model) {
                        if(!empty($model->hasOneFile('post_image')->one())) {
                            return "<img src=\"{$model->getPostImage()->getWebUrl('square_small')}\" width='100px' />";
                        } else {
                            return "";
                        }
                    },
                ],
                [
                    'format' => 'raw',
                    'label' => "",
                    'value' => function ($model) {
                        return Html::button(
                            '+',
                            [
                                'class' => 'btn btn-primary',
                                'onClick' => "window.selectPostAction({$model->id})",
                                'data-button-model-id' => $model->id,
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<script type="text/javascript">
$("#socialwall-select-posts-grid .pagination a").click(function (e) {
  e.preventDefault();
  window.loadPageInSelectPostsModal(e.target.href);
});
$("#socialwall-select-posts-grid .thead_static a").click(function (e) {
  e.preventDefault();
  window.loadPageInSelectPostsModal(e.target.href);
});
</script>