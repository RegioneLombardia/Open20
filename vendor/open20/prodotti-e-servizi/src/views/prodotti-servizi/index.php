<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views
 */

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var frontend\models\search\ProdottiServiziSearch $model
 */

use open20\prodottiservizi\utility\ProdottiServiziUtility;

$scope = $parametro['scope'];
$destinatari = $parametro['destinatari'];
$soluzioni = $parametro['soluzioni'];
$finalita = $parametro['finalita'];

$js = <<<JS
    var dati = {
        'dest': [],
        'solu' : 0,
        'oldSolu' : 0,
        'fina' : [],
        'stat' : 0,
        'scope' : {$scope}
    };
    
    // Indica se nella chiamata ajax bisogna aggiornare o meno lo stato dei pulsanti delle soluzioni
    var updateSolutions = true;
    
    // Prima chiamata
    createUrlParams();
    
    // Input titolo
    $(document).on('click', '.JquerySendFilter', function(){
        dati['title'] = $('#field_title').val();
        createUrlParams();
    });
    
    // Input pulsanti. Soluzioni non è array quindi ha bisogno di un controllo in più
    $(document).on('click', '.JqueryButtonFilter', function(){
        if($(this).hasClass('disabled') || $(this).is('[id^="solu-"]')) {
            return ;
        }
        $(this).toggleClass('active');
        let dataId = $(this).attr('id').split('-');

        if($(this).hasClass('active')){
            dati[dataId[0]].push(dataId[1]);
        }
        else{
            let index = dati[dataId[0]].indexOf(dataId[1]);
            dati[dataId[0]].splice(index, 1);
        }
        createUrlParams();
    });
    
    $(document).on('click', '[id^="solu-"].JqueryButtonFilter', function(){
        if($(this).hasClass('disabled')) {
            return;
        }
        // Controllo se c'è già una soluzione selezionata, se c'è e non è quella cliccata allora la deseleziono
        var activeSolu = $('[id^="solu-"].active');
        if(activeSolu.length > 0 && activeSolu.get(0) != $(this).get(0)) {
            activeSolu.toggleClass('active');
        }
        
        $(this).toggleClass('active');
        if($(this).hasClass('active')) {
            let dataId = $(this).attr('id').split('-');
            dati['solu'] = dataId[1];
            updateSolutions = false;
        }
        else {
            dati['solu'] = 0;
            updateSolutions = true;
        }
        createUrlParams();
    });
    
    // Input radio
    $(document).on('change', '.JqueryRadioFilter', function(){
        let attribute = $(this).attr('name');
        dati[attribute] = $(this).val();
        createUrlParams();
    });
    
    // Reset dei filtri
    $(document).on('click', '.JqueryResetFilter', function(){
        dati['dest'] = [];
        dati['solu'] = 0;
        dati['fina'] = [];
        dati['stat'] = 0;
        
        $('#all-status').prop("checked", true);
        $('#all-scope').prop("checked", true);
        $('.chip, .JqueryButtonFilter, .active').each(function(){
           $(this).removeClass('active'); 
        });
        createUrlParams();
    });
    
    // Crea form da inviare via ajax
    function createUrlParams() {
        var params = '';
        
        if(dati['solu'] != dati['oldSolu'] && !updateSolutions) {
            updateSolutions = false;
        }
        else {
            updateSolutions = true;
        }
        for(let i=0; i < dati['dest'].length; i++) {
            params += addSymbolUrl(params) + "ProdottiServiziSearch[prodotti_servizi_destinatari_mm][" + i + "]=" + dati['dest'][i];
        }
        if(dati['solu'] != 0) {
            params += addSymbolUrl(params) + "ProdottiServiziSearch[solution][0]=" + dati['solu'];
        }
        for(let i=0; i < dati['fina'].length; i++) {
            params += addSymbolUrl(params) + "ProdottiServiziSearch[prodotti_servizi_finalita_mm][" + i + "]=" + dati['fina'][i];
        }
        if(dati['stat'] != 0) {
            params += addSymbolUrl(params) + "ProdottiServiziSearch[status]=" + dati['stat'];
        }
        if(dati['scope'] != 0) {
            params += addSymbolUrl(params) + "ProdottiServiziSearch[scope]=" + dati['scope'];
        }
        
        // Salvo da parte l'ultima soluzione utilizzata
        dati['oldSolu'] = dati['solu'];
        ajaxCall(params);
    }
    
    function ajaxCall(params) {
        $.ajax({
            url: '/prodotti-e-servizi/prodotti-servizi/ajax-filter-prodotti' + params,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('#dataproviderProdotti').html('<span class="loading-spinner"></span>');
            },
            success: function (response) {
                if (response.error) {
                    console(response.error.msg);
                } else {
                    response = response.split('|||');
                    $('#dataproviderProdotti').html(response[0]);

                    // Gestione prepruning filtri
                    let prepruningData = response[1].split(':::');
                    let prepruningSoluzioni = prepruningData[1].split(',');
                    let prepruningFinalita = prepruningData[3].split(',');
                    let prepruningDestinatari = prepruningData[5].split(',');

                    if(updateSolutions) {
                        $('[id*="solu-"]').each(function(){
                            if(prepruningSoluzioni.includes($(this).attr('id'))){
                                $(this).removeClass('disabled');
                            }
                            else{
                                $(this).addClass('disabled');
                            }
                        });
                    }
                    $('[id*="fina-"]').each(function(){
                        if(prepruningFinalita.includes($(this).attr('id'))){
                            $(this).removeClass('disabled');
                        }
                        else{
                            $(this).addClass('disabled');
                        }
                    });
                    $('[id*="dest-"]').each(function(){
                        if(prepruningDestinatari.includes($(this).attr('id'))){
                            $(this).removeClass('disabled');
                        }
                        else{
                            $(this).addClass('disabled');
                        }
                    });
                    $('.icon-card').each(function(){
                        if(window.getComputedStyle(this,':before').content == 'none'){
                            let classes = $(this).attr('class');
                            classes=classes.replace('-outline', '').replace('mdi ', 'mdi mdi-alert-outline ');
                            $(this).attr('class', classes);
                        }
                    });
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    }
    
    function addSymbolUrl(params) {
        return (params === '') ? "?" : "&";
    }
JS;
$this->registerJs($js);

$this->title = ($scope == 0) ? Yii::t('amoscore', 'Tutti i Prodotti e Servizi') : ProdottiServiziUtility::getAmbitoLabel($scope);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/frontend']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_searchFilter', [
    'scope' => $scope,
    'destinatari' => $destinatari,
    'soluzioni' => $soluzioni,
    'finalita' => $finalita
]); ?>
<div id="dataproviderProdotti" class="prodotti-servizi-index">
    <span class="loading-spinner"></span>
</div>