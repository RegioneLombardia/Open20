<?php

namespace open20\prodottiservizi\utility;

use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\prodottiservizi\models\ProdottiServiziAccordion;
use open20\prodottiservizi\models\search\ProdottiServiziAccordionSearch;
use yii\bootstrap\Modal;

class ProdottiServiziUtility {

    /**
     * @return string[]
     */
    public static function getStati() {
        return [
            1 => 'Aperto',
            2 => 'Chiuso con attività in corso',
            3 => 'Archiviato'
        ];
    }

    /**
     * @return string[]
     */
    public static function getAmbiti() {
        return [
            1 => 'Imprese',
            2 => 'PA ed Enti di Ricerca',
            3 => 'Professionisti'
        ];
    }
    public static function getStatiClass() {
        return [
            1 => 'status-aperto',
            2 => 'status-chiuso',
            3 => 'status-archiviato'
        ];
    }
    /**
     * @param $id
     * @return string
     */
    public static function getAmbitoLabel($id) {
        $ambiti = self::getAmbiti();
        return $ambiti[$id];
    }

    /**
     * @param $id
     * @return string
     */
    public static function getStatoLabel($id) {
        $stati = self::getStati();
        return $stati[$id];
    }
    public static function getStatiClassLabel($id) {
        $statiClass = self::getStatiClass();
        return $statiClass[$id];
    }

    /**
     * Funzione per generare un modale relativo ai prodotti associati all'elemento da cancellare che hanno solo lui come associato (dello stesso tipo)
     * @param $model
     * @param $prodotti
     * @param $action
     * @param $messages
     * @param $options
     * @return array
     */
    public static function getModalDeleteSinglePS($model, $prodotti, $action, $messages, $options) {
        Modal::begin([
            'id' => 'modalDeleteSingle-' . $model->id,
            'header' => \Yii::t('prodottiservizi', '#headerErrorModalDelete')
        ]);
        echo Html::tag('div', \Yii::t('prodottiservizi', $messages[0])
        );
        echo Html::ul($prodotti, ['item' => function($item, $index){
            return Html::tag('li', Html::a($item->title, ['/prodotti-e-servizi/prodotti-servizi/update', 'id' => $item->id], ['target' => '_blank']));
        }]);

        echo Html::tag('div', \Yii::t('prodottiservizi', $messages[1]));
        echo Html::tag('div',
            Html::a(BaseAmosModule::tHtml('prodottiservizi', 'Roger'), null,
                ['class' => 'btn btn-navigation-primary', 'data-dismiss' => 'modal']),
            ['class' => 'pull-right m-15-0']
        );
        Modal::end();
        $options = \yii\helpers\ArrayHelper::merge($options, ['data-toggle' => 'modal', 'data-target' => '#modalDeleteSingle-' . $model->id]);
        return $options;
    }

    /**
     * Funzione per generare un modale relativo ai prodotti associati all'elemento da cancellare che hanno anche altri elementi associati (dello stesso tipo)
     * @param $model
     * @param $prodotti
     * @param $action
     * @param $message
     * @param $options
     * @return array
     */
    public static function getModalDeleteMultiplePS($model, $prodotti, $action, $message, $options) {
        Modal::begin([
            'id' => 'modalDeleteMultiple-' . $model->id,
            'header' => \Yii::t('prodottiservizi', '#headerErrorModalDelete')
        ]);
        echo Html::tag('div', \Yii::t('prodottiservizi', $message)
        );
        echo Html::ul($prodotti, ['item' => function($item, $index){
            return Html::tag('li', Html::a($item->title, ['/prodotti-e-servizi/prodotti-servizi/update', 'id' => $item->id], ['target' => '_blank']));
        }]);
        echo Html::tag('div',
            Html::a(BaseAmosModule::tHtml('amoscore', '#cancel'), null,
                ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
            . Html::a(BaseAmosModule::tHtml('amoscore', '#confirm'), [$action, 'id' => $model->id],
                ['class' => 'btn btn-navigation-primary']),
            ['class' => 'pull-right m-15-0']
        );
        Modal::end();
        $options = \yii\helpers\ArrayHelper::merge($options, ['data-toggle' => 'modal', 'data-target' => '#modalDeleteMultiple-' . $model->id]);
        return $options;
    }

    public static function addAccordionToNewProdotto($idProdotto) {
        $modelSearch = new ProdottiServiziAccordionSearch();
        $dataPerovider = $modelSearch->search();
        /** @var $model ProdottiServiziAccordion */
        foreach($dataPerovider->getModels() as $model) {
            $model->prodotto_id = $idProdotto;
            $model->save(false);
        }
    }
}