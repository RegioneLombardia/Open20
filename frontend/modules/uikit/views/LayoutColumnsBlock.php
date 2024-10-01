<?php

/**
 * @var $this \luya\cms\base\PhpBlockView
 */
use app\modules\uikit\BaseUikitBlock;

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch ($visibility) {
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;
        break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest;
        $n_class = $this->varValue('addclass');
        if ($canSeeBlock && !is_null($n_class)) {
            $canSeeBlock = BaseUikitBlock::visivility($n_class);
        }
        break;
}
$n_colonne = $this->varValue('n_colonne', 1);
$col_add_class = (array) $data['addClass'];

$id1 = array_search(1, array_column($col_add_class, 'n_col'));
$id2 = array_search(2, array_column($col_add_class, 'n_col'));
$id3 = array_search(3, array_column($col_add_class, 'n_col'));
$id4 = array_search(4, array_column($col_add_class, 'n_col'));
$id5 = array_search(5, array_column($col_add_class, 'n_col'));
$border1 = array_search(1, array_column($col_add_class, 'n_col'));
$border2 = array_search(2, array_column($col_add_class, 'n_col'));
$border3 = array_search(3, array_column($col_add_class, 'n_col'));
$border4 = array_search(4, array_column($col_add_class, 'n_col'));
$border5 = array_search(5, array_column($col_add_class, 'n_col'));
?>



