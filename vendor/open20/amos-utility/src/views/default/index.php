<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\utility\Module;
use open20\amos\utility\utility\UtilityUtility;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $moduleNames array
 */

$this->title = Module::t('amosutility', 'Functions List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="default-index">
    <div class="row">
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Database'); ?></h3>
            <p><?= Module::t('amosutility', 'Manage Database with Adminer'); ?></p>
            <p><?= Html::a('Start &raquo;', ['database/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'File Manager'); ?></h3>
            <p><?= Module::t('amosutility', 'Browser and manage files'); ?></p>
            <p><?= Html::a('Start &raquo;', ['files/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Packages'); ?></h3>
            <p><?= Module::t('amosutility', 'See all installed packages (composer)'); ?></p>
            <p><?= Html::a('Start &raquo;', ['packages/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Logs'); ?></h3>
            <p><?= Module::t('amosutility', 'Browse Logs'); ?></p>
            <p><?= Html::a('Start &raquo;', ['logs/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Requirements'); ?></h3>
            <p><?= Module::t('amosutility', 'Check if app requirements are satisfied'); ?></p>
            <p><?= Html::a('Start &raquo;', ['packages/requirements'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Tasks'); ?></h3>
            <p><?= Module::t('amosutility', 'Manage Applications TAsks'); ?></p>
            <p><?= Html::a('Start &raquo;', ['tasks/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Workflow Manager'); ?></h3>
            <p><?= Module::t('amosutility', 'Manage workflow'); ?></p>
            <p><?= Html::a('Start &raquo;', ['/workflow-manager'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Translate Manager'); ?></h3>
            <p><?= Module::t('amosutility', 'Manage Translation'); ?></p>
            <p><?= Html::a('Start &raquo;', ['/translatemanager'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Reset dashboard'); ?></h3>
            <p><?= Module::t('amosutility', 'Reset dashboard'); ?></p>
            <?php ActiveForm::begin(['id' => 'module', 'action' => '/utility/default/reset-dashboard-by-module']) ?>
            <?= Select::widget([
                'data' => $moduleNames,
                'name' => 'moduleName',
                'options' => [
                    'placeholder' => Module::t('amosutility', 'select a specific module or leave empty to reset all'),
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
            <p><?= Html::submitButton('Start &raquo;', ['class' => 'btn btn-default']) ?></p>
            <?php ActiveForm::end() ?>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Run Console Command'); ?></h3>
            <p><?= Module::t('amosutility', 'Run Console Command'); ?></p>
            <p><?= Html::a('Start &raquo;', ['console/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Clean Cache'); ?></h3>
            <p><?= Module::t('amosutility', 'Clean the app cache'); ?></p>
            <p><?= Html::a('Start &raquo;', ['cache/clean'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Run Migrations'); ?></h3>
            <p><?= Module::t('amosutility', 'Run migrations like a console command'); ?></p>
            <p><?= Html::a('Start &raquo;', ['console/migrate'], ['class' => 'btn btn-default']) ?></p>
        </div>
        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Force Translate'); ?></h3>
            <p><?= Module::t('amosutility', 'Run force translate like a console command'); ?></p>
            <p><?= Html::a('Start &raquo;', ['cache/translate'], ['class' => 'btn btn-default']) ?></p>
        </div>

        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Impersonate'); ?></h3>
            <p><?= Module::t('amosutility', 'Select user to impersonate'); ?></p>
            <?php ActiveForm::begin(['id' => 'impersonate', 'method' => 'get', 'action' => '/admin/security/impersonate']) ?>
            <?= Select::widget([
                'data' => UtilityUtility::getUsersToImpersonate(),
                'name' => 'user_id',
                'options' => [
                    'lang' => substr(Yii::$app->language, 0, 2),
                    'multiple' => false,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
            <p><?= Html::submitButton(Module::t('amosutility', 'Impersonate') . ' &raquo;', ['class' => 'btn btn-default']) ?></p>
            <?php ActiveForm::end() ?>
        </div>
        <?php /*

        <div class="generator col-lg-4">
            <h3><?= Module::t('amosutility', 'Bullet Counter'); ?></h3>
            <p><?= Module::t('amosutility', 'Run bullet counter command'); ?></p>
            <p><?= Html::a('Start &raquo;', ['bullet-counters/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
    </div>
</div>
