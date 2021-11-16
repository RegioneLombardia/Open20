<?php
$js = <<<JS
    $('#btn-search').click(function(e){
        e.preventDefault();
        var a = $('.search-div input').serialize();
        $.pjax.reload( {container: "#container-pjax", url: window.location.href+"&"+a  , timeout: 20000}).done(
            function(){
                    var selected_radio =$('input[type=radio]:checked');
                    $(selected_radio)
                    .parents('tr')
                    .addClass('success');
            });
    });
 $('#btn-search-cancel').click(function(e){
        e.preventDefault();
        $('.search-div input').val('');
        var a = $('.search-div input').serialize();
        $.pjax.reload( {container: "#container-pjax", url: window.location.href+"&"+a  , timeout: 20000}).done(
            function(){
                    var selected_radio =$('input[type=radio]:checked');
                    $(selected_radio)
                    .parents('tr')
                    .addClass('success');
            });
    });
 
 $('.search-div input').keypress(function(e){

    if(e.keyCode == 13)
    {
        e.preventDefault();
        var a = $('.search-div input').serialize();
        $.pjax.reload( {container: "#container-pjax", url: window.location.href+"&"+a  , timeout: 20000}).done(
            function(){
                    var selected_radio =$('input[type=radio]:checked');
                    $(selected_radio)
                    .parents('tr')
                    .addClass('success');
            });
    }
    });
JS;
$this->registerJs($js);
?>
<div class="container-change-view ">
    <div class="btn-tools-container">
        <div class="tools-right">
            <?= \open20\amos\core\icons\AmosIcons::show('search', [
                'class' => 'btn btn-tools-primary show-hide-element',
                'data-toggle-element' => 'form-search'
            ]);?>
        </div>

    </div>
</div>

<div class="search-div element-to-toggle"  data-toggle-element="form-search">
<?php foreach ($contentModelSearch->searchFieldsLike() as $searchField){ ?>
    <div class="col-lg-6 col-xs-12">
        <?= $form->field($contentModelSearch, $searchField)->textInput() ?>
    </div>
<?php } ?>
    <div class="col-xs-12">
        <div class="pull-right">
            <?php echo \yii\helpers\Html::a('Annulla', '', ['id' => 'btn-search-cancel', 'class' => 'btn btn-secondary-primary'])?>

            <?php echo \yii\helpers\Html::a('search', '', ['id' => 'btn-search', 'class' => 'btn btn-navigation-primary'])?>
        </div>
    </div>
</div>
