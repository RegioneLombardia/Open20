<?php
/**
 * View file for block: ModuleBackendBlock 
 *
 * File has been created with `block/create` command. 
 *
 *
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
        if ($canSeeBlock && !empty($n_class)) {
            $canSeeBlock = BaseUikitBlock::visivility($n_class);
        }
        break;
}

if ($canSeeBlock) {
    ?>


    <?= $this->extraValue('moduleContent'); ?>

<?php } ?>