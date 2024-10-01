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
use open20\amos\admin\models\UserProfileClasses;

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

    <div>
        <?php if ($manage_profile) { ?>
            <div>
                <a href="/<?= AmosAdmin::getModuleName() ?>/user-profile-classes/index" class="btn btn-action-primary pull-right"><?=
                                                                                                                                    AmosAdmin::t('amosadmin', 'Gestisci i profili')
                                                                                                                                    ?></a>
            </div>
        <?php
        }
        ?>
        <div>
            <?php
            if ($permission) {
            ?>
                <?php // $form->field($model, 'profiles')->checkboxList($profiles)  
                ?>
                <h2><?= $model->getAttributeLabel('profiles') ?></h2>
                <div class="permission-checkbox">
                    <fieldset>
                        <legend class="sr-only">User Profile[profiles][]</legend>
                        <input type="hidden" name="UserProfile[profiles]" value="">
                        <div id="userprofile-profiles" class="userprofile-profiles-checkbox row">
                            <?php
                            if (empty($profiles)) {
                            ?>
                                <div class="checkbox">
                                    <?=
                                    AmosAdmin::t('amosadmin', 'Nessun profilo disponibile.');
                                    ?>
                                </div>
                                <?php
                            } else {
                                foreach ($profiles as $k => $v) {

                                    $item = UserProfileClasses::findOne($k);
                                    if (!empty($item)) {
                                ?>

                                        <div class="checkbox">
                                            <input type="checkbox" id="UserProfile[profiles][]<?= $k ?>" name="UserProfile[profiles][]" value="<?= $k ?>" <?=
                                                                                                                                                            ((!empty($model->profiles) && array_key_exists($k, $model->profiles)) ? 'checked' : '')
                                                                                                                                                            ?>>
                                            <label class="no-asterisk" for="UserProfile[profiles][]<?= $k ?>">

                                                <div class="avatar-wrapper avatar-extra-text mb-0">
                                                    <div class="avatar-box-img">
                                                        <?=
                                                        AmosIcons::show(
                                                            'account-circle',
                                                            ['class' => 'avatar size-lg'],
                                                            AmosIcons::AM
                                                        )
                                                        ?>
                                                    </div>

                                                    <div class="ml-2 avatar-body">
                                                        <div class="name-manage">
                                                            <p class="avatar-name font-weight-bold mb-0">
                                                                <?= $item->name ?>
                                                            </p>
                                                        </div>
                                                        <small class="avatar-info font-weight-normal mb-0">
                                                            <?= strip_tags($item->description) ?>
                                                        </small>
                                                    </div>
                                                </div>

                                            </label>
                                        </div>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </fieldset>
                </div>
                <?php
            } else {
                $profileClasses = $model->profileClasses;
                if (!empty($profileClasses)) {
                ?>
                    <h2><?= $model->getAttributeLabel('profiles') ?></h2>
                    <div class="permission-container">
                        <?php foreach ($profileClasses as $item) { ?>

                            <div class="avatar-wrapper avatar-extra-text mb-0">
                                <div class="avatar-box-img">
                                    <?=
                                    AmosIcons::show(
                                        'account-circle',
                                        ['class' => 'avatar size-lg'],
                                        AmosIcons::AM
                                    )
                                    ?>
                                </div>

                                <div class="ml-2 avatar-body">
                                    <div class="name-manage">
                                        <p class="avatar-name font-weight-bold mb-0">
                                            <?= $item->name ?>
                                        </p>
                                    </div>
                                    <small class="avatar-info font-weight-normal mb-0">
                                        <?= strip_tags($item->description) ?>
                                    </small>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <?= AmosAdmin::t('amosadmin', 'Nessun profilo associato'); ?>
            <?php
                }
            }
            ?>
        </div>
    </div>

</section>