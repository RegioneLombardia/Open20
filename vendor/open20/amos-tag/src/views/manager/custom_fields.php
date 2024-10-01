<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;

// parse parent key
if ($noNodesMessage) {
    $parentKey = '';
} elseif (empty($parentKey)) {
    $parent = $node->parents(1)->one();
    $parentKey = empty($parent) ? '' : Html::getAttributeValue($parent, $keyAttribute);
}
?>

<div class="row">
    <?php if($node->isRoot() || strpos($parentKey, "ROOT") !== false){ ?>
    <div class="col-sm-6">
        <?php echo $form->field($node, 'limit_selected_tag')->textInput(); ?>
    </div>
    <?php } ?>
    <div class="col-sm-6">
        <?php echo $form->field($node, 'codice')->textInput(); ?>
    </div>
    <div class="col-sm-12">
        <?php echo $form->field($node, 'descrizione')->textarea(['rows' => 6]); ?>
    </div> 
</div>
