<?php

use yii\helpers\Html;

?>
<?= $this->render("bi-breadcrumbs", [
    'currentAsset' => $currentAsset
]); ?>
<?php if (!is_null($this->title)) : ?>
    <div class="heading border-bottom mb-4 pb-2">
        <div class="d-flex align-items-center flex-wrap">
            <div class="mr-auto my-2">
                <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
            </div>
            <?php if ((!empty($this->params['enableLayoutList']) && $this->params['enableLayoutList'] == true)
                || (!empty(\Yii::$app->params['enableLayoutList']) &&  \Yii::$app->params['enableLayoutList'])) { ?>
                <?= $this->render("bi-additional-button", [
                    'currentAsset' => $currentAsset
                ]); ?>
                <?= $this->render("bi-new-button", [
                    'currentAsset' => $currentAsset
                ]); ?>

            <?php } ?>
        </div>
    </div>
<?php endif; ?>