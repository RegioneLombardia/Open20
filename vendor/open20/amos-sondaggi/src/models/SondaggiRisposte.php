<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models;
use open20\amos\attachments\behaviors\FileBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class SondaggiRisposte
 * This is the model class for table "sondaggi_risposte".
 * @package open20\amos\sondaggi\models
 */
class SondaggiRisposte extends \open20\amos\sondaggi\models\base\SondaggiRisposte
{
    public $byBassRuleCwh = true;

    public $attachment;
    public $attachment_multiple;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'risposta_libera'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        $i = 1;
        if($this->sondaggi_domande_id == 11){
            $i = 0;
        }
        return ArrayHelper::merge(parent::rules(), [
            //[['regola_pubblicazione', 'destinatari', 'validatori'], 'safe'],
            [['domanda_'.$this->sondaggi_domande_id], 'file', 'maxFiles' => $i],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
            ]);
    }
}
