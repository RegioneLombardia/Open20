<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\socialwall\models\SocialwallPostsSearch $model
 */

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

$this->title = \open20\socialwall\Module::t('amoscore', 'Asset Social');
$this->params['titleSection'] = $this->title;
$this->params['hideCreate'] = true;
//$this->params['canCreate'] = true;
//$this->params['createNewBtnParams'] = [
//    'urlCreateNew' => '/socialwall/socialwall-posts/load-post-from-social',
//    'createNewBtnLabel' => \open20\socialwall\Module::t('socialwall', 'Carica'),
//];
$this->params['breadcrumbs'][] = $this->title;

$this->params['labelLinkAll'] = Yii::t('socialwall', 'Cerca contenuto social');
$this->params['urlLinkAll'] = '/socialwall/socialwall-posts/load-post-from-social';
$this->params['titleLinkAll'] = Yii::t('socialwall', 'Cerca contenuto social');

//foreach($dataProvider->getModels() as $model) {
//    $post = $model->attributes;
//    echo \open20\socialwall\widgets\SocialwallItemWidget::widget([
//        "post" => \open20\socialwall\utility\Social::formatPost(
//            $post['type'],
//            $post['profile_name'],
//            !empty($model->hasOneFile('profile_picture')->one())
//                    ? $model->getProfilePicture()->getWebUrl()
//                    : null,
//            $post['text'],
//            !empty($model->hasOneFile('post_image')->one())
//                ? $model->getPostImage()->getWebUrl()
//                : null,
//            $post['permalink'],
//            $post['publication_date'],
//            $post['profile_url'],
//            $post['media_type']
//        ),
//    ]);
//}
//\yii\helpers\VarDumper::dump($postsPreviewList, 5, true);die;

?>
<div class="socialwall-posts-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
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
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => "{preview} {update} {mydelete}",
                    'buttons' => [
                        'preview' => function ($url, $model) {
                            $recordId = $model->id;
                            return \yii\helpers\Html::a(
                                'Anteprima', "#modal-post-" . $recordId,
                                [
                                    'data-toggle' => "modal",
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => 'Anteprima',
                                ]
                            );
                        },
                        'mydelete' => function ($url, $model) {
                            return Html::a('<span class="mdi mdi-delete"></span>', ["/socialwall/socialwall-posts/delete", 'id' => $model->id],
                                [
                                    'title' => 'Elimina',
                                    'class' => 'btn btn-danger-inverse',
                                    'style' => 'color:#a61919; border-top:1px solid #ccc',
                                    'data-confirm' => 'Sei sicuro di voler eliminare questo post?',
                                    // 'data-toggle' => 'tooltip',
                                ]);
                        }
                    ]
                ],
            ],
        ],
        /*'listView' => [
        'itemView' => '_item',
        'masonry' => FALSE,

        // Se masonry settato a TRUE decommentare e settare i parametri seguenti 
        // nel CSS settare i seguenti parametri necessari al funzionamento tipo
        // .grid-sizer, .grid-item {width: 50&;}
        // Per i dettagli recarsi sul sito http://masonry.desandro.com                                     

        //'masonrySelector' => '.grid',
        //'masonryOptions' => [
        //    'itemSelector' => '.grid-item',
        //    'columnWidth' => '.grid-sizer',
        //    'percentPosition' => 'true',
        //    'gutter' => '20'
        //]
        ],
        'iconView' => [
        'itemView' => '_icon'
        ],
        'mapView' => [
        'itemView' => '_map',          
        'markerConfig' => [
        'lat' => 'domicilio_lat',
        'lng' => 'domicilio_lon',
        'icon' => 'iconMarker',
        ]
        ],
        'calendarView' => [
        'itemView' => '_calendar',
        'clientOptions' => [
        //'lang'=> 'de'
        ],
        'eventConfig' => [
        //'title' => 'titleEvent',
        //'start' => 'data_inizio',
        //'end' => 'data_fine',
        //'color' => 'colorEvent',
        //'url' => 'urlEvent'
        ],
        'array' => false,//se ci sono più eventi legati al singolo record
        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
        ]*/
    ]); ?>

</div>

<?php
$labelPreviewPost = \open20\socialwall\Module::t('socialwall', 'Anteprima post');

// LISTA ANTEPRIMA POST
foreach($dataProvider->getModels() as $model) {
    $post = $model->attributes;
    $recordId = $model->id;
    $previewPost = '<div class="it-grid-list-wrapper it-image-label-grid it-masonry">' . \open20\socialwall\widgets\SocialwallItemWidget::widget([
        "post" => \open20\socialwall\utility\Social::formatPost(
            $post['type'],
            $model->socialwallPostsProfile->profile_name,
            !empty($model->profile_picture)
                ? $model->profile_picture
                : null,
            $post['text'],
            !empty($model->hasOneFile('post_image')->one())
                ? $model->getPostImage()->getWebUrl()
                : null,
            $post['permalink'],
            $post['publication_date'],
            $model->socialwallPostsProfile->profile_url,
            $post['media_type']
        ),
        "postContainerId" => "post-preview-" . $model->id,
    ]) . '</div>';

    echo <<<HTML
<div class="modal fade" id="modal-post-{$recordId}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{$recordId}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="modalLabel{$recordId}">{$labelPreviewPost}</h5>
                {$previewPost}
            </div>
        </div>
    </div>
</div>
HTML;

    $this->registerJs(<<<JS
$("tr[data-key={$recordId}] td.w1").attr("data-toggle", "modal");
$("tr[data-key={$recordId}] td.w1").attr("data-target", "#modal-post-{$recordId}");
$($("#post-preview-{$recordId}").children()[0]).width("500px");
JS
    );
}

$this->registerCss(<<<CSS
td.w1 { cursor: pointer; }
CSS
);
?>