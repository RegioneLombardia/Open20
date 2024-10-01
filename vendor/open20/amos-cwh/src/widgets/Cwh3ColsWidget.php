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

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\utility\CwhUtil;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class Cwh3ColsWidget
 * @package open20\amos\cwh\widgets
 */
class Cwh3ColsWidget extends Widget
{
    public
        $layout = "<div class=\"col-xs-12\">{validatori}</div><div class=\"col-xs-12\">{previewSign}</div><div class=\"col-xs-12\">{regolaPubblicazione}</div><div class=\"col-xs-12\">{destinatari}</div>",
        $renderCols = true,

        $moduleCwh;

    protected
        $regolaPubblicazione = [],
        $validatori = [],
        $destinatari = [],
        $form = null,         // \yii\widgets\ActiveForm $form
        $model = null,         // @var \yii\db\ActiveRecord $model
        $baseConfigRules = [],
        $enableIgnoreNotifyFromEditorialStaff = false;

    /**
     * public $validatoriEnabled = true;
     * public $destinatariEnabled = true;
     * public $pubblicazioneEnabled = true;
     */
    public function init()
    {
        $this->baseConfigRules = [
            'form' => $this->form,
            'model' => $this->model,
            'moduleCwh' => $this->moduleCwh
        ];

        // DA QUI A... SERVE A NULLA SEMBRA...
        $regolaPubblicazione = "{regolaPubblicazione}";
        $destinatari = "{destinatari}";
        $validatori = "{validatori}";
        $recipientsCheck = "{recipientsCheck}";

        if (!$this->moduleCwh->validatoriEnabled) {
            $validatori = '';
        }

        if (!$this->moduleCwh->destinatariEnabled) {
            $destinatari = '';
            $recipientsCheck = '';
        }

        if (!$this->moduleCwh->regolaPubblicazioneEnabled) {
            $regolaPubblicazione = '';
            $recipientsCheck = '';
        }

        if ($this->moduleCwh->enableIgnoreNotifyFromEditorialStaff) {
            $this->enableIgnoreNotifyFromEditorialStaff = $this->moduleCwh->enableIgnoreNotifyFromEditorialStaff;
        }
        // A QUI... INTANTO LO LASCIAMO TBD capire a cosa e se serve

        parent:: init();
    }

    /**
     * @return string|string[]|null
     */
    public function run()
    {
        $layoutToRender = '';

        if ($this->renderCols) {
            $layoutToRender = $this->render('layouts/3cols', ['enableIgnoreNotifyFromEditorialStaff' => $this->enableIgnoreNotifyFromEditorialStaff]);
        } else {
            $layoutToRender = $this->render('layouts/check-recipients');
        }

        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $layoutToRender);

        return $content;
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

    /**
     * @param $name
     * @return bool|string
     * @throws \yii\base\InvalidConfigException
     *
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
            case '{previewSign}':
                return $this->renderPreviewSign();
            case '{ignore_notify_from_editorial_staff}':
                return $this->renderIgnoreNotifyFromEditorialStaff();
            default:
                return false;
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function renderIgnoreNotifyFromEditorialStaff()
    {
        if (\Yii::$app->user->can('IGNORE_NOTIFY_EDITORIAL_STAFF')) {
            /** @var  $pubblicazione CwhPubblicazioni */
            $pubblicazione = CwhUtil::getCwhPubblicazione($this->model);
            $value = false;
            if ($pubblicazione) {
                $value = $pubblicazione->ignore_notify_editorial_staff;
            }
            $hiddenInput = Html::hiddenInput('enabled_ignore_notify_editorial', true);
            $checkBox = Html::checkbox('ignore_notify_from_editorial_staff', $value, ['label' => AmosCwh::t('amoscwh', 'Abilita notifica a tutti gli utenti di {appname}', [
                'appname' => \Yii::$app->name
            ])]);
            if (\Yii::$app->user->can('ADMIN')) {
                $checkBox .= ' ' . AmosIcons::show('info', [
                        'title' => AmosCwh::t('amoscwh', 'Selezionando questa opzione verrà ignorata la scelta utente sulla ricezione delle notizie di interesse generale'),
                        'data-toggle' => 'tooltip'
                    ]);
            }
            return Html::tag('div', $hiddenInput . $checkBox , ['class' => '', 'id' => 'ignore-notify-editoria-staff-id']);
        }
        $this->enableIgnoreNotifyFromEditorialStaff = false;
        return '';
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function renderRegolaPubblicazione()
    {
        return RegolaPubblicazioneNEW::widget(
            ArrayHelper::merge(
                $this->baseConfigRules,
                $this->getRegolaPubblicazione()
            )
        );
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

    /**
     * @return string
     * @throws \Exception
     */
    protected function renderEditori()
    {
        return DestinatariNEW::widget(
            ArrayHelper::merge(
                $this->baseConfigRules,
                $this->getDestinatari()
            )
        );
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

    /**
     * @return string
     * @throws \Exception
     */
    protected function renderValidatori()
    {
        return ValidatoriNEW::widget(
            ArrayHelper::merge(
                $this->baseConfigRules,
                $this->getValidatori()
            )
        );
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

    /**
     * @return string
     * @throws \Exception
     */
    protected function renderRecipientsCheck()
    {
        return RecipientsCheckNEW::widget($this->baseConfigRules);
    }

    /**
     * @return string
     */
    protected function renderPreviewSign()
    {
        $profile = UserProfile::findOne(['user_id' => $this->model->created_by]);

        return $this->render(
            'preview_sign',
            [
                'model' => $this->model,
                'profile' => $profile
            ]
        );
    }
}
