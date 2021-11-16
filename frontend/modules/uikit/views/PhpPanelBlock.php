<?php

use app\modules\uikit\Uikit;

/**
 * @var $this
 * @var $data
 */

$id    = $data['id'];
if(!empty($data['class'][0])) {
    $class = $data['class'][0];
}
else {
    $class = '';
}

?>

<div id="<?php echo $id ?>" class="<?php echo $class ?>">
    <?= eval($data['content']); ?>
</div>