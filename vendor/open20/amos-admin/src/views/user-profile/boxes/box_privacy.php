<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

?>

<section class="section-data">
    <?php if ($adminModule->confManager->isVisibleField('privacy', ConfigurationManager::VIEW_TYPE_FORM)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'privacy')->label('<a data-toggle="modal" data-target="#modalPrivacy">Visualizza e accetta il documento della privacy</a>')->checkbox() ?>
            </div>
<!--            <div class="col-xs-4 m-t-20">-->
<!--                <a href='/site/privacy' target='_blank'>  < ?=AmosAdmin::t('amosadmin','Visualizza il documento della privacy')?></a>-->
<!--            </div>-->
        </div>
    <?php endif; ?>
</section>

<!-- Modal -->
<div class="modal fade" id="modalPrivacy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=AmosAdmin::t('amosadmin','#privacy_label')?></h4>
            </div>
            <div class="modal-body">
                <?= $this->render('@app/views/site/privacy'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=AmosAdmin::t('amosadmin','#close')?></button>
            </div>
        </div>
    </div>
</div>