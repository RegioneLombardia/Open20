<?php
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;

ModuleAdminAsset::register($this);


$currentUrl = \yii\helpers\Url::current();
//$this->registerCss('#grid-contact-share .select-on-check-all{ display: none; }');
$amosadmin = AmosAdmin::getModuleName();
$js = <<<JS
    var selectedProfilesIds = [];
     // event on click on the ckeckbox
    $(document).on('click', 'input[name="share-profile-selected[]"]', function(){
        if(this.checked){
           selectedProfilesIds.push($(this).val());
        }
        else {
            var index = selectedProfilesIds.indexOf($(this).val());
            if (index > -1) {
              selectedProfilesIds.splice(index, 1);
            }
        }
    });
    
    // set the checkbox to checked when you change page
    function setChecked(){
        $('#grid-contact-share tbody tr').each(function() {
                var valore = $(this).find('input').val();
                var flag = 0;
               
                for(var i=0; i < selectedProfilesIds.length; i++) {
                     if(selectedProfilesIds[i] == valore ) {
                         $(this).find('input').attr('checked', true);
                         $(this).addClass('success');
                         flag = 1;        
                     }
                }
                
                if(flag == 0) {
                     $(this).removeClass('success');
                    $(this).find('input').removeAttr('checked');
                }
            });
    }
    
    // at the reload of pjax set the checkbox to checked is already selected
     $(document).on('pjax:end', function(data, status, xhr, options) {
            setChecked();
     });
    
    
    // call ajax the insert the text  in the chat of the users selected
    $('#share-to-contacts-btn').click(function(){
        $('#alert-error-share').hide();
        if(confirm('Sei sicuro di condividere questo elemento con i contatti selezionati?')){
            var text = $('#text-share').val();
            var modal = $('#modal-contacts-share');
            var url = modal.attr('data-url');
            var classname = modal.attr('data-content-classname');
            var content_id = modal.attr('data-content-id');
            
            if(selectedProfilesIds.length == 0){
                $('#alert-error-share').show();
            }else {
                $.ajax({
                   url: '/$amosadmin/user-profile-ajax/ajax-share-with',
                   type: 'post',
                   data: {
                       url: url , 
                       text: text,
                       selected_users  : selectedProfilesIds,
                   },
                   success: function (data) {
                       if(data == 'true'){
                           selectedProfilesIds = [];
                           $('#modal-contacts-share').modal('hide');
                       }
                   }
                });
            }
        }
    });  
    
    // search button
    $('#search-users-share-btn').click(function(){
        var text = $('#text-share').val();
        var modal = $('#modal-contacts-share');
        var url = modal.attr('data-url');
        var classname = modal.attr('data-content-class');
        var contentId = modal.attr('data-content-id');
        var searchName = $('#search-users-share').val();

        $.pjax.reload({
            url: '/$amosadmin/user-profile-ajax/ajax-contact-list?classname='+encodeURIComponent(classname)+'&content_id='+contentId+'&searchName='+searchName,
            container:'#pjax-container-contact-share',
            replace : false, // avoid tha change of url in the navigation search bar
            method: 'get',
            timeout: 5000
        });
    });
    
    // reset search button
    $('#reset-search-share-btn').click(function(){
        var modal = $('#modal-contacts-share');
        var classname = modal.attr('data-content-class');
        var contentId = modal.attr('data-content-id');
        var searchName = $('#search-users-share').val('');

        $.pjax.reload({
            url: '/$amosadmin/user-profile-ajax/ajax-contact-list?classname='+encodeURIComponent(classname)+'&content_id='+contentId+'&searchName=',
            container:'#pjax-container-contact-share',
            replace : false,
            method: 'get',
            timeout: 5000
        });
    });
    
    //select all users in the page (not all the users in all the pages)
    $('.select-on-check-all').click(function(){
         if(this.checked){
             $('input[name="share-profile-selected[]"]').each(function(){
                 selectedProfilesIds.push($(this).val());
                 $(this).parents('tr')
                    .addClass('success');
             });
        }
        else {
              $('input[name="share-profile-selected[]"]').each(function(){
                 var index = selectedProfilesIds.indexOf($(this).val());
                    if (index > -1) {
                      selectedProfilesIds.splice(index, 1);
                      $(this).parents('tr')
                            .removeClass('success');
                    }
             });
        }
        selectedProfilesIds = jQuery.unique(selectedProfilesIds);
    })


