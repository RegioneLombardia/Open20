<?php

use open20\amos\cwh\AmosCwh;

/** @var \open20\amos\core\record\AmosRecordAudit $model */
/** @var \open20\amos\core\forms\ActiveForm $form */
/** @var string $name */

\open20\amos\cwh\assets\ModuleAttivitaAsset::register($this);

$data_trees = [];
?>
    <div id="amos-cwh-tag">
        <div id="vista-semplice">
            <section class="">
                <div class="amos-tag-tree-container amos-cwh-tag-tree-container row">
                    <?php if (!count($contentsTreesSimple)): ?>
                        <div class="alert fade in kv-alert alert-warning">
                            <?= AmosCwh::t('amoscwh', 'Non sono stati inseriti tag per questo plugin.') ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    foreach ($contentsTreesSimple as $id_tree => $label_tree) :
                        $limit_tree = (array_key_exists("tree_" . $id_tree, $limit_trees) && $limit_trees["tree_" . $id_tree] ? $limit_trees["tree_" . $id_tree] : false);
                        $tags_selected_tree = (
                        array_key_exists('simple-choice', $tags_selected)
                        && $tags_selected['simple-choice']
                        && array_key_exists("tree_" . $id_tree, $tags_selected['simple-choice'])
                        && $tags_selected['simple-choice']["tree_" . $id_tree]
                            ? $tags_selected['simple-choice']["tree_" . $id_tree]
                            : []
                        );

                        // verifica se esiste già il contenitore
                        if (!array_key_exists("simple-choice", $data_trees)) {
                            $data_trees["simple-choice"] = [];
                        }

                        // inserisce i dati nell'array per gli eventi js
                        $data_trees["simple-choice"][] = [
                            'id' => $id_tree,
                            'limit' => $limit_tree,
                            'tags_selected' => $tags_selected_tree,
                        ];
                        ?>
                        <div id="tree_simple_<?= $id_tree ?>" class="col-xs-12 col-md-8 m-b-5">
                            <?=
                            \kartik\tree\TreeViewInput::widget([
                                'query' => open20\amos\tag\models\Tag::find()
                                    ->andWhere(['root' => $id_tree])
                                    ->addOrderBy('root, lft'),
                                'headingOptions' => ['label' => $label_tree],
                                'rootOptions' => [
                                    'label' => '<i class="dash dash-bullhorn text-success"></i>', 'class' => 'text-success hidden'
                                ],
                                'fontAwesome' => false,
                                'asDropdown' => false,
                                'multiple' => true,
                                'cascadeSelectChildren' => ($limit_tree ? false : true),
                                'value' => $model->getInterestTagValues(null, 'simple-choice', $id_tree),
                                'name' => $model->formName() . '[' . $name . '][simple-choice][' . $id_tree . ']',
                                'options' => [
                                    'disabled' => false,
                                    'name' => $model->formName() . '[' . $name . '][simple-choice][' . $id_tree . ']',
                                    'data' => [
                                        'tree-attach' => $id_tree
                                    ]
                                ],
                                'id' => 'simple-choice-treeview-' . $id_tree,
                            ]);
                            ?>
                        </div>

                        <div class="preview_tag_tree col-md-4 col-xs-12 m-b-30">
                            <div id="remaining_tag_tree_simple-choice_<?= $id_tree ?>" class="remaining_tag_tree">
                                <?= AmosCwh::t('amoscwh', 'Scelte rimanenti:') ?>
                                <span class="tree-remaining-tag-number"></span>
                            </div>
                            <div id="preview_tag_tree_simple-choice_<?= $id_tree ?>" class="preview_tag_tree"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
<?php
$options = [
    'data_trees' => $data_trees,
    'error_limit_tags' => AmosCwh::t('amoscwh', 'Hai superato le scelte disponibili per questi descrittori.'),
    'tags_unlimited' => AmosCwh::t('amoscwh', 'illimitate'),
    'no_tags_selected' => AmosCwh::t('amoscwh', 'Nessun tag selezionato'),
    'icon_remove_tag' => \open20\amos\core\icons\AmosIcons::show('close', [], 'am'),
];

$this->registerJs(
    'var AreeInteresseVars = ' . \yii\helpers\Json::htmlEncode($options) . ';',
    \yii\web\View::POS_HEAD
);
