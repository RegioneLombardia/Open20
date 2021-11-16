<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\layouts
 * @category   CategoryName
 */

/**@var $this \yii\web\View */
/**@var $content string */

use open20\amos\core\helpers\Html;
use yii\widgets\Breadcrumbs;

AmosRapidAsset::register($this);

?>


<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>

    <?php $this->beginBody() ?>

    <div class="container">
        <?php if (isset($this->params['breadcrumbs'])) { ?>
            <div class="breadcrumbs">
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                ]) ?>
            </div>
        <?php } ?>

        <h1><?= $this->title ?></h1>

        <?= $content ?>

        <div class="clearfix"></div>

    </div>


    <div class="clearfix"></div>

    <footer class="text-center">
        <hr>
        <?= date('Y-m-d H:i:s'); ?>

    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>