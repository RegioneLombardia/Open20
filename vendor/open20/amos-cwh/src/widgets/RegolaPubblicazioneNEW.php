<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\widgets
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;

use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\cwh\utility\CwhUtil;
use kartik\select2\Select2;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use open20\amos\cwh\models\CwhNodi;

/**
 * Class RegolaPubblicazioneNEW
 * @package open20\amos\cwh\widgets
 */
class RegolaPubblicazioneNEW extends Widget
{
    public $moduleCwh;

    protected
        $data = [],
        $default = null,
        $form = null,           // @var \yii\widgets\ActiveForm $form
        $model = null,          // @var \yii\db\ActiveRecord $model
        $nameField = null,      // @var string

        $scope,
        $scopeFilter;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // if we are working under a specific network scope (eg. community dashboard)
        $this->scope = $this->moduleCwh->getCwhScope();
        $this->scopeFilter = (empty($this->scope) ? false : true);

        $regolePubblicazione = CwhUtil::getPublicationRulesQuery()
            ->andWhere(['not', ['id' => 5]])
            ->all();

        // if working in a network scope only rules based on the network membership are available
        if ($this->scopeFilter) {
            $this->setDefault($regolePubblicazione[0]);
        }

        $this->setData($regolePubblicazione);

        if (!isset($this->nameField)) {
            // $refClass = new \ReflectionClass(get_class($this->getModel()));
            $refClass = new \ReflectionClass(get_class($this->model));
            $this->setNameField($refClass->getShortName());
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        // $model = $this->getModel();
        if ($this->model instanceof ModelLabelsInterface) {
            $labelSuffix = ' ' . $this->model->getGrammar()->getArticleSingular() . ' ' . $this->model->getGrammar()->getModelSingularLabel();
        } else {
            $labelSuffix = ' ' . AmosCwh::t('amoscwh', 'il contenuto');
        }

        $pluginName = '';

        // if we are working under a specific network scope (eg. community dashboard)
        // $scopeFilter = (empty($scope) ? false : true);

        $data = ArrayHelper::map($this->getData(), 'id', 'nome');

        $ruleOneEnabled = array_key_exists(CwhRegolePubblicazione::ALL_USERS, $data);
        $ruleTwoEnabled = array_key_exists(CwhRegolePubblicazione::ALL_USERS_WITH_TAGS, $data);
        $ruleThreeEnabled = array_key_exists(CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS, $data);
        $ruleFourEnabled = array_key_exists(CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS, $data);

        if ($ruleThreeEnabled) {
            $data[CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS] = AmosCwh::t('amoscwh', 'Tutti gli utenti');
        }

        if ($ruleFourEnabled) {
            $data[CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS] = AmosCwh::t('amoscwh', 'Solo gli utenti per aree di interesse');
        }

        $publicationRules = [];

        if (!$this->scopeFilter && empty($this->model->destinatari)) {
            $enabledRules = array_keys($data);
            foreach ($enabledRules as $enabledRule) {
                if ($enabledRule <= CwhRegolePubblicazione::ALL_USERS_WITH_TAGS) {
                    $publicationRules[$enabledRule] = $data[$enabledRule];
                }
            }
            $regolaPubblicazioneDefault = $ruleOneEnabled ? 1 : 2;
        } else {
            $publicationRules = array_unique($data);
            $regolaPubblicazioneDefault = $ruleThreeEnabled ? 1 : 2;
        }

        $rules = [
            1 => AmosCwh::t('amoscwh', 'Tutti gli utenti'),
        ];
        if ($ruleTwoEnabled || $ruleFourEnabled) {
            $rules[2] = AmosCwh::t('amoscwh', 'Solo gli utenti per aree di interesse');
        }

        if (count($this->getData()) > 1) {
            $js = <<<JS
                
                (function($) {
                    var origAppend = $.fn.append;
                
                    $.fn.append = function () {
                        return origAppend.apply(this, arguments).trigger("append");
                    };
                })(jQuery);
                    
                    var opt = $("#cwh-regola_pubblicazione").children("option[value=1]");
                    var saveOpt = $(opt).clone();
                    $($(".field-cwh-destinatari").find(".select2-selection__rendered")[0]).on('append', function (e) {
                        if($(".field-cwh-destinatari").find(".select2-selection__choice").length > 0){
                            e.preventDefault();
                            $("#cwh-regola_pubblicazione").append(saveOpt);
                            $("#cwh-regola_pubblicazione").select2("destroy");
                            $("#cwh-regola_pubblicazione").select2(select2_3e79cec5);
                        } else {
                            var opt = $("#cwh-regola_pubblicazione").children("option[value=1]");
                            $(opt).remove();
                            $("#cwh-regola_pubblicazione").select2("destroy");
                            $("#cwh-regola_pubblicazione").select2(select2_3e79cec5);
                            $("#cwh-regola_pubblicazione").val("2").trigger("change");
                        }
                    });

JS;

            if (!$ruleOneEnabled) {
                $this->view->registerJs($js);
            }

            if (!empty($this->model->destinatari) && !$this->scopeFilter) {
                $this->view->registerJs(<<<JS
                 var optVal1 = $("#cwh-regola_pubblicazione").children("option[value=1]");
                    
                    if($(".field-cwh-destinatari").find(".select2-selection__choice").length <= 0){
                        if($("input[id$=\"-regola_pubblicazione\"]").val() != undefined && $("input[id$=\"-regola_pubblicazione\"]").val() != 1 && $("input[id$=\"-regola_pubblicazione\"]").val() != 3) {
                            $(optVal1).remove();
                        }
                    }
JS
                );
            }

            if (!$this->scopeFilter
                && !$ruleOneEnabled
                && !in_array(
                    $this->model->regola_pubblicazione,
                    [
                        CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS,
                        CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS
                    ]
                )
            ) {
                $this->view->registerJs(<<<JS
                    var optV1 = $("#cwh-regola_pubblicazione").children("option[value=1]");
                    if($(".field-cwh-destinatari").find(".select2-selection__choice").length <= 0){
                        $(optV1).remove();
                    }
JS
                );
            }

            if ($this->model->regola_pubblicazione == 3) {
                $this->model->regola_pubblicazione = 1;
            }

            if ($this->model->regola_pubblicazione == 4) {
                $this->model->regola_pubblicazione = 2;
            }

            $destinatariFromScope = false;
            if (!$this->getModel()->isNewRecord) {
                foreach ($this->getModel()->destinatari as $cwhNodiId) {
                    $cwhNodo = CwhNodi::findOne(['id' => $cwhNodiId, 'cwh_config_id' => 3]);
                    if (!is_null($cwhNodo)) {
                        $destinatariFromScope = true;
                        break;
                    }
                }
            }

            $widget = '
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">' . Html::label(AmosCwh::t('amoscwh', 'Chi può visualizzare ') . $labelSuffix, null, ['class' => 'control-label']) . '</div>
                        <div class="col-xs-12">' .
                Select2::widget([
                        'name' => $this->getNameField() . '[regola_pubblicazione]',
                        'value' => $this->model->regola_pubblicazione ? $this->model->regola_pubblicazione : $regolaPubblicazioneDefault,
                        'data' => $rules,
                        'options' => [
                            'placeholder' => AmosCwh::t('amoscwh', '#3col_recipients_placeholder'),
                            'name' => $this->getNameField() . '[regola_pubblicazione]',
                            'id' => 'cwh-regola_pubblicazione',
                            'value' => $this->getDefault(),
                        ],
                        'disabled' => $destinatariFromScope && !$this->scopeFilter
                    ]
                ) . '
                        </div>
                    </div>' .
                //COMMENTATO HIDDEN INPUT PER FIX
//                    $this->getForm()->field($this->model, 'regola_pubblicazione')->hiddenInput([
//                    'value' => $this->model->regola_pubblicazione ? $this->model->regola_pubblicazione : $regolaPubblicazioneDefault,])->label(false) .
                '</div>';
        } else {
            $regolaDiPubblicazioneField = $this->getForm()->field($this->model,
                'regola_pubblicazione')->label(AmosCwh::t('amoscwh', 'Chi può visualizzare ') . $labelSuffix); // TODO traduzione corretta
            $RegolaDiPubblicazione = $this->getData()[0];

            $regolaDiPubblicazioneField->template = "
                <div class=\"row\">
                    <div class=\"col-xs-6\">{label}</div>
                    <div class=\"col-xs-6\"> <span class=\"tooltip-field pull-right\"> {hint} </span> <span class=\"tooltip-error-field pull-right\"> {error} </span> </div>
                    <div class=\"col-xs-12\"><strong>" . $RegolaDiPubblicazione['nome'] . "</strong>{input}</div>
                </div>";

            $widget = $regolaDiPubblicazioneField->hiddenInput(['value' => $RegolaDiPubblicazione['id'], 'id' => 'cwh-regola_pubblicazione',]);
        }

        return $widget;
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

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
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

    /**
     * @return null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param null $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }
}
