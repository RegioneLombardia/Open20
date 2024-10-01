<?php
use open20\amos\core\forms\ActiveForm;
use yii\helpers\Html;
use open20\amos\videoconference\AmosVideoconference;

$this->title = AmosVideoconference::t('amosvideoconference', 'Gestisci Partecipanti');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;
?>

<small><strong>Utilizza questa sezione per invitare gli utenti alla videoconferenza o toglierli dalla stessa</strong></small>
<div class="dove-evento my-4">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->render('parts/_form_webmeeting_participant', [
        'form' => $form,
        'model' => $model,
        'modelSearch' => $modelSearch,
        'dataProvider' => $dataProvider,
        'participants_user_profile_ids' => $participants_user_profile_ids,
        'countInvitationUsers' => $countInvitationUsers,
        'dataProviderInvitationUsers' => $dataProviderInvitationUsers,
    ]) ?>

    <div class="buttons">
        <?php
        $urlBack = '/videoconference/videoconf/index';
        if(!empty($redirectUrl)){
            $urlBack = $redirectUrl;
        }
        ?>
        <?php echo Html::a(\Yii::t('amosevents', 'Indietro'), $urlBack, ['class' => 'btn btn-secondary']) ?>

        <?php echo Html::submitButton(\Yii::t('amosevents', 'Conferma'), ['class' => 'btn btn-primary ml-auto pull-right']) ?>
    </div>
    <?php $form = ActiveForm::end(); ?>
</div>