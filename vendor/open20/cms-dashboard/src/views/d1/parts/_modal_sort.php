<?php

/**
 * @var $container
 * @var $modelContainer
 * @var $menuQuery
 */
use open20\cms\dashboard\Module;
use open20\cms\dashboard\utilities\Utility;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$textMenu = Module::txt('Sposta la voce di menu');
$menuIndx = clone $menuQuery;
$allTree = []; // Albero per js delle pagine, ogni chiave è una pagina, il valore un array con i discendenti
$ancestorsLevels = []; // Tiene traccia degli ultimi antenati incontrati

foreach ($menuIndx->all() as $k => $v) {
    if($v['index1'] == '0') $ancestorsLevels = []; // Reset array antenati ad ogni radice trovata

    // Calcolo livello nell'albero della pagina
    $pageLevel = Utility::calculateTreePageLevel($v);
    // Aggiorno l'array degli antenati
    list($ancestorsLevels, $originalAncestorPageId) = Utility::updateAncestorsLevel($ancestorsLevels, $v['nav0'], $pageLevel);
    // Aggiorno l'albero delle pagine
    $allTree = Utility::updateArrayTreePageParentChild($allTree, $ancestorsLevels, $originalAncestorPageId, $v['nav0'], $pageLevel);
}
$allTreeJson = json_encode($allTree);

$js = <<<JS
    var currentBtn = null;
    var titleNav = null;
    var treeAll = $allTreeJson;
    $(document).on('click', 'a[data-class="sort-page"]', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var title = $(this).attr('data-title');
        var modelId = $(this).attr('data-key');
        $('#newvers').attr('data-key',modelId);

        newSortRows(id, title, modelId);
    });
    
     $(document).on('click', '#newvers', function(e){
        e.preventDefault();
        submitNewSort();
    });       
        
    $('#modal-sort').removeAttr('tabindex');

    function newSortRows (id, title, modelId) {
        currentBtn = id;
        var oldTitle = '$textMenu';
        $("#vocmn").text(oldTitle + ' "' + title + '"');
        $("#modal-sort").modal("show");
        $('#menu-sort_id option').each(function(){
            $(this).removeAttr('disabled');
        });
         $('#menu-sort_inside_id option').each(function(){
            $(this).removeAttr('disabled');
        });
         
        // Disabilito la pagina che sto spostando
        $('#menu-sort_id option[value='+modelId+']').attr('disabled',true);
        $('#menu-sort_inside_id option[value='+modelId+']').attr('disabled',true);

        // Disabilito radice, antenati e discendenti
        $.each(treeAll[modelId][0], function (key, nav){
            $('#menu-sort_id option[value='+nav+']').attr('disabled',true);
            $('#menu-sort_inside_id option[value='+nav+']').attr('disabled',true);
        });
        // Disabilito i non-fratelli (escluse categorie usate sopra)
        $.each(treeAll[modelId][1], function (key,nav){
            $('#menu-sort_id option[value='+nav+']').attr('disabled',true);
        });
    }

    function submitNewSort() {
        $('#alert-by-sort').addClass('hidden');
        currentBtn = $('#newvers').attr('data-key');
        if (currentBtn) {
            var url = $('#createNewSort' + currentBtn).attr('href');
            var menusort = encodeURIComponent($('#menu-sort_id').val());
            var updown = encodeURIComponent($('#menu-updown').val());
            if(updown == 3){
                menusort = encodeURIComponent($('#menu-sort_inside_id').val());
            }
            if (menusort && menusort != currentBtn) {
                // console.log(window.location.href);

                url = url + '&up=' + updown + '&dest=' + menusort + '&url='+window.location.href;
                window.location.href = url;
            } else {
                $('#alert-by-sort').removeClass('hidden');
            }
        }
    }
    
    $('#menu-updown').on('change', function(){
       // console.log($(this).val());
        if($(this).val() == 3){
              $('#container-first-level').show();
              $('#container-all-level').hide();
        }else{
              $('#container-first-level').hide();
              $('#container-all-level').show();
        }
    });
    
JS;
$this->registerJs($js);

\yii\bootstrap\Modal::begin([
    'header' => '<h4 id="vocmn"></h4>',
    'options' => ['id' => 'modal-sort'],
        //'toggleButton' => ['label' => 'click me'],
]);
?>
<div id="alert-by-sort" class="alert alert-danger hidden" role="alert">
    <?= Module::txt('La destinazione non può essere uguale alla voce da spostare. Selezionare un\'altra voce di menu.') ?>
</div>
<div class="col-lg-12">

    <?=
    Select2::widget([
        'model' => $modelContainer,
        'attribute' => 'updown',
        'hideSearch' => true,
        'data' => [0 => Module::txt('Prima della voce'), 1 => Module::txt('Dopo la voce'), 3 => Module::txt('Dentro la voce')],
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
    ?>
</div>
<br><br>

<div id="container-all-level">
    <div class="col-lg-12">
        <?=
        Select2::widget([
            'model' => $modelContainer,
            'attribute' => 'item',
            'hideSearch' => true,
            'data' => ArrayHelper::map($menuQuery->all(), 'nav0', 'title'),
            'options' => ['multiple' => false, 'class' => 'form-control', 'id' => 'menu-sort_id'],
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
        ?>
    </div>
</div>

<div style="display:none" id="container-first-level">
    <div class="col-lg-12">

        <?php
/*
        $navContainer = \luya\cms\models\NavContainer::find()->andWhere(['alias' => $container])->one();
        $queryMenuFirstLevel = \luya\cms\models\NavItem::find()
                ->innerJoin('cms_nav', 'cms_nav.id = cms_nav_item.nav_id')
                ->andWhere(['cms_nav.parent_nav_id' => 0])
                ->andWhere(['cms_nav.is_deleted' => 0])
                ->andWhere(['cms_nav.is_draft' => 0])
                ->andWhere(['cms_nav.nav_container_id' => $navContainer->id]);
*/
        ?>
        <?=
        Select2::widget([
            'name' => 'item_inside',
            'data' => ArrayHelper::map($menuQuery->all(), 'nav0', 'title'),
            'options' => ['multiple' => false, 'class' => 'form-control', 'id' => 'menu-sort_inside_id'],
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
        ?>
    </div>
</div>


<br><br>
<div class="col-lg-12">
    <button type="submit" id="newvers" data-modal="true"
            class="btn btn-navigation-primary pull-right"><?= Module::txt('Conferma') ?></button>
</div>
<?php
\yii\bootstrap\Modal::end();
?>

