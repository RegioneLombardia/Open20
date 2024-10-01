<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\view\manager
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\tag\AmosTag;
use kartik\select2\Select2;

/** @var \yii\web\View $this */
/** @var \kartik\form\ActiveForm $form */
/** @var \open20\amos\tag\models\Tag $node */

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();
$userProfileClassname = $adminModule->model('UserProfile');

if ($node->isRoot()):
    $moduliTaggabili = [];
    /** @var \open20\amos\core\module\AmosModule $module */
    $moduleTag = \Yii::$app->getModule(AmosTag::getModuleName());
    foreach ($moduleTag->modelsEnabled as $module) {
        $function = new \ReflectionClass($module);
        $moduliTaggabili[$module] = $function->getShortName();
    }
    $ruoliDaScegliere = [];
    foreach (Yii::$app->getAuthManager()->getRoles() as $key => $ruolo) {
        $ruoliDaScegliere[$key] = $ruolo->name;
    }

    /**
     * TODO
     * Attenzione: integrare la select2 nel model $node, così va bene
     * ma non benissimo...
     */


    $i =0;
    foreach ($moduliTaggabili as $keyModule => $moduleName): ?>
        <div class="row">
            <div class="col-sm-6">
                <h4><?= AmosTag::tHtml('amostag','Abilita questa root per: ') . $moduleName ?></h4>
            </div>
            <div class="col-sm-12">
                <div class="checkbox">
                <?= Select2::widget([
                    'name' => 'ModelsRoles[' . $keyModule . ']',
                    'value' => $node->getAssignedRolesByClassname($keyModule),
                    'data' => $ruoliDaScegliere,
                    'options' => [
                        'placeholder' => AmosTag::t('amostag','Seleziona un ruolo...'),
                        'multiple' => true
                    ],
                    'id' => 'roleSelect'. $i ,
                    'pluginOptions' => [
                        'tags' => true,
                        'maximumInputLength' => 50
                    ],
                ])
                ?>
                </div>
            </div>
        </div>

        <?php
    $i++;
    endforeach;
    ?>
    <?php if (Yii::$app->getModule('cwh')): ?>

    <div class="row">
        <div class="col-sm-6">
            <h4><?= AmosTag::tHtml('amostag',"&Egrave; un albero per aree di interesse dell'utente?") ?></h4>
        </div>
        <div class="col-sm-12">
            <div class="checkbox">
            <?= Select2::widget([
                'name' => 'CwhTagInterestMm[' . $userProfileClassname . ']',
                'value' => $node->getAssignedInterestByClassname($userProfileClassname),
                'data' => $ruoliDaScegliere,
                'options' => [
                    'placeholder' => AmosTag::t('amostag', 'Seleziona un ruolo...'),
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 50
                ],
            ])
            ?>
            </div>
        </div>
    </div>

    <?php endif; ?>

<?php endif; ?>
