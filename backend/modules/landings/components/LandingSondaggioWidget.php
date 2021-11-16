<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\landings\components
 */

namespace backend\modules\landings\components;


use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\design\utility\DateUtility;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use open20\amos\core\helpers\Html;
use yii\helpers\VarDumper;

class LandingSondaggioWidget extends Widget
{
    /* @var Sondaggi $model */
    var $model= null;

    public function init()
    {

        if (is_null($this->model)) {
            throw new InvalidConfigException('You must set model for this widget');
        }

        if (!($this->model instanceof Sondaggi)) {
            throw new InvalidConfigException('Model for widget is of type: ' . Sondaggi::className());
        }

        parent::init();
    }


    public function run()
    {
        $url = \yii\helpers\Url::current();
        $anchor = '';
        $cookieValues = $this->model->getFrontendCookie($this->model->id);

//        VarDumper::dump($cookieValues,3,true);
        $idSessione = null;
        if (isset($cookieValues['idSessione']) && !empty($cookieValues['idSessione'])) {
            $idSessione = $cookieValues['idSessione'];
        }

        $noFillableMessage = null;
        $poolUrl = null;
        if ($this->model->isCompilable()) {
            $poolUrl = \Yii::$app->urlManager->createUrl([
                '/sondaggi/frontend/compila',
                'id' => $this->model->id,
                'idSessione' => $idSessione,
            ]);
        } else {
            $noFillableMessage = 'Sondaggio non compilabile';

            // controllo se non è compilabile perchè l'utente ha terminato la compilazione
            $cookieValues = $this->model->getFrontendCookie($this->model->id);

            if (!empty($this->model->publish_date) && (new \DateTime($this->model->publish_date)) > (new \DateTime())) {
                $noFillableMessage = 'Sondaggio compilabile a partire dalla data: ' . \Yii::$app->formatter->asDate($this->model->publish_date);
            } else if (isset($cookieValues['idSessione']) && !empty($cookieValues['idSessione'])) {
                $idSessione = $cookieValues['idSessione'];
                $srs = SondaggiRisposteSessioni::findOne(['id' => $idSessione]);
                if (!empty($srs) && $srs->completato) {
                    $noFillableMessage = 'Sondaggio completato '; // . \Yii::$app->formatter->asDatetime($srs->end_date);
                }
            }
        }

        $dateStart = $this->model->publish_date;
        $dayStart   = DateUtility::getDate($dateStart, 'php:d');
        $monthStart = DateUtility::getDate($dateStart, 'php:M');
        $yearStart  = DateUtility::getDate($dateStart, 'php:Y');
        $dateStart  = DateUtility::getDate($dateStart);

        $dateEnd = $this->model->close_date;
        $hideDateEnd = empty($this->model->close_date);
        if (isset($dateEnd)) {
            $dayEnd   = DateUtility::getDate($dateEnd, 'php:d');
            $monthEnd = DateUtility::getDate($dateEnd, 'php:M');
            $yearEnd  = DateUtility::getDate($dateEnd, 'php:Y');
            $dateEnd  = DateUtility::getDate($dateEnd);
        }

        return $this->render('landing_sondaggio_widget', [
            'model' => $this->model,
            'poolUrl' => $poolUrl,
            'noFillableMessage' => $noFillableMessage,
            // dati sulla data inizio sondaggio
            'dayStart' => $dayStart,
            'monthStart' => $monthStart,
            'yearStart' => $yearStart,
            'dateStart' => $dateStart,
            // dati sulla data termine
            'hideDateEnd' => $hideDateEnd,
            'dayEnd' => $dayEnd,
            'monthEnd' => $monthEnd,
            'yearEnd' => $yearEnd,
            'dateEnd' => $dateEnd,

        ]);
    }

}