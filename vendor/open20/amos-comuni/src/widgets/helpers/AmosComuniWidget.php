<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni\widgets\helpers
 * @category   CategoryName
 */

namespace open20\amos\comuni\widgets\helpers;

use open20\amos\comuni\AmosComuni;
use open20\amos\comuni\assets\ComuniAsset;
use open20\amos\core\record\Record;

use yii\base\Exception;
use yii\base\Widget;

/**
 * Class AmosComuniWidget
 *
 * <p><b>Widget che permette la creazione delle tendine dei dati residenziali: Nazione, Provincia, Comune, Cap</b></p>
 *
 * Ogni tendina è configurabile con le configurazioni previste dai widget usato:
 * - Nazione => Select2
 * - Provincia => Select2
 * - Comune => DepDrop
 * - CAP => DepDrop
 *
 * campo fondamentale 'attribute' dove indicare il nome del field del model da utilizzare
 *
 * <p>esempio di configurazione base</p>
 *
 * ```php
 * echo \open20\amos\comuni\widgets\helpers\AmosComuniWidget::widget([
 *   'form' => $form,
 *   'model' => $model,
 *   'nazioneConfig' => [
 *       'attribute' => 'nazione_id',
 *   ],
 *   'provinciaConfig' => [
 *       'attribute' => 'istat_province_id',
 *   ],
 *   'comuneConfig' => [
 *       'attribute' => 'istat_comuni_id',
 *   ],
 *   'capConfig' => [
 *       'attribute' => 'cap_id',
 *   ]
 *   ]);
 * ```
 * @package open20\amos\comuni\widgets\helpers
 */
class AmosComuniWidget extends Widget
{
    /**
     * 
     * @var type
     */
    public $form;

    /**
     * 
     * @var type
     */
    public $model;
    
    /**
     * 
     * @var type
     */
    public $nazioneConfig;
    
    /**
     * 
     * @var type
     */
    public $provinciaConfig;
    
    /**
     * 
     * @var type
     */
    public $comuneConfig;
    
    /**
     * 
     * @var type
     */
    public $capConfig;
    
    /**
     * 
     * @var type
     */
    protected $params;
    
    /**
     * 
     * @var type
     */
    public $elementByRow = 4;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // check on form object
        if (!isset($this->form)) {
            throw new Exception(AmosComuni::t('amoscomuni', '#no_form_object'));
        }
        
        // check on model object
        if (!isset($this->model)) {
            throw new Exception(AmosComuni::t('amoscomuni', '#no_model_object'));
        }

        // check the correct chain Provincia/Comune
        if (isset($this->provinciaConfig) && !isset($this->comuneConfig)) {
            throw new Exception(AmosComuni::t('amoscomuni', '#no_province_and_city'));
        }

        // check the correct chain Cap/Comune
        if ((isset($this->capConfig) && !isset($this->comuneConfig))) {
            throw new Exception(AmosComuni::t('amoscomuni', '#no_province_and_city'));
        }
        
        $nazioneAttribute = $this->nazioneConfig['attribute'];
        $provinciaAttribute = $this->provinciaConfig['attribute'];
        $comuneAttribute = $this->comuneConfig['attribute'];
        $capAttribute = $this->capConfig['attribute'];

        $nazione_id = isset($this->nazioneConfig['options']['id'])
            ? $this->nazioneConfig['options']['id']
            : $this->generateFieldId($this->model, $nazioneAttribute);
        
        $provincia_id = isset($this->provinciaConfig['options']['id'])
            ? $this->provinciaConfig['options']['id']
            : $this->generateFieldId($this->model, $provinciaAttribute);
        
        $comune_id = isset($this->comuneConfig['options']['id'])
            ? $this->comuneConfig['options']['id']
            : $this->generateFieldId($this->model, $comuneAttribute);
        
        $cap_id = isset($this->capConfig['options']['id'])
            ? $this->capConfig['options']['id']
            : $this->generateFieldId($this->model, $capAttribute);

        $colMdRow = array_shift($this->getCalculatedElementByRow());

        $nazioneClass = isset($this->nazioneConfig['class'])
            ? $this->nazioneConfig['class']
            : 'col-md-' . $colMdRow;
        
        $provinciaClass = isset($this->provinciaConfig['class'])
            ? $this->provinciaConfig['class']
            : 'col-md-' . $colMdRow;
        
        $comuneClass = isset($this->comuneConfig['class'])
            ? $this->comuneConfig['class']
            : 'col-md-' . $colMdRow;

        $capClass = isset($this->capConfig['class'])
                ? $this->capConfig['class']
                : 'col-md-' . $colMdRow;
        
        $this->params = [
            'model' => $this->model,
            'form' => $this->form,
            'nazioneConfig' => $this->nazioneConfig,
            'provinciaConfig' => $this->provinciaConfig,
            'comuneConfig' => $this->comuneConfig,
            'capConfig' => $this->capConfig,
            'colMdRow' => array_shift($this->getCalculatedElementByRow()),
            
            'nazioneAttribute' => $nazioneAttribute,
            'provinciaAttribute' => $provinciaAttribute,
            'comuneAttribute' => $comuneAttribute,
            'capAttribute' => $capAttribute,
            'nazione_id' => $nazione_id,
            'provincia_id' => $provincia_id,
            'comune_id' => $comune_id,
            'cap_id' => $cap_id,
            'nazioneClass' => $nazioneClass,
            'provinciaClass' => $provinciaClass,
            'comuneClass' => $comuneClass,
            'capClass' => $capClass
        ];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        //registro il file comuni_common_js.js a tutte le varie view
        ComuniAsset::register(\Yii::$app->getView());

        $html_nazione = '';
        $html_provincia = '';
        $html_comune = '';
        $html_cap = '';

        $dimensions = $this->getCalculatedElementByRow();
        $this->params['widget'] = $this;

        if (isset($this->nazioneConfig)) {
            $this->params['colMdRow'] = array_shift($dimensions);
            $html_nazione = $this->render('nazione_view', $this->params);
        }
        if (isset($this->provinciaConfig)) {
            $this->params['colMdRow'] = array_shift($dimensions);
            $html_provincia = $this->render('provincia_view', $this->params);
            $this->params['colMdRow'] = array_shift($dimensions);
            $html_comune = $this->render('comune_view', $this->params);
        } else {
            $this->params['colMdRow'] = array_shift($dimensions);
            $html_comune = $this->render('comune_single_select_view', $this->params);
        }

        if (isset($this->capConfig)) {
            $this->params['colMdRow'] = array_shift($dimensions);
            $html_cap = $this->render('cap_view', $this->params);
        }

        $html_complete = $html_nazione . $html_provincia . $html_comune . $html_cap;

        return $html_complete;
    }

    /**
     * @param Record $model
     * @param string $fieldName
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function generateFieldId($model, $fieldName)
    {
        return $model->formName() . '-' . $fieldName . '-id';
    }

    /**
     * @return array
     */
    public function getCalculatedElementByRow()
    {
        $nElem = 1;
        $divColMd = null;
        $dimensions = [];
        if (!empty($this->nazioneConfig)) {
            $nElem++;
        }
        if (!empty($this->provinciaConfig)) {
            $nElem++;
        }
        if (!empty($this->comuneConfig)) {
            $nElem++;
        }
        if (!empty($this->capConfig)) {
            $nElem++;
        }
        $resto = $nElem % $this->elementByRow;
        
        if ($resto == $nElem || $resto == 0) {
            $div1 = bcdiv(12, $nElem);
            for ($n = $nElem; $n > 0; $n--) {
                $dimensions[] = $div1;
            }
        } else {
            $completo = $nElem - $resto;
            $div2 = bcdiv(12, $completo);
            $div3 = bcdiv(12, $resto);
            for ($b = $completo; $b > 0; $b--) {
                $dimensions[] = $div2;
            }
            for ($c = $resto; $c > 0; $c--) {
                $dimensions[] = $div3;
            }
        }
        
        return $dimensions;
    }
}