JS;

$this->registerJs($js);
?>

<div id="alert-error-share" class="alert-danger alert fade in" role="alert" hidden>
    E' necessario selezionare almeno un utente
</div>
<p><?= AmosAdmin::t('amosadmin', 'Scegli con quali utenti della tua rete personale condividere questo contenuto')?></p>
<div class="container-tools">
    <div class="search-recipients">
        <div class="col-xs-12">
            <div class="col-sm-6 col-sm-push-6 btn-search-admin">
                <?= Html::input('text', null, null, [
                    'id' => 'search-users-share',
                    'class' => 'form-control pull-left',
                    'placeholder' => AmosAdmin::t('amosadmin', 'Search ...')
                ]) ?>
                <?= Html::a(AmosIcons::show('search'),
                    null,
                    [
                        'id' => 'search-users-share-btn',
                        'class' => 'btn btn btn-tools-secondary',
                    ])
                ?>
                <?= Html::a(AmosIcons::show('close'),
                    null,
                    [
                        'id' => 'reset-search-share-btn',
                        'class' => 'btn btn-danger-inverse',
                        'alt' => AmosAdmin::t('amosadmin', 'Cancel recipient search')
                    ])
                ?>
            </div>
        </div>
    </div>
</div>
<?php
\yii\widgets\Pjax::begin([
     'id' => 'pjax-container-contact-share',
    'timeout' => 2000,
    'enablePushState' => false,
    'enableReplaceState' => false,
    'clientOptions' => ['data-pjax-container' => 'grid-contact-share']]);

echo \open20\amos\core\views\AmosGridView::widget([
    'id' => 'grid-contact-share',
    'dataProvider' => $dataProvider,
    'columns' => [
        'Photo' => [
            'headerOptions' => [
                'id' => \Yii::t('amoscore', 'Photo'),
            ],
            'contentOptions' => [
                'headers' => \Yii::t('amoscore', 'Photo'),
            ],
            'label' => \Yii::t('amoscore', 'Photo'),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var \open20\amos\admin\models\UserProfile $userProfile */
                $userProfile = $model->user->getProfile();
                return \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $userProfile]);
            }
        ],
        'nomeCognome',
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'name' => 'share-profile-selected',
            'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
            'checkboxOptions' => function ($model, $key, $index, $column) use ($availableUserProfileIds) {
                if (!in_array($model->id, (array)$availableUserProfileIds)) {
                    return [
                        'disabled' => true,
                        'title' => \open20\amos\admin\AmosAdmin::t('amosadmin', "Non è possibile condividere il contenuto con questo utente. Il contenuto si trova in una community non accessibile a questo utente")
                    ];
                }
                return '';
            }
        ],
//        [
//            'class' => 'yii\grid\CheckboxColumn',
//            'name' => 'share-profile-selected',
//
//        ],

    ]
]);
\yii\widgets\Pjax::end();
?>
<div class="col-xs-12 nop modal-contact-comment">
    <label class="control-label"><?= \open20\amos\admin\AmosAdmin::t('amosadmin', 'Comment') ?></label>
    <?= \open20\amos\core\helpers\Html::textarea(\open20\amos\admin\AmosAdmin::t('amosadmin', 'Comment'),'', ['id' => 'text-share', 'class' => 'form-control']); ?>
</div>


<?php echo \open20\amos\core\helpers\Html::button(\open20\amos\admin\AmosAdmin::t('amosadmin','Condividi'), [
    'id' => 'share-to-contacts-btn',
    'class' => 'btn btn-navigation-primary pull-right'
]);
?>

