<?php
use yii\helpers\Url;
use yii\web\View;

$urlDeleteProfile = Url::to('/preferenceuser/preference/delete-profile');

$this->registerJs(<<<JS

$("a[id='anchor-elimina-profilo-id']").click(function(event) {
    event.preventDefault();
    $('#sprofile-delete-id').modal('show');
    var modalConfirm = function(callback){
        $("#ok-profile-delete-id").on("click", function(){
            callback(true);
            $('#profile-delete-id').modal('hide');
        });
        
        $("#cancel-profile-delete-id").on("click", function(){
            callback(false);
            $('#profile-delete-id').modal('hide');
        });
    };

    modalConfirm(function(confirm){
        if(confirm){
            location.href = '{$urlDeleteProfile}';
        }
    });

});

JS
, View::POS_READY );
?>