<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "head"); ?>
</head>
<body>

<?php $this->beginBody() ?>
<section id="bk-page">

    <div class="container">

        <div class="page-content">

            <div class="page-header">
                <?php if (!is_null($this->title)): ?>
                    <h1 class="title"><?= \open20\amos\core\helpers\Html::encode($this->title) ?></h1>
                    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "textHelp"); ?>
                <?php endif; ?>
            </div>

            <?php if ($this instanceof \open20\amos\core\components\AmosView): ?>
                <?php $this->beginViewContent() ?>
            <?php endif; ?>
            <?= $content ?>
            <?php if ($this instanceof \open20\amos\core\components\AmosView): ?>
                <?php $this->endViewContent() ?>
            <?php endif; ?>
        </div>
    </div>

</section>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
