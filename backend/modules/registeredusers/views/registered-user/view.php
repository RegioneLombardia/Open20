<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/registeredusers/views
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var backend\modules\registeredusers\models\Operator $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/registeredusers']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Operator'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registereduser-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'nome',
            'cognome',
            'codice_fiscale',
            'sesso',
            'presentazione_breve',
            'presentazione_personale:html',
            [
                'attribute'=>'nascita_data',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],                
            ],
            'privacy',
            'indirizzo_residenza',
            'cap_residenza',
            'numero_civico_residenza',
            'domicilio_indirizzo',
            'domicilio_civico',
            'domicilio_cap',
            'domicilio_localita',
            'domicilio_lat',
            'domicilio_lon',
            'widgets_selected:html',
            'first_access_redirect_url',
            'first_access_mail_url',
            'first_access_login_effectuated',
            'first_access_wizard_steps_accessed:html',
            'nazionalita',
            'email_pec',
            'altri_dati_contatto:html',
            'telefono',
            'azienda',
            'is_imported',
            'user_import_task_id',
            'cellulare',
            'fax',
            'attivo',
            'status',
            'note:html',
            'partita_iva',
            'iban',
            'facebook',
            'twitter',
            'linkedin',
            'googleplus',
            [
                'attribute'=>'ultimo_accesso',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            'count_logins',
            [
                'attribute'=>'ultimo_logout',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            'validato_almeno_una_volta',
            'avatar_id',
            'nascita_nazioni_id',
            'nascita_province_id',
            'nascita_comuni_id',
            'user_profile_titoli_studio_id',
            'user_profile_stati_civili_id',
            'nazionalita_residenza_id',
            'comune_residenza_id',
            'provincia_residenza_id',
            'domicilio_provincia_id',
            'domicilio_comune_id',
            'residenza_nazione_id',
            'facilitatore_id',
            'external_facilitator_id',
            'default_facilitatore',
            'dont_show_facilitator',
            'enable_facilitator_box',
            'user_profile_area_id',
            'user_profile_area_other',
            'user_profile_role_id',
            'user_profile_role_other',
            'user_profile_age_group_id',
            'prevalent_partnership_id',
            'user_id',
            'notify_from_editorial_staff',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
