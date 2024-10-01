<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\widgets\views
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\Tag;
use open20\amos\tag\assets\ModuleTagFormAsset;
use kartik\tree\TreeViewInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

/**
 *
 * @var \open20\amos\core\record\AmosRecordAudit $model
 * @var \open20\amos\core\forms\ActiveForm $form
 * @var string $name
 * @var array $trees
 * @var array $limit_trees
 * @var bool $is_search
 * @var array $tags_selected
 * @var bool $hideHeader
 * @var string $id
 */

ModuleTagFormAsset::register($this);

$moduleCwh  = \Yii::$app->getModule('cwh');
$errorBlockMessage = AmosTag::t('amostag', 'Selezionare almeno 1 tag.');
$errorTooltipTitle = AmosTag::t('amostag', 'E\' necessario scegliere almeno 1 tag');

$this->registerJS(<<<JS
    var errorBlockMessage = "$errorBlockMessage";
    var errorTooltipTitle = "$errorTooltipTitle";
    
    // This will collapse all tag trees when page is ready
    $(document).ready(function($) {
        if ($("input[id^=\"tree_obj_\"]").length) {
            $("input[id^=\"tree_obj_\"]").treeview("collapseAll");
        }
    });
    
    // Show an error message at the bottom of the section title if in the page
    // there is a "*-regola_pubblicazione"
    // class with an error in it.
    if ($('div[class*="-regola_pubblicazione"]').hasClass('has-error')) {
        $(".tag-plugin-block-error").append('<div style="margin-bottom: 10px;"><span class="tooltip-error-field"><span class="help-block help-block-error">'+errorBlockMessage+'</span></span></div>');
    
        $(".tag-plugin-warning-triangle").append('<span class="tooltip-error-field"> <span title="" data-toggle="tooltip" data-placement="top" data-original-title="'+errorTooltipTitle+'"><span class="am am-alert-triangle" style="color: #a02622"> </span> </span> </span>');
    
        $('div[class*="-regola_pubblicazione"]').hide();
    }

   if ($('#amos-tag div').hasClass('has-error')) {
        $(".tag-plugin-block-error").append('<div style="margin-bottom: 10px;"><span class="tooltip-error-field"><span class="help-block help-block-error">'+errorBlockMessage+'</span></span></div>');
    }
JS
);
?>

<div id="<?= $id ?>" class="<?= $containerClass ?> body">
    <div class="intestazione-box">
        <?php
        if (!$hideHeader):
            if (!$is_search) {
                echo Html::tag(
                    'h3',
                    AmosTag::tHtml('amostag', '#tags_title_tree'),
                    ['class' => 'tags-title']
                );
            } else {
                echo Html::tag('label', AmosTag::tHtml('amostag', '#tags_label_tree'));
            }
            ?>
        <?php endif; ?>
    </div>
    <div class="tag-plugin-warning-triangle"></div>
    <div class="tag-plugin-block-error"></div>

    <?php
    $data_trees = [];
    foreach ($trees as $tree) :
        //dati dell'albero
        $id_tree = $tree['id'];
        $label_tree = $tree['nome'];
        $limit_tree = ((array_key_exists("tree_" . $id_tree,
            $limit_trees) && $limit_trees["tree_" . $id_tree]) ? $limit_trees["tree_" . $id_tree] : false);
        $tags_selected_tree = [];
        if (
            array_key_exists('tree_' . $id_tree, $tags_selected)
            && !empty($tags_selected['tree_' . $id_tree])
        ) {
            $tags_selected_tree = $tags_selected['tree_' . $id_tree];
        }
        //inserisce i dati nell'array per gli eventi js
        $data_trees[] = [
            'id' => $id_tree,
            'limit' => $limit_tree,
            'tags_selected' => $tags_selected_tree
        ];
        ?>

        <div class="amos-tag-tree-container row">
            <div id="tree_<?= $id_tree ?>" class="col-sm-12 col-md-8">
            <?php
            $optionsTree = [
                'id' => 'tree_obj_' . $id_tree,
                'disabled' => false,
                'name' => $model->formName() . '[tagValues][' . $id_tree . ']',
            ];
            if (!empty($tags_selected_tree)) {
                $ids = ArrayHelper::map($tags_selected_tree, 'id', 'id');
                $optionsTree['value'] = implode(',',$ids);
            } else {
                $optionsTree['value'] = '';
            }

            echo $form->field($model, $name)->widget(TreeViewInput::class, [
                'query' => Tag::find()
                    ->andWhere(['root' => $id_tree])
                    ->addOrderBy('root, lft'),
                'headingOptions' => ['label' => $label_tree],
                'rootOptions' => [
                    'label' => '<i class="fa fa-tree text-success"></i>',
                    'class' => 'text-success hidden'
                ],
                'fontAwesome' => false,
                'asDropdown' => false,
                'multiple' => true,
                'cascadeSelectChildren' => ($limit_tree ? false : true),
                'options' => $optionsTree,
            ])
            ->label(false);
            ?>
            </div>

            <div id="preview_tag_tree_<?= $id_tree ?>" class="preview_tag_tree col-sm-12 col-md-4"></div>
            <div class="clearfix"></div>
        </div>
    <?php endforeach; ?>
</div>

<?php
$options = [
    'data_trees' => $data_trees,
    'error_limit_tags' => AmosTag::t('amostag', 'Hai superato le scelte disponibili per questi descrittori.'),
    'tags_unlimited' => AmosTag::t('amostag', 'illimitate'),
    'no_tags_selected' => AmosTag::t('amostag', 'Nessun tag selezionato'),
    'icon_remove_tag' => AmosIcons::show('close', [], 'am'),
];
$this->registerJs(
    "if (typeof TagFormVars === 'undefined' || TagFormVars === null) {
        TagFormVars = [];
    }
    TagFormVars.push(" . Json::htmlEncode($options) . ");",
    View::POS_HEAD
);
?>