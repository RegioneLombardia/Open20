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

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use open20\amos\cwh\AmosCwh;


class CwhWidget extends Widget
{
    public $layout = "<div class=\"col-xs-12\">{validatori}</div><div class=\"col-xs-12\">{regolaPubblicazione}</div><div class=\"col-xs-12\">{destinatari}</div>";
    protected $regolaPubblicazione = [];
    protected $validatori = [];
    protected $destinatari = [];
    /**
     * @var \yii\widgets\ActiveForm $form
     */
    protected $form = null;


    //protected $layout = "<div class=\"col-xs-12\">{regolaPubblicazione}</div><div class=\"col-xs-12\">{destinatari}</div><div class=\"col-xs-12\">{validatori}</div>";
    /**
     * @var \yii\db\ActiveRecord $model
     */
    protected $model = null;

    public function init()
    {

        /**
         *
         *
         * public $validatoriEnabled = true;
         * public $destinatariEnabled = true;
         * public $pubblicazioneEnabled = true;
         */

        $regolaPubblicazione = "{regolaPubblicazione}";
        $destinatari = "{destinatari}";
        $validatori = "{validatori}";
        $recipientsCheck = "{recipientsCheck}";
        if (!\Yii::$app->getModule('cwh')->validatoriEnabled) {
            $validatori = '';
        }

        if (!\Yii::$app->getModule('cwh')->destinatariEnabled) {
            $destinatari = '';
            $recipientsCheck = '';
        }

        if (!\Yii::$app->getModule('cwh')->regolaPubblicazioneEnabled) {
            $regolaPubblicazione = '';
            $recipientsCheck = '';
        }

        $this->layout = "<div class='row m-b-25'>
                            <div class=\"col-xs-12\">$validatori</div>
                         </div>
                         <div class='row'>
                            <div class='col-sm-6 col-xs-12'>$regolaPubblicazione</div>
                            <div class=\"col-sm-6 col-xs-12\">$destinatari</div>
                         </div>
                         <div class='row'>
                            <div class='col-xs-12'>$recipientsCheck</div>
                         </div>";

        parent:: init();
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @param null $destinatari
     */
    public function setEditori($destinatari)
    {
        $this->destinatari = $destinatari;
    }

    public function run()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->layout);

        return $content;
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{regolaPubblicazione}':
                return $this->renderRegolaPubblicazione();
            case '{destinatari}':
                return $this->renderEditori();
            case '{validatori}':
                return $this->renderValidatori();
            case '{recipientsCheck}':
                return $this->renderRecipientsCheck();
            default:
                return false;
        }
    }

    protected function renderRegolaPubblicazione()
    {

        $configRegolaPubblicazione = [
            'form' => $this->getForm(),
            'model' => $this->getModel(),
        ];

        $configRegolaPubblicazione = ArrayHelper::merge($configRegolaPubblicazione, $this->getRegolaPubblicazione());

        return RegolaPubblicazione::widget($configRegolaPubblicazione);
    }

    /**
     * @return null
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param null $form
     */
    public function setForm($form)
    {
        $this->form = $form;
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
     * @return null
     */
    public function getRegolaPubblicazione()
    {
        return $this->regolaPubblicazione;
    }

    /**
     * @param null $regolaPubblicazione
     */
    public function setRegolaPubblicazione($regolaPubblicazione)
    {
        $this->regolaPubblicazione = $regolaPubblicazione;
    }

    protected function renderEditori()
    {
        $configEditori = [
            'form' => $this->getForm(),
            'model' => $this->getModel(),
        ];

        $configEditori = ArrayHelper::merge($configEditori, $this->getDestinatari());

        return Destinatari::widget($configEditori);
    }

    /**
     * @return null
     */
    public function getDestinatari()
    {
        return $this->destinatari;
    }

    /**
     * @param array $destinatari
     */
    public function setDestinatari($destinatari)
    {
        $this->destinatari = $destinatari;
    }

    protected function renderValidatori()
    {
        $configValidatori = [
            'form' => $this->getForm(),
            'model' => $this->getModel(),
        ];

        $configValidatori = ArrayHelper::merge($configValidatori, $this->getValidatori());

        return Validatori::widget($configValidatori);
    }

    /**
     * @return null
     */
    public function getValidatori()
    {
        return $this->validatori;
    }

    /**
     * @param null $validatori
     */
    public function setValidatori($validatori)
    {
        $this->validatori = $validatori;
    }

    protected function renderRecipientsCheck()
    {
        $configRecipientsCheck = [
            'form' => $this->getForm(),
            'model' => $this->getModel(),
        ];
        return RecipientsCheck::widget($configRecipientsCheck);
    }
}