<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$linkPrivacy = '@app/views/site/privacy';

if (isset(\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'])) {
    $linkPrivacy = \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'];
}

?>

<section class="section-data">
    <?php if ($adminModule->confManager->isVisibleField('privacy', ConfigurationManager::VIEW_TYPE_FORM)) : ?>
        <div>
            <?php if (!isset(\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'])) : ?>
                <div>
                    <?= $form->field($model, 'privacy')->label('<a data-toggle="modal" href="#" data-target="#modalPrivacy">Visualizza e accetta il documento della privacy</a>')->checkbox() ?>
                </div>
            <?php else : ?>
                <div>
                    <a href="<?= $linkPrivacy ?>" target='_blank' title="<?= AmosAdmin::t('amosadmin', 'Visualizza il documento della privacy in nuova finestra') ?>">
                        <?= AmosAdmin::t('amosadmin', 'Visualizza il documento della privacy') . ' ' . AmosIcons::show('square-right') ?>
                    </a>
                    <?= $form->field($model, 'privacy')->hiddenInput(['value' => 1]); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php if (!isset(\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'])) : ?>
<!-- Modal -->
<div class="modal fade" id="modalPrivacy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= AmosAdmin::t('amosadmin', '#privacy_label') ?></h4>
            </div>
            <div class="modal-body">
                <?= $this->render($linkPrivacy); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= AmosAdmin::t('amosadmin', '#close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
