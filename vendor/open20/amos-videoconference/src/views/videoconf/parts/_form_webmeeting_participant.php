<?php
use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\bootstrap\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use open20\amos\videoconference\AmosVideoconference;

\open20\amos\videoconference\assets\VideoconfrenceParticipantsAssets::register($this);

/** @var array $participants_user_profile_ids */

$id = $model->id;

$idAllUsers = [];
foreach($dataProvider->getModels() as $model) {
    $idAllUsers []= $model->user_id;
}
$idAllUsers = json_encode($idAllUsers);
$idParticipants = json_encode($participants_user_profile_ids);
$max_participant = 100;
$js = <<<JS

var selected_users = [];
var deleted_users  = [];
var all_users = JSON.parse('{$idAllUsers}').map(number => number.toString());
var participants = JSON.parse('{$idParticipants}').map(number => number.toString());
var selectedAll = false; // flag per il pulsante SELEZIONA TUTTO

//CERCA PER NOME
$(document).on('keyup', '#search-name-id',function(){
    var selected = selected_users;
    var deleted = deleted_users;
    var name = $(this).val();
    if(/*name.length > 3*/ true){
          $.ajax({
                url: '/videoconference/videoconf-ajax/search-users-webmeeting',
                type: 'GET',
                data: { name: $(this).val() ,id: $id, selected_users: selected, deleted_users: deleted},
                success: function(data) {
                    $('#container-result-search').html(data);
                 }
        });
    }
});

// CERCA PER LETTERA
$(document).on('click','.char-search', function(e){
    e.preventDefault();
    var selected = selected_users;
    var deleted = deleted_users;

    $('.char-search').each(function(){
            $(this).removeClass('active');
    });
    
    $(this).addClass('active');
        $.ajax({
            url: '/videoconference/videoconf-ajax/search-users-webmeeting',
            type: 'GET',
            data: { char: $(this).attr('data-key'), id: $id, selected_users: selected, deleted_users: deleted},
            success: function(data) {
                $('#container-result-search').html(data);
             }
        });
});

// PAGINAZIONE GRID
$(document).on('click', '#container-result-search .pagination li a', function(e){
    e.preventDefault();
    var selected = selected_users;
    var deleted = deleted_users;
    
    var name = $('#search-name-id').val();
    var char = $('#toolbar-search-char .active').attr('data-key');
      $.ajax({
            url: '/videoconference/videoconf-ajax/search-users-webmeeting',
            type: 'GET',
            data: {
                name: name,
                char: char,
                id: $id,
                selected_users: selected,
                deleted_users: deleted,
                page: parseInt($(this).attr('data-page')) + 1
                },
            success: function(data) {
                $('#container-result-search').html(data);
             }
        });
});

//INSERISCI UTENTI
$(document).on('click','.checkbox-user', function(){
    //select users
    if(this.checked === true){
        var counter = parseInt($('.counter-selected').text());
        
        if(counter >= $max_participant){
            $(this).removeAttr('checked');
            $('#modal-alert-max-participanti').modal('show');
            return false;
        }
        //limite per api
        if(counter > 100){
            $(this).removeAttr('checked');
            $('#modal-alert-max-total').modal('show');
            return false;
        }
        // se è tra i cancellati lo rimuovo dalla lista cancellati così riappare l'utente
        //altrimenti lo aggiungo alla lista degli utenti inseriti
        const index = deleted_users.indexOf($(this).val());
        if (index > -1) {
            deleted_users.splice(index, 1);
        }else{
            selected_users.push($(this).val());
        }
        //incrementa counter
        $('.counter-selected').text(counter + 1);
        $('.counter-selected-hide').text(counter + 1);
    //delete user
    }else{
        const index = selected_users.indexOf($(this).val());
        if (index > -1) {
            selected_users.splice(index, 1);
        } else {
            deleted_users.push($(this).val());
        }
        //decrementa counter
        var counter = parseInt($('.counter-selected').text());
        $('.counter-selected').text(counter - 1);
        $('.counter-selected-hide').text(counter - 1);
    }
    
    //setta campo di input per salvataggio
    $('#selected-users-ids').val(selected_users.join());
    $('#deleted-users-ids').val(deleted_users.join());

    
    //console.log(selected_users);
    //console.log(deleted_users);
});

// ELIMINA UTENTI
$(document).on('click','.delete-users', function(e){
    e.preventDefault();
    var user_id = $(this).attr('data-key');
    const index = selected_users.indexOf(user_id);
    if (index > -1) {
        selected_users.splice(index, 1);
    }else {
        deleted_users.push(user_id);
    }
    
    //setta campo di input per salvataggio
    $('#selected-users-ids').val(selected_users.join());
    $('#deleted-users-ids').val(deleted_users.join());
    $(this).parents('.item-participant').parent().remove();
    
    //deseleziona checkbox
    $('.checkbox-user[value="'+user_id+'"]').removeAttr('checked');
    
    //decrementa counter
    var counter = parseInt($('.counter-selected').text());
    $('.counter-selected').text(counter - 1);
    $('.counter-selected-hide').text(counter - 1);
    
    //console.log(selected_users);
    //console.log(deleted_users);
});

//MOSTRA TUTTI GLI UTENTI SELEZIONATI E NASCONDI LA RICERCA
$('#show-all-users').click(function(e){
    e.preventDefault();
    $('#container-result-search').hide();
    $('#container-all-users').show();
    
    var users = selected_users.join();
    var users_deleted = deleted_users.join();
    
     $('#show-all-users').hide();
     $('#hide-all-users').show();
     $('#search-fields').hide();
    $.ajax({
        url: '/videoconference/videoconf-ajax/users-invited-webmeeting',
        type: 'GET',
        data: { id: $id, selected_users: users , deleted_users: users_deleted},
        success: function(data) {
            $('#container-all-users').html(data);
         }
    });
});