<?php if ($canSeeBlock): ?>
    <?php if (!$data['not_embed_container']): ?>
        <div class="uk-section">
            <div class="uk-container">
            <?php endif; ?>
            <div class="row<?= $this->cfgValue('rowDivClass', null, ' {{rowDivClass}}'); ?> <?= !is_null($col_add_affix) ? 'affix-parent' : ''; ?>">
                <?php
                if (empty($data['add_affix'])) {
                    $data['add_affix'] = '';
                }
                switch ($n_colonne):
                    case 8:?>

                        <div class="col-12<?= $id1 !== false ? ' ' . $col_add_class[$id1]['css'] : '' ?>  <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>

                        <?php break; ?>
                    <?php case 1: ?>

                        <div class="col-12 col-md-6 <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?>  <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>
                        <div class="col-12 col-md-6 <?= $id2 !== false && !empty($col_add_class[$id2]['css']) ? ' ' . $col_add_class[$id2]['css'] : '' ?>  <?= ($border2 !== false &&  !empty($col_add_class[$id2]['border']) ? $col_add_class[$id2]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-2' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col2"); ?>
                        </div>
                        <?php break; ?>
                    <?php case 2: ?>
                        <div class="col-12 col-md-4 <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?>  <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>
                        <div class="col-12 col-md-4 <?= $id2 !== false && !empty($col_add_class[$id2]['css']) ? ' ' . $col_add_class[$id2]['css'] : '' ?>  <?= ($border2 !== false &&  !empty($col_add_class[$id2]['border']) ? $col_add_class[$id2]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-2' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col2"); ?>
                        </div>
                        <div class="col-12 col-md-4 <?= $id3 !== false && !empty($col_add_class[$id3]['css']) ? ' ' . $col_add_class[$id3]['css'] : '' ?>  <?= ($border3 !== false &&  !empty($col_add_class[$id3]['border']) ? $col_add_class[$id3]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-3' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col3"); ?>
                        </div>
                        <?php break; ?>
                    <?php case 3: ?>
                        <div class="col-12 col-md-3 <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?> <?= (!empty($col_add_class[$border1]['border']) ? $col_add_class[$border1]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>
                        <div class="col-12 col-md-3 <?= $id2 !== false && !empty($col_add_class[$id2]['css']) ? ' ' . $col_add_class[$id2]['css'] : '' ?> <?= (!empty($col_add_class[$border2]['border']) ? $col_add_class[$border2]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-2' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col2"); ?>
                        </div>
                        <div class="col-12 col-md-3 <?= $id3 !== false && !empty($col_add_class[$id3]['css']) ? ' ' . $col_add_class[$id3]['css'] : '' ?> <?= (!empty($col_add_class[$border3]['border']) ? $col_add_class[$border3]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-3' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col3"); ?>
                        </div>
                        <div class="col-12 col-md-3 <?= $id4 !== false && !empty($col_add_class[$id4]['css']) ? ' ' . $col_add_class[$id4]['css'] : '' ?> <?= (!empty($col_add_class[$border4]['border']) ? $col_add_class[$border4]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-4' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col4"); ?>
                        </div>
                        <?php break; ?>
                    <?php case 4: ?>
                        <div class="col-12 col-md <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?> <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>
                        <div class="col-12 col-md <?= $id2 !== false && !empty($col_add_class[$id2]['css']) ? ' ' . $col_add_class[$id2]['css'] : '' ?> <?= ($border2 !== false &&  !empty($col_add_class[$id2]['border']) ? $col_add_class[$id2]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-2' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col2"); ?>
                        </div>
                        <div class="col-12 col-md <?= $id3 !== false && !empty($col_add_class[$id3]['css']) ? ' ' . $col_add_class[$id3]['css'] : '' ?> <?= ($border3 !== false &&  !empty($col_add_class[$id3]['border']) ? $col_add_class[$id3]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-3' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col3"); ?>
                        </div>
                        <div class="col-12 col-md <?= $id4 !== false && !empty($col_add_class[$id4]['css']) ? ' ' . $col_add_class[$id4]['css'] : '' ?> <?= ($border4 !== false &&  !empty($col_add_class[$id4]['border']) ? $col_add_class[$id4]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-4' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col4"); ?>
                        </div>
                        <div class="col-12 col-md <?= $id5 !== false && !empty($col_add_class[$id5]['css']) ? ' ' . $col_add_class[$id5]['css'] : '' ?> <?= ($border5 !== false &&  !empty($col_add_class[$id5]['border']) ? $col_add_class[$id5]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-5' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col5"); ?>
                        </div>
                        <?php break; ?>  
                    <?php case 5: ?>
                        <div class="col-12 col-md-8 <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?>  <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>
                        <div class="col-12 col-md-4 <?= $id2 !== false && !empty($col_add_class[$id2]['css']) ? ' ' . $col_add_class[$id2]['css'] : '' ?>  <?= ($border2 !== false &&  !empty($col_add_class[$id2]['border']) ? $col_add_class[$id2]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-2' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col2"); ?>
                        </div>
                        <?php break; ?>
                    <?php case 6: ?>
                        <div class="col-12 col-md-4 <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?>  <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>
                        <div class="col-12 col-md-8 <?= $id2 !== false && !empty($col_add_class[$id2]['css']) ? ' ' . $col_add_class[$id2]['css'] : '' ?>  <?= ($border2 !== false &&  !empty($col_add_class[$id2]['border']) ? $col_add_class[$id2]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-2' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col2"); ?>
                        </div>
                        <?php break; ?>
                    <?php case 7: ?>
                        <div class="col-12 col-md-2 <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?>  <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>
                        <div class="col-12 col-md-8 <?= $id2 !== false && !empty($col_add_class[$id2]['css']) ? ' ' . $col_add_class[$id2]['css'] : '' ?>  <?= ($border2 !== false &&  !empty($col_add_class[$id2]['border']) ? $col_add_class[$id2]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-2' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col2"); ?>
                        </div>
                        <div class="col-12 col-md-2 <?= $id3 !== false && !empty($col_add_class[$id3]['css']) ? ' ' . $col_add_class[$id3]['css'] : '' ?>  <?= ($border3 !== false &&  !empty($col_add_class[$id3]['border']) ? $col_add_class[$id3]['border'] : '') ?> <?= $data['add_affix'] == 'affix-column-3' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col3"); ?>
                        </div>
                        <?php break; ?>
                    <?php default: ?>
                        <div class="col-12 <?= $id1 !== false && !empty($col_add_class[$id1]['css']) ? ' ' . $col_add_class[$id1]['css'] : '' ?>  <?= ($border1 !== false &&  !empty($col_add_class[$id1]['border']) ? $col_add_class[$id1]['border'] : '') ?>  <?= $data['add_affix'] == 'affix-column-1' ? ' affix-top-cms-component' : '' ?>">
                            <?= $this->placeholderValue("col1"); ?>
                        </div>

                        <?php break; ?>

                <?php endswitch;
                ?>


                <?php $this->placeholderValue("col$i"); ?>
            </div>

            <?php if (!$data['not_embed_container']): ?>
            </div>
        </div>
    <?php endif; ?>
    <?php




 endif; 