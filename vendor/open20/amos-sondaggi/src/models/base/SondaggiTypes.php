<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models\base
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models\base;

use open20\amos\sondaggi\AmosSondaggi;

class SondaggiTypes extends \yii\base\BaseObject
{
    private $sondaggi_types = [];


    const SONDAGGI_TYPE_STANDARD = '1';
    const SONDAGGI_TYPE_LIVE = '2';

    public function init()
    {
        parent::init();
        $this->sondaggi_types = self::getLabels();

    }


    public function getTypes()
    {
        return $this->sondaggi_types;
    }

    /**
     *
     * @return string
     */
    public static function getStandardType()
    {
        return self::SONDAGGI_TYPE_STANDARD;
    }

    /**
     *
     * @return string
     */
    public static function getLiveType()
    {
        return self::SONDAGGI_TYPE_LIVE;
    }

    /**
     * @return array
     */
    public static function getLabels()
    {
        return [
            self::SONDAGGI_TYPE_STANDARD => AmosSondaggi::t('amossondaggi', 'Completo'),
            self::SONDAGGI_TYPE_LIVE => AmosSondaggi::t('amossondaggi', 'Live')
        ];
    }

    /**
     * @param $type
     * @return string
     */
    public static function getDescription($type)
    {
        if ($type == self::SONDAGGI_TYPE_STANDARD) {
            return AmosSondaggi::t('amossondaggi', 'sondaggio completo con tutte le tipologie di risposta');
        }
        if ($type == self::SONDAGGI_TYPE_LIVE) {
            return AmosSondaggi::t('amossondaggi', 'sondaggio live che prevede una sola domanda con risposte a insieme chiuso');
        }
        return '';
    }
}
