<?php
/**
 * @var array $dirTree
 * @var \yii\web\View $this
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;

use open20\amos\documenti\AmosDocumenti;

use execut\widget\TreeView;

use yii\web\JsExpression;
use \yii\bootstrap\Button;

$this->title = AmosDocumenti::t('amosdocumenti', '#choose_nodes_title');
$this->params['breadcrumbs'][] = $this->title;

$hashFile = \Yii::$app->request->get('item');

$moduleL = \Yii::$app->getModule('layout');
if (!empty($moduleL)) {
    \open20\amos\layout\assets\SpinnerWaitAsset::register($this);
} else {
    \open20\amos\core\views\assets\SpinnerWaitAsset::register($this);
}

$js = new JsExpression(<<<JS
    var checkedNodes = [];

    function checkUncheckNode(node, checked) {
        var path = node.dataAttr.path;
        
        if(!checked) {
            var node = checkedNodes.indexOf(path);
            checkedNodes.splice(node,1);
        } else {
            checkedNodes.push(path);
        }
        
        var dataString = JSON.stringify(checkedNodes);
        
        jQuery('#nodes').val(dataString);
    }
JS
);

$js2 = <<<JS
    $('#button-confirm').click(function(){
        $('.loading').show();
    });
JS;

$this->registerJs($js2);
$this->registerJs($js, \yii\web\View::POS_HEAD);

echo '<div class="loading" id="loader" hidden></div>';

/**
 * Start Form
 */
ActiveForm::begin([
    'action' => 'build-platform'
]);

/**
 * HIDDEN INPUT WITH DATA
 */
echo Html::hiddenInput(
    'nodes', 
    null, 
    [
        'id' => 'nodes',
        'type' => 'hidden',
    ]
);

if (isset($communityId) && $communityId) {
    echo Html::hiddenInput(
        'communityId', 
        $communityId, 
        [
            'id' => 'communityId',
            'type' => 'hidden',
        ]
    );
}

/**
 * HIDDEN INPUT WITH ITEM
 */
echo Html::hiddenInput('item', $item, ['type' => 'hidden',]);

//Disable the nome input if it for community
if (!isset($communityId) || !$communityId) {
    /**
     * Choose the first level community name
     */
    echo Html::tag('p', AmosDocumenti::t('amosdocumenti', '#choose_nodes_description'));
    echo Html::tag('div',
        Html::label(AmosDocumenti::t('amosdocumenti', '#choose_nodes_name_label')) .
        Html::input(
            'text', 
            'name', 
            $hashFile, 
            ['class' => 'form-control']
        ), 
        ['class' => 'form-group col-lg-6 col-xs-12 nop']
    );
    echo Html::tag('div', null, ['class' => 'clearfix']);
    echo '<hr/>';
}

echo Html::tag('h2', AmosDocumenti::t('amosdocumenti', '#choose_nodes_title_2'));
echo Html::tag('p', AmosDocumenti::t('amosdocumenti', '#choose_nodes_description_2'));

/**
 * TREE VIEW
 */
$onSelect = new JsExpression(<<<JS
function(event, item) {
    checkUncheckNode(item,true);
}
JS
);
$onUnselect = new JsExpression(<<<JS
function(event, item) {
    checkUncheckNode(item,false);
}
JS
);

try {
    echo TreeView::widget([
        'data' => [$dirTree],
        'size' => TreeView::SIZE_SMALL,
        'header' => AmosDocumenti::t('amosdocumenti', '#choose_nodes_tree'),
        'clientOptions' => [
            'name' => 'trees',
            'onNodeChecked' => $onSelect,
            'onNodeUnchecked' => $onUnselect,
            'highlightSelected' => false,
            'showCheckbox' => true,
            'selectedBackColor' => 'rgb(40, 153, 57)',
            'borderColor' => '#fff',
        ],
    ]);
} catch (Exception $e) {
    pr($e->getMessage(),'ERROR');
}
/**
 * END TREE VIEW
 */

echo Button::widget([
    'label' => 'Conferma',
    'options' => [
        'id' => 'button-confirm',
        'type' => 'submit',
        'class' => 'btn btn-navigation-primary pull-right'
    ],
]);

ActiveForm::end();
