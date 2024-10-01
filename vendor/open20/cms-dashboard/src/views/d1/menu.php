<?php

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use open20\amos\core\views\AmosGridView;
use open20\cms\dashboard\utilities\Utility;
use open20\cms\dashboard\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use open20\cms\dashboard\assets\CmsDashboardAsset;

$this->title = Module::txt('Menu');

$this->params['breadcrumbs'][] = $this->title;
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$this->registerJs('
        $("#select-container").change(function() {
            window.location.href = "/' . Module::getModuleName() . '/d1/menu?container="+$(this).val();
        });
        $(".btn-group.btn.show-hide-element.btn-secondary").hide();
        ', \yii\web\View::POS_READY);
$canPublish = \Yii::$app->user->can('CMS_PUBLISH_PAGE');
$textMenu = Module::txt('Sposta la voce di menu');
?>
<script type="text/javascript">
    var currentBtn = null;
    var titleNav = null;

    function newSortRows(id, title) {
        currentBtn = id;
        event.preventDefault();
        var oldTitle = '<?= $textMenu ?>';
        $("#vocmn").text(oldTitle + ' "' + title + '"');
        $("#modal-text").modal("show");
    }

    function submitNewSort() {
        $('#alert-by-sort').addClass('hidden');
        if (currentBtn) {
            var url = $('#createNewSort' + currentBtn).attr('href');
            var menusort = encodeURIComponent($('#menu-sort_id').val());

            var updown = encodeURIComponent($('#menu-updown').val());
            if (menusort && menusort != currentBtn) {
                url = url + '&up=' + updown + '&dest=' + menusort;
                window.location.href = url;
            } else {
                $('#alert-by-sort').removeClass('hidden');
            }
        }
    }
</script>

<?php /*
  $this->render('_search', [
  'model' => $modelContainer,
  'originAction' => Yii::$app->controller->action->id
  ]); */
?>
<div class="index-change-container">
    <?php
    \yii\bootstrap\Modal::begin([
        'header' => '<h4 id="vocmn"></h4>',
        'options' => ['id' => 'modal-text'],
            //'toggleButton' => ['label' => 'click me'],
    ]);
    echo '<div id="alert-by-sort" class="alert alert-danger hidden" role="alert">' .
    Module::txt('La destinazione non può essere uguale alla voce da spostare. Selezionare un\'altra voce di menu.')
    . '</div>';
    echo '<div class="col-lg-12">';
    /*   echo Html::activeDropDownList($modelContainer, 'updown',
      [0 => Module::txt('Prima della voce'), 1 => Module::txt('Dopo la voce')],
      ['id' => 'menu-updown', 'class' => 'form-control']) . '<br>';
      //    echo '</div><div class="col-lg-6">';
      /*   echo Html::activeDropDownList($modelContainer, 'item',
      ArrayHelper::map($menuQuery->all(), 'nav0', 'title'),
      ['id' => 'menu-sort_id',
      'class' => 'form-control']) . '<br>'; */
    echo Select2::widget([
        'model' => $modelContainer,
        'attribute' => 'updown',
        'hideSearch' => true,
        'data' => [0 => Module::txt('Prima della voce'), 1 => Module::txt('Dopo la voce')],
        'options' => ['multiple' => false, 'id' => 'menu-updown', 'class' => 'form-control'],
        'pluginOptions' => [
            'allowClear' => true,
            'templateResult' => new \yii\web\JsExpression("function formatState (state) {                                
                                tree = state.text;    
                                    newTree = tree.replace(/\ \ /g, '&nbsp;&nbsp;&nbsp;');
                                    if(newTree == tree){
                                        return tree;
                                    } else {
                                    newTree = '<span class=\" d-flex \">' + tree.replace(/\ \ /g, '&nbsp;&nbsp;&nbsp;@') + '</span>';     
                                    var lastIndex = newTree.lastIndexOf('@');
                                    newTree = newTree.slice(0, lastIndex) + '<span class=\"mdi mdi-subdirectory-arrow-right m-r-10 \"></span>' + newTree.slice(lastIndex + 1);                           
                                    newTree = newTree.replace(/@/g, '&nbsp;&nbsp;&nbsp;')
                                    newTree2 = $(newTree);
                                    return newTree2;
                                }
                              }
                            "),
        ],
    ]);
    echo '</div>';
    echo '<br><br>';
    echo '<div class="col-lg-12">';
    echo Select2::widget([
        'model' => $modelContainer,
        'attribute' => 'item',
        'hideSearch' => true,
        'data' => ArrayHelper::map($menuQuery->all(), 'nav0', 'title'),
        'options' => ['multiple' => false, 'class' => 'form-control', 'id' => 'menu-sort_id'],
        'pluginOptions' => [
            'allowClear' => true,
            'templateResult' => new \yii\web\JsExpression("function formatState (state) {                                
                                tree = state.text;                              
                                newTree = $('<span class=\" d-flex \">' + tree.replace(/\ \ /g, '&nbsp;&nbsp;&nbsp;<span class=\"mdi mdi-subdirectory-arrow-right m-r-10 \"></span>') + '</span>');                               
                                return newTree;
                              }
                            "),
        ],
    ]);
    echo '</div>';
    echo '<br><br>';
    echo '<div class="col-lg-12">';
    echo '<button type="submit" id="newvers" data-modal="true" onclick="submitNewSort()" class="btn btn-navigation-primary pull-right">' . Module::txt('Conferma') . '</button>';
    echo '</div>';

    \yii\bootstrap\Modal::end();
    ?>
    <div class="row">        
        <div class="col-lg-1">
            <label for="select-container"><?= Module::txt('Mostra') ?></label>
        </div>
        <div class="col-lg-11">
            <?=
            Select2::widget([
                'model' => $modelContainer,
                'attribute' => 'container',
                'data' => ArrayHelper::map(Utility::getAllCmsContainer(), 'alias', 'name'),
                'options' => ['placeholder' => Module::txt('Seleziona ...'), 'id' => 'select-container'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>
</div>
<?php
echo AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'showPageSummary' => false,
    // 'rowOptions' => function ($model) {
    //     if ($model['offline']) {
    //         return ['class' => 'danger'];
    //     }
    //     return '';
    // },
    'columns' => [
        [
            'width' => '4%;',
            'class' => 'kartik\grid\ExpandRowColumn',
            'columnKey' => 'main_node',
            'value' => function ($model, $key, $index) use ($container){
                $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);
                if ($children->count() > 0) {
                    return AmosGridView::ROW_COLLAPSED;
//                                   return AmosGridView::ROW_EXPANDED;
                }
                return '';
            },
            'extraData' => [
                'container' => $container,
                'canPublish' => $canPublish,
                'lvl' => 1
            ],
            'detailUrl' => '/dashboards/d1/luya-admin-menu?view=_submenu',
            'disabled' => function ($model, $key, $index, $column) use ($container) {
                $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);
                if ($children->count() > 0) {
                    return false;
                }
                return true;
            },
            'expandOneOnly' => false,
            'detailRowCssClass' => '',
        ],
        //   ['class' => 'yii\grid\SerialColumn'],
        [
            'width' => '6%;',
            'attribute' => 'offline',
            'label' => Module::txt('Stato'),
            'value' => function ($model) {
                $statusOnline = Module::txt('Pubblicato');
                $statusOffline = Module::txt('Bozza');
                if ($model['offline']) {
                    return ('<span class="mdi mdi-cloud-off-outline mdi-24px text-muted" title="' . $statusOffline . '" data-toggle="tooltip"></span>');
                }
                return ('<span class="mdi mdi-cloud mdi-24px text-primary" title="' . $statusOnline . '" data-toggle="tooltip"></span>');
            },
            'format' => 'raw',
        ],
        [
            'width' => '50%;',
            'attribute' => 'title',
            'label' => Module::txt('Pagina'),
            'value' => function ($model) {
                return '<strong>' . $model['title'] . '</strong>';
            },
            'format' => 'html',
        ],
        [
            'width' => '15%;',
            'attribute' => 'hidden',
            'label' => Module::txt('Visibile nel menu'),
            'value' => function ($model) {
                return ($model['hidden'] ? Module::txt('No') : Module::txt('Si'));
            },
        ],
        [
            'width' => '6%;',
            'attribute' => 'ordina',
            'label' => 'Ordina',
            'value' => function ($model) {
                return Html::a('<span class="mdi mdi-arrow-split-horizontal"></span>',
                        ['/' . Module::getModuleName() . '/d1/new-sort', 'id' => $model['nav_id'], 'lvl' => 0],
                        ['class' => 'btn btn-default',
                            'onclick' => 'newSortRows(' . $model['nav_id'] . ', "' . $model['title'] . '")',
                            'id' => 'createNewSort' . $model['nav_id'],
                ]);
            },
            'format' => 'raw'
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{preview}{visibility}',
            'buttons' => [
                'preview' => function ($url, $model) {
                    $url = \yii\helpers\Url::current();
                    $link = Utility::getLinkTarget($model);
                    $onlineVersion = Utility::getOnlineVersion($model['id0']);
                    $target = ['/cms-page-preview', 'itemId' => $model['id0'], 'version' => (!empty($onlineVersion['id']) ? $onlineVersion['id'] : 1)];
                    if (!empty($link)) {
                        $target = $link;
                    }
                    return Html::a('<span class="mdi mdi-eye"></span>', $target
                            , [
                        'title' => Module::txt('Visualizza l\'anteprima'),
                        'class' => 'btn btn-tool-secondary',
                        'target' => '_blank',
                    ]);
                },
                'visibility' => function ($url, $model) {
                    $url = \yii\helpers\Url::current();
                    if ($model['hidden']) {
                        return Html::a('<span class="mdi mdi-eye"></span>', ['/' . Module::getModuleName() . '/d1/set-visibility-menu', 'id' => $model['nav_id'], 'value' => 0]
                                        , [
                                    'title' => Module::txt('Rendi visibile nel menu'),
                                    'class' => 'btn btn-tool-secondary',
                        ]);
                    }
                    return Html::a('<span class="mdi mdi-eye"></span>', ['/' . Module::getModuleName() . '/d1/set-visibility-menu', 'id' => $model['nav_id'], 'value' => 1]
                            , [
                        'title' => Module::txt('Nascondi nel menu'),
                        'class' => 'btn btn-tool-secondary',
                    ]);
                },
            ],
        ],
    ]
]);
