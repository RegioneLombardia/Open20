<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodotti-e-servizi
 * @category   CategoryName
 */

namespace open20\prodottiservizi;

use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\prodottiservizi\models\ProdottiServizi;
use open20\prodottiservizi\models\search\ProdottiServiziSearch;

/**
 * registry module definition class
 */
class Module extends AmosModule implements SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface {
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\prodottiservizi\controllers';
    public $newFileMode = 0666;
    public $name = 'Prodotti e servizi';
    public $materialLink = 'https://materialdesignicons.com/';
    public $maxProdottiServiziCardFinalitaNumber = 2; // Massimo numero di finalità da mostrare nelle card

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));

    }

    protected function getDefaultModels()
    {
        return [];
    }

    /**
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'prodotti-e-servizi';
    }

    public function getWidgetGraphics()
    {
        return NULL;
    }

    public function getWidgetIcons()
    {
        return [

        ];
    }

    /**
     * @return array
     */
    public function getIndexActions()
    {
        return [
            '/prodotti-e-servizi/prodotti-servizi/index',
            '/prodotti-e-servizi/prodotti-finalita/index',
            '/prodotti-e-servizi/prodotti-destinatari/index',
            '/prodotti-e-servizi/prodotti-soluzioni/index',
            '/prodotti-e-servizi/prodotti-servizi/amministra'
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'prodotti-e-servizi' => '/prodotti-e-servizi/prodotti-servizi/index',
        ];
    }

    /**
     * @return array
     */

    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'prodotti-e-servizi' => '/prodotti-e-servizi/prodotti-servizi/index',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        $names = [
            'prodotti-e-servizi' => self::t('prodottiservizi', "Prodotti e Servizi"),
            'prodotti-finalita' => self::t('prodottiservizi', "Finalità"),
            'prodotti-destinatari' => self::t('prodottiservizi', "Destinatari"),
            'prodotti-soluzioni' => self::t('prodottiservizi', "Soluzioni"),
        ];

        return $names;
    }


    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return  ProdottiServiziSearch::className();
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return ProdottiServizi::className();
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        return 'feed';
    }
}
