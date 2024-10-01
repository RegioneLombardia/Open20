<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/cms-dashboard/src/views 
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
*/

$this->title = Yii::t('amoscore', 'Index');
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
    // Per spostare in basso l'item della sidebar
    $(document).on('click', 'a[id^="sidebarItemDown-"]', function(event){
        event.preventDefault();
        let idClicked = parseInt($(this).attr('name'));
        let idNeighbor = idClicked + 1;
        callAjax(idClicked, idNeighbor);
    });
    
// Per spostare in alto l'item della sidebar
    $(document).on('click', 'a[id^="sidebarItemUp-"]', function(event){
        event.preventDefault();
        let idClicked = parseInt($(this).attr('name'));
        let idNeighbor = idClicked - 1;
        callAjax(idClicked, idNeighbor);
    });
    
    // Chiamata per effettuare lo scambio di posizione di due item della sidebar
    function callAjax(idClicked, idNeighbor) {
        $.ajax({
            url: '/dashboards/cms-dash-sidebar-item/swap-position',
            type: "POST",
            data: { idClicked: idClicked, idNeighbor: idNeighbor },
            success: function(data){
                $('#listSidebarItems').html(data);
                
                // Scambio dei due item
                let elementClicked = $('.JqueryItem-' + idClicked);
                let elementNeighbor = $('.JqueryItem-' + idNeighbor);
                
                let tmp = elementClicked.html();
                elementClicked.html(elementNeighbor.html());
                elementNeighbor.html(tmp);
            },
            error: function(e){
                console.log(e);
            }
        });
    }
JS;
$this->registerJs($js);
echo $this->render('parts/_updatedListItems', [
        'dataProvider' => $dataProvider,
        'currentView' => $currentView
]);
?>

