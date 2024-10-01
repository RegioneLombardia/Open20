<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;


use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\cwh\AmosCwh;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\web\View;


/**
 * Class RecipientsCheck
 * @package open20\amos\cwh\widgets
 */
class RecipientsCheck extends Widget
{
    /**
     * @var \yii\widgets\ActiveForm $form
     */
    protected $form = null;

    /**
     * @var \yii\db\ActiveRecord $model
     */
    protected $model = null;

    /**
     * @var string
     */
    protected $nameField = null;

    public function init()
    {
        parent::init();
        if (!isset($this->nameField)) {
            $refClass = new \ReflectionClass(get_class($this->getModel()));
            $this->setNameField($refClass->getShortName());
        }
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \yii\db\ActiveRecord $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    public function run()
    {
        $model = $this->model;
        if ($model instanceof ModelLabelsInterface) {
            $labelSuffix = ' ' . $model->getGrammar()->getArticleSingular() . ' ' . $model->getGrammar()->getModelSingularLabel();
        } else {
            $labelSuffix = ' ' . AmosCwh::t('amoscwh', 'il contenuto');
        }
        $refClass = new \ReflectionClass(get_class($model));
        $formPrefix = strtolower($refClass->getShortName());
        $className = addslashes($refClass->name);
        $js = <<<JS
        
        function drawRecipientsPopup() {
            var tags = $('#amos-tag').find('li.kv-selected');
            var tmp = [];
            $.each(tags, function( key, value ) { 
                tmp.push($(value).data('key'));
            });
            tagValues = tmp.toString(); 
            
            $.ajax({
                url: '/cwh/cwh-ajax/recipients-check',
                async: true,
                type: 'POST',
                data: {
                    validators:  $("#$formPrefix-validatori").val(),
                    publicationRule: $("#cwh-regola_pubblicazione").val(),
                    scopes: $("#cwh-destinatari").val(),
                    tags: tagValues,
                    className: "$className",
                    searchName: $("#search-recipients").val(),
                    labelSuffix: "$labelSuffix"
                },
               success: function(response) {
                   if(response) { 
                      $("#recipients-preview").html(response);
                      $("#recipientsPopup").modal('show');  
                   } else{
                       $("#recipientsPopup").modal('show'); 
                   }
               }
            });
        }
        
        $('#recipientsPopup').on("click", "#search-recipients-btn", function(e) {    
            e.preventDefault(); 
            drawRecipientsPopup();
            return false;
        });

         $('#recipientsPopup').on("keypress", "#search-recipients", function(e) {
            if(e.which == 13) {
                e.preventDefault();
                 drawRecipientsPopup();
                return false;
            }
        });

         $('#recipientsPopup').on("click", "#reset-search-recipients-btn", function(e) {    
            e.preventDefault(); 
            $("#search-recipients").val('');
            drawRecipientsPopup();
            return false;
        });
        
        $("#recipients-check").on("click", function(e) {    
            e.preventDefault(); 
            drawRecipientsPopup();
            return false;
        });
        
        $('#recipientsPopup').on("click", ".pagination li a", function(e) {
            e.preventDefault();
            var tags= $('#amos-tag').find('input.hide');
            var tagValues = ''; 
            $.each(tags, function( key, value ) { 
                if(value.value != ''){
                    if(tagValues != ''){
                        tagValues = tagValues + ',';
                    }
                    tagValues = tagValues+ value.value; 
                }
            });
            
            var data = {
                validators:  $("#$formPrefix-validatori").val(),
                publicationRule: $("#cwh-regola_pubblicazione").val(),
                scopes: $("#cwh-destinatari").val(),
                tags: tagValues,
                className: "$className",
                searchName: $("#search-recipients").val(),
                labelSuffix: "$labelSuffix"
            };
            
            $.pjax({
                type: 'POST',
                url: $(this).attr('href'),
                container: '#recipients-grid',
                replace: false,
                push: false,
                data: data
            });
            return false;
        });
        
JS;
        $this->getView()->registerJs($js, View::POS_LOAD);

        Modal::begin([
            'id' => 'recipientsPopup',
            'header' => AmosCwh::t('amoscwh', "Recipients check"),
            'size' => Modal::SIZE_LARGE
        ]);
        echo Html::tag('div','', [ 'id' => 'recipients-preview' ]);
        echo Html::tag('div',
            Html::a(AmosCwh::t('amoscwh', 'Close'), null, ['data-dismiss' => 'modal', 'class' => 'btn btn-secondary']),
            ['class' => 'pull-right', 'style' => 'margin: 15px 0']
        );
        Modal::end();
        $btn = Html::a(' ' . AmosCwh::t('amoscwh','#recipients_check_btn'),null,['id' => 'recipients-check', 'class' => 'recipients-check am am-eye']);
        return $btn;
    }

    /**
     * @return \yii\widgets\ActiveForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param \yii\widgets\ActiveForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getNameField()
    {
        return $this->nameField;
    }

    /**
     * @param string $nameField
     */
    public function setNameField($nameField)
    {
        $this->nameField = $nameField;
    }
}