<?php

use open20\amos\layout\Module;
use open20\amos\core\helpers\Html;
use open20\amos\layout\assets\BaseAsset;

$asset = BaseAsset::register($this);
?>


<?php
$this->title = Module::t('amoslayout', '#error_title_page');

/* ASSISTANCE CONTROL LIKE PARTS/ASSISTANCE */
//Pickup assistance params
$assistance = isset(\Yii::$app->params['assistance']) ? \Yii::$app->params['assistance'] : [];

//Check if is in email mode
$isMail = ((isset($assistance['type']) && $assistance['type'] == 'email') || (!isset($assistance['type']) && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
$mailAddress = isset($assistance['email']) ? $assistance['email'] : (isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : '');

$previousUrl = !empty(Yii::$app->session->get('previousUrl')) ? Yii::$app->session->get('previousUrl') : '/';
?>

<?php if ($exception->statusCode == '404'): ?>
    <div class="error-content col-xs-12 nop">
        <div class="image col-xs-12">
            <?= Html::img($asset->baseUrl . '/img/error/icon-error-404.png', ['alt' => '404 error', 'class' => 'img-responsive']) ?>
        </div>
        <div class="text col-xs-12">
            <h1><?= Module::t('amoslayout', '#error_title_404') ?></h1>
            <h2><?= Module::t('amoslayout', '#error_text_404') ?></h2>
            <div class="actions">
                <?php if(Yii::$app->request->referrer): ?>
                <?= Html::a(Module::t('amoslayout', '#error_goback_btn'), $previousUrl, ['class' => 'btn btn-secondary', 'title' => Module::t('amoslayout', '#error_goback_btn')]) ?>
                <?php else: ?>
                <?= Html::a(Module::t('amoslayout', '#error_go_dash'), Yii::$app->params['platform']['backendUrl'], ['class' => 'btn btn-secondary', 'title' => Module::t('amoslayout', '#error_go_dash')]) ?>
                <?php endif;?>
                <?php if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))): ?>
                    <?= Html::a(
                        Module::t('amoslayout', '#error_contact_helpdesk'),
                        $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : ''),
                        [
                            'class' => 'btn btn-action-primary',
                            'title' => Module::t('amoslayout', '#error_contact_helpdesk')
                        ])
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php elseif ($exception->statusCode == '403'): ?>
    <div class="error-content col-xs-12 nop">
        <div class="image col-xs-12">
            <?= Html::img($asset->baseUrl . '/img/error/icon-error-403.png', ['alt' => '403 error', 'class' => 'img-responsive']) ?>
        </div>
        <div class="text col-xs-12">
            <h1><?= Module::t('amoslayout', '#error_title_403') ?></h1>
            <h2><?= Module::t('amoslayout', '#error_text_403') ?></h2>
            <div class="actions">
                <?= Html::a(Module::t('amoslayout', '#error_goback_btn'), $previousUrl, ['class' => 'btn btn-secondary', 'title' => Module::t('amoslayout', '#error_goback_btn')]) ?>
<!--                --><?php //Html::a(Module::t('amoslayout', '#error_goback_ask_auth'), '#', ['class' => 'btn btn-navigation-primary', 'title' => Module::t('amoslayout', '#error_goback_ask_auth')]) ?>
                <?php if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))): ?>
                    <?= Html::a(
                        Module::t('amoslayout', '#error_contact_helpdesk'),
                        $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : ''),
                        [
                            'class' => 'btn btn-action-primary',
                            'title' => Module::t('amoslayout', '#error_contact_helpdesk')
                        ])
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="error-content generic-error col-xs-12 nop">
        <div class="image col-xs-12">
            <?= Html::img($asset->baseUrl . '/img/error/icon-error-generic.png', ['alt' => 'generic error', 'class' => 'img-responsive']) ?>
        </div>
        <div class="text col-xs-12">
            <h1><?= Module::t('amoslayout', '#error_title_generic') ?></h1>
            <h2><?= Module::t('amoslayout', '#error_text_generic') ?></h2>
            <div class="actions">
                <?= Html::a(Module::t('amoslayout', '#error_goback_btn'), $previousUrl, ['class' => 'btn btn-secondary', 'title' => Module::t('amoslayout', '#error_goback_btn')]) ?>
                <?php if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))): ?>
                    <?= Html::a(
                        Module::t('amoslayout', '#error_contact_helpdesk'),
                        $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : ''),
                        [
                            'class' => 'btn btn-action-primary',
                            'title' => Module::t('amoslayout', '#error_contact_helpdesk')
                        ])
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

