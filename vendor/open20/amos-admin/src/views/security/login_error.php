<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $message
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->title = Yii::t('amosplatform', 'Errore');
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12">
        <h2 class="title-login"><?= Html::encode($this->title) ?></h2>
        <h3 class="subtitle-login"><?= Html::encode($message) ?></h3>
    </div>
    <div class="col-lg-12 col-sm-12 col-xs-12 footer-link text-center">
        <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), (\Yii::$app->request->referrer ?: ['/'.AmosAdmin::getModuleName().'/security/login']), ['class' => 'btn btn-secondary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login'), 'target' => '_self']) ?>
    </div>
</div>