//NASCONDI TUTTI GLI UTENTI SELEZIONATI E TORNA ALLA RICERCA
$('#hide-all-users').click(function(e){
    e.preventDefault();
    $('#container-result-search').show();
    $('#container-all-users').hide();
    
    $('#show-all-users').show();
    $('#hide-all-users').hide();
    $('#search-fields').show();
    
})

$(document).on('click', '.search-all', function(){
    if(!selectedAll) {
        selected_users = all_users.filter(item => !participants.includes(item));
        deleted_users = [];
    } else {
        selected_users = [];
        deleted_users = participants;
    }
    selectedAll = !selectedAll;
    
    $.ajax({
        url: '/videoconference/videoconf-ajax/search-users-webmeeting',
        type: 'GET',
        data: { char: '', id: $id, selected_users: selected_users, deleted_users: deleted_users},
        success: function(data) {
            $('#container-result-search').html(data);
            
            $('.counter-selected').text(selected_users.length);
            $('.counter-selected-hide').text(selected_users.length);
            
            //setta campo di input per salvataggio
            $('#selected-users-ids').val(selected_users.join());
            $('#deleted-users-ids').val(deleted_users.join());
         }
    });
})

JS;
$this->registerJs($js);


?>



<div id="search-fields">
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($modelSearch, 'name')->textInput([
                    'id' => 'search-name-id',
                    'aria-controls' => 'container-result-search',
                    'placeholder' => \open20\amos\videoconference\AmosVideoconference::t('amosevents','Cerca')
                    // 'type' => 'search'
            ])
            ->label(AmosVideoconference::t('amosevents','Digita qui sotto il nome, cognome, email o il codice fiscale per filtrare i risultati, selezionali per inserirli nella lista degli invitati')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= Html::button(AmosVideoconference::t('amosevents', 'Seleziona / Deseleziona tutti'), [
                'class' => 'btn btn-primary search-all'
            ]) ?>
        </div>
    </div>

    <div class="row m-b-15">
        <div id="toolbar-search-char" class="col-md-12">
            <?= Html::a(AmosVideoconference::t('amosevents','Tutti'), ['/webmeeting/web-meeting-ajax/search-user-events', 'char' => ''], [
                'class' => 'char-search',
                'data-key' => ''
            ]); ?>
            <?php foreach (range('A', 'Z') as $char) {
                echo Html::a($char, ['/webmeeting/web-meeting-ajax/search-user-events', 'char' => $char], [
                    'class' => 'char-search',
                    'data-key' => $char,
                    'aria-controls' => 'container-result-search'
                ]);
            } ?>
        </div>
    </div>
</div>
<div class="row m-b-10">
    <div class="col-md-12">
        <?= Html::a(AmosVideoconference::t('amosevents', "Visualizza tutti gli invitati") . " (<span class='counter-selected'>$countInvitationUsers</span>) ", '#', [
            'id' => 'show-all-users',
            'class' => 'btn btn-primary pull-right',
            'aria-controls' => 'container-all-users'

        ]) ?>
        <?= Html::a(AmosVideoconference::t('amosevents', "Torna alla ricerca"), '#', [
            'id' => 'hide-all-users',
            'class' => 'btn btn-outline-primary pull-right',
            'style' => 'display:none',
            'aria-controls' => 'container-result-search'
        ]) ?>
    </div>
</div>
<?php $dataProviderInvitationUsers->pagination = false;?>
<div style="display:none" id="container-all-users">
    <?= $this->render('_result_search', [
        'dataProvider' => $dataProviderInvitationUsers,
        'participants' => true,
        'readOnly' => false,

    ])
    ?>
</div>

<?php $dataProvider->pagination->pageSize = 30; ?>
<div id="container-result-search" class="container-partecipants">
    <?= $this->render('_result_search', [
        'dataProvider' => $dataProvider,
        'participants_user_profile_ids' => $participants_user_profile_ids,
        'participants' => false,
        'readOnly' => false,
    ])
    ?>
</div>

<?= Html::hiddenInput('selected_users_ids', null, ['id' => 'selected-users-ids']) ?>
<?= Html::hiddenInput('deleted_users_ids', null, ['id' => 'deleted-users-ids']) ?>
<?= Html::hiddenInput('submit_participants', 1, ['id' => 'submit_participants']) ?>

<?php
\yii\bootstrap\Modal::begin([
    'id' => 'modal-alert-max-participanti',
    'header' => "<h3>" . AmosVideoconference::t('amosevents', '') . "</h3>",
    'size' => 'modal-lg',

]); ?>
<h5><?=  AmosVideoconference::t('amosevents', 'Attenzione! Puoi inserire un massimo di {x} partecipanti',[
        'x'=> $max_participant
    ]) ?>
</h5>
<?php
\yii\bootstrap\Modal::end();
?>


<?php
\yii\bootstrap\Modal::begin([
    'id' => 'modal-alert-max-total',
    'header' => "<h3>" . AmosVideoconference::t('amosevents', '') . "</h3>",
    'size' => 'modal-lg',

]); ?>
<h5><?=  AmosVideoconference::t('amosevents', 'Attenzione! Puoi selezionare un massimo di 100 utenti alla volta, effettua il salvataggio e continua ad invitare altri utenti.') ?>
</h5>
<?php
\yii\bootstrap\Modal::end();
?>
