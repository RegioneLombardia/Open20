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
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\helpers\Html;

ModuleAdminAsset::register(Yii::$app->view);

/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \open20\amos\admin\models\LoginForm $model
 */

$this->title = AmosAdmin::t('amosadmin', 'Login');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="bk-loginContainer loginContainer">
    <div class="body col-xs-12">
        <?php if (!isset($title_message)) { ?>
            <?= Html::tag('h2', AmosAdmin::t('amosadmin', 'Errore'), ['class' => 'title-login']) ?>
        <?php } else { ?>
            <?= Html::tag('h2', $title_message, ['class' => 'title-login']) ?>
        <?php } ?>
        <!-- If the result message is defined, show it -->
        <?php if (isset($result_message)) : ?>
            <!-- If the result message is an array of errors, set the first error message in an h3 tag and the other ones will be set in a p tag -->
            <?php if (is_array($result_message)) {
                foreach ($result_message as $pos => $message) {
                    if ($pos == 0) { ?>
                        <?= Html::tag('h3', $message, ['class' => 'subtitle-login']) . '<hr>' ?>
                    <?php } else { ?>
                        <?= Html::tag('p', $message, ['class' => 'text-center nom']) ?>
                    <?php }
                }
            } else { ?>
                <!-- If the result message is not an array of errors, set the error in a h3 -->
                <?= Html::tag('h3', $result_message, ['class' => 'subtitle-login']) ?>
            <?php } ?>
            <!-- Otherwise, show a generic response message -->
        <?php else : ?>
            <?= Html::tag('h3', AmosAdmin::t('amosadmin', '#generic_register_response_message'), ['class' => 'subtitle-login']) ?>
        <?php endif; ?>

        <div class="col-lg-12 col-sm-12 col-xs-12 footer-link text-center">
            <?php if (!isset($hideGoBackBtn) || !$hideGoBackBtn): ?>
                <?php if (!isset($go_to_login_url)) { ?>
                    <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), ['/'.AmosAdmin::getModuleName().'/security/login'], ['class' => 'btn btn-secondary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login'), 'target' => '_self']) ?>
                <?php } else { ?>
                    <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), $go_to_login_url, ['class' => 'btn btn-secondary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login'), 'target' => '_self']) ?>
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>
</div>
