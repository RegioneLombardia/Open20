<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/events/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventGroupReferentMm;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventGroupReferentMm $model
 */

$this->title = AmosEvents::t('amosevents', "Assegna ruolo operatore a " . $profile->nomeCognome);;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Event Group Referent Mm'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$module = \Yii::$app->getModule('events');
$roles = [
    'EVENT_DG' => AmosEvents::t('amosevents', 'Dg'),
    'EVENT_DG_OPERATOR' => AmosEvents::t('amosevents', 'Operatore'),
];
if ($module) {
    $groupReferentAdministration = $module->groupReferentAdministration;
    if (!empty($groupReferentAdministration)) {
        $administrator_group_id = $groupReferentAdministration['id'];
        if ($model->event_group_referent_id == $administrator_group_id) {
            $roles = [
                $groupReferentAdministration['roleAdmin'] => AmosEvents::t('amosevents', 'Super user'),
                $groupReferentAdministration['role'] => AmosEvents::t('amosevents', 'Operatore super user')
            ];
        }
    }
}
?>
<?php $form = ActiveForm::begin() ?>

<div class="event-group-referent-mm-view">


    <?php if (!empty($currentRole)) {
        $labelSubmit = AmosEvents::t('amosevents', 'Attiva');
        ?>
        <div class="form-group col-md-6">

            <h3><?php echo AmosEvents::t('amosevents', "L'utente ha già completato il corso"); ?></h3>
            <h5>
                <?= AmosEvents::t('amosevents', "L'utente attualmente possiede il seguente ruolo") ?>
                <br><strong><?= AmosEvents::t('amosevents', "Ruolo") ?></strong><?= ": " . EventGroupReferentMm::getUserRoleString($model) ?>
            </h5>
            <?= Html::hiddenInput('role', $currentRole) ?>
            <?= Html::hiddenInput('is_ok', true) ?>
        </div>
    <?php } else {
        $labelSubmit = AmosEvents::t('amosevents', 'Assegna ruolo');
        ?>
        <div class="form-group col-md-6">
            <label class="label-control"><?= AmosEvents::t('amosevents', "Ruolo") ?></label>
            <?php echo \kartik\select2\Select2::widget([
                'name' => 'role',
                'data' => [
                    'EVENT_DG' => 'Dg',
                    'EVENT_DG_OPERATOR' => 'Operatore',
                ],
                'options' => ['class' => 'form-control', 'placeholder' => \Yii::t('amosevents', 'Select...')]
            ]) ?>
        </div>
    <?php } ?>

</div>

<div id="form-actions" class="bk-btnFormContainer col-md-12">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary pull-left']); ?>

    <?= Html::submitButton(AmosEvents::t('amosevents', $labelSubmit), ['class' => 'btn btn-primary pull-right']); ?>
</div>
<?php ActiveForm::end(); ?>
