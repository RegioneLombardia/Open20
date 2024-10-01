<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

namespace open20\design\components\bootstrapitalia;

use yii\base\InvalidCallException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm as YiiActiveForm;
use yii\widgets\ActiveFormAsset;

/**
 * Description of ActiveForm
 *
 */
class ActiveForm extends YiiActiveForm
{

    public function init()
    {
        parent::init();
        $this->fieldClass = ActiveField::className();
    }

    public function run()
    {
        if (!empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        $content                     = ob_get_clean();
        $this->options['novalidate'] = 'novalidate';
        $html                        = Html::beginForm($this->action,
                $this->method, $this->options);
        $html                        .= $content;

        if ($this->enableClientScript) {
            $this->registerClientScript();
        }

        $html .= Html::endForm();
        return $html;
    }

    /**
     * This registers the necessary JavaScript code.
     * @since 2.0.12
     */
    public function registerClientScript()
    {
        $id         = $this->options['id'];
        $options    = Json::htmlEncode($this->getClientOptions());
        $attributes = Json::htmlEncode($this->attributes);
        $view       = $this->getView();
        ActiveFormAsset::register($view);
        $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        // $view->registerJs("(function() {
        //     'use strict';
        //     window.addEventListener('load', function() {
        //       var forms = document.getElementsByClassName('needs-validation');
        //       var validation = Array.prototype.filter.call(forms, function(form) {
        //         form.addEventListener('submit', function(event) {
        //           if (form.checkValidity() === false) {
        //             event.preventDefault();
        //             event.stopPropagation();
        //           }
        //         }, false);
        //         form.classList.add('was-validated');
        //       });
        //     }, false);
        //   })();");
    }
}