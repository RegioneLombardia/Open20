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
use open20\amos\tag\assets\ModuleTagFormAsset;
use open20\amos\tag\assets\ModuleTagFormFrontendAsset;
use open20\amos\tag\models\Tag;
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
 * @var bool $enabledAjax
 * @var bool $isFrontend
 * @var array $tags_selected
 * @var bool $hideHeader
 * @var string $id
 */

if(!$enabledAjax) {
    if ($isFrontend == false) {
        ModuleTagFormAsset::register($this);
    } else {
        ModuleTagFormFrontendAsset::register($this);
    }
}

$errorBlockMessage = AmosTag::t('amostag', 'Selezionare almeno 1 tag.');
$errorTooltipTitle = AmosTag::t('amostag', 'E\' necessario scegliere almeno 1 tag');

if(!$enabledAjax) {
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
}
?>

<div id="<?= $id ?>" class="<?= $containerClass ?> body">
    <div class="intestazione-box">
     <?php
	if(!empty($trees)) {
            if (!$hideHeader) {
                if (!$is_search) {
                    echo \open20\amos\core\helpers\Html::tag(
                        'h3',
                        AmosTag::tHtml('amostag', '#tags_title_tree'),
                        [
                            'class' => 'tags-title'
                        ]
                    );
                } else {
                        echo \open20\amos\core\helpers\Html::tag('label', AmosTag::tHtml('amostag', '#tags_label_tree'));
                }
            }
	}
    ?>
    </div>

    <?php
    if (
        isset($moduleCwh)
        && in_array(get_class($model), $moduleCwh->modelsEnabled)
        && $moduleCwh->behaviors
    ) {
        /** @var ActiveForm $form */
        echo $form->field($model, 'tagsMandatory')
            ->hiddenInput(['value' => ''])
            ->label(false);
        }
    ?>

    <div class="tag-plugin-warning-triangle"></div>
    <div class="tag-plugin-block-error"></div>

    <?php
    $data_trees = [];

    foreach ($trees as $tree) {
        //dati dell'albero
        $data_tree = \open20\amos\tag\widgets\TagWidget::getDataTree($tree, $limit_trees, $tags_selected);
        $data_trees[] = $data_tree;
        $id_tree = $data_tree['id'];
        $label_tree = $data_tree['label'];
        $limit_tree = $data_tree['limit'];
        $tags_selected_tree = $data_tree['tags_selected'];
        ?>


        <div class="amos-tag-tree-container row">
        <?php if (!$is_search) : ?>
            <div id="remaining_tag_tree_<?= $id_tree ?>" class="remaining_tag_tree col-xs-12">
            <?= AmosTag::tHtml('amostag', 'Scelte rimanenti:') ?>
                <span class="tree-remaining-tag-number"></span>
            </div>
            <?php endif; ?>

            <div id="tree_<?= $id_tree ?>" class="col-sm-12 col-md-8">
            <?php
            $model->setFocusRoot($id_tree);
            $optionsTree = [
                'id' => 'tree_obj_' . $id_tree,
                'disabled' => false,
                'name' => $model->formName() . '[tagValues][' . $id_tree . ']',
            ];

            if ($is_search) {
                if (empty($tags_selected_tree)) {
                    $optionsTree['value'] = '';
                }else{
                    $ids = \yii\helpers\ArrayHelper::map($tags_selected_tree, 'id', 'id');
                    $optionsTree['value'] = implode(',',$ids);
                }
            } else {
                if (!empty($tags_selected_tree)) {
                    $ids = ArrayHelper::map($tags_selected_tree, 'id', 'id');
                    $optionsTree['value'] = implode(',', $ids);
                } else {
                    $optionsTree['value'] = '';
                }
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
    <?php } ?>

</div>

<?php
$options = [
    'data_trees' => $data_trees,
    'selectSonsOnly' => $selectSonsOnly,
    'error_limit_tags' => AmosTag::t('amostag', 'Hai superato le scelte disponibili per questi descrittori.'),
    'tags_unlimited' => AmosTag::t('amostag', 'illimitate'),
    'no_tags_selected' => AmosTag::t('amostag', 'Nessun tag selezionato'),
    'icon_remove_tag' => AmosIcons::show('close', [], 'am'),
];


/** @var $this \yii\web\View */
if(!$enabledAjax) {
    $this->registerJs(
        "if (typeof TagFormVars === 'undefined' || TagFormVars === null) {
        TagFormVars = [];
    }
    TagFormVars.push(" . \yii\helpers\Json::htmlEncode($options) . ");
    ",
        \yii\web\View::POS_HEAD
    );
}
