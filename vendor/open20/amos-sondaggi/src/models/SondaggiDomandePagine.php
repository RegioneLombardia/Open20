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
 * Class SondaggiDomandePagine
 * This is the model class for table "sondaggi_domande_pagine".
 * @package open20\amos\sondaggi\models
 */
class SondaggiDomandePagine extends \open20\amos\sondaggi\models\base\SondaggiDomandePagine
{
    public $byBassRuleCwh = true;
    public $file;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'titolo'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['file'], 'file']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->file = $this->getFile()->one();
    }

    /**
     * Getter for $this->file;
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOneFile('file');
    }

    public function getAvatarUrl($dimension = 'original')
    {
        $url = '/img/img_default.jpg';
        if ($this->file) {
            $url = $this->file->getUrl($dimension);
        }
        return $url;
    }

    /**
     * Ordina le pagine in funzione di quella appena salvata
     * @param string $tipo Tipologia di ordinamento che può essere 'prima' o 'dopo'
     * @param integer $rif Id della pagina prima o dopo la quale inserire la nuova
     */
    public function setOrdinamento($tipo = 'dopo') {
        if ($tipo == 'dopo') {
            $pageDopo = SondaggiDomandePagine::find()->andWhere(['>', 'ordinamento', $this->ordinamento])->orderBy('ordinamento')->one();
            if (!empty($pageDopo)) {
                $ordDopo = $pageDopo->ordinamento;
                /* Checking whether this new position will break conditioned questions; in this case, returns an error */
                $questions = $this->sondaggiDomandes;
                $afterQuestions = $pageDopo->sondaggiDomandes;
                if (!empty($questions) && !empty($afterQuestions)) {
                    $questionIds = \yii\helpers\ArrayHelper::map($questions, 'id', 'domanda');
                    foreach($afterQuestions as $afterQuestion) {
                        if (!empty($afterQuestion->domanda_condizionata_testo_libero) &&
                            array_key_exists($afterQuestion->domanda_condizionata_testo_libero, $questionIds)) {
                            return false;
                        }
                        $conditioned = $afterQuestion->getSondaggiRispostePreCondMm()->with('sondaggiRispostePredefinite')->all();
                        if (!empty($conditioned)) {
                            foreach($conditioned as $item) {
                                if (array_key_exists($item->sondaggiRispostePredefinite->sondaggi_domande_id, $questionIds)) {
                                    return false;
                                }
                            }
                        }
                    }
                }
                $pagineDopo = $this->getAllPoolPages()->andWhere(['>', 'ordinamento', $ordDopo])->andWhere([
                    '!=', 'id', $this->id]);
                $this->ordinamento = $ordDopo + 1;
                $this->save();
                foreach ($pagineDopo->all() as $pagina) {
                    $aggiorna              = SondaggiDomandePagine::findOne(['id' => $pagina['id']]);
                    $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                    $aggiorna->save();
                }
                return true;
            } else {
                $this->ordinamento = 1;
                $this->save();
                return true;
            }
        } else {
            $pagePrima = SondaggiDomandePagine::find()->andWhere(['<', 'ordinamento', $this->ordinamento])->orderBy(['ordinamento' => SORT_DESC])->one();
            if (!empty($pagePrima)) {
                $ordPrima = $pagePrima->ordinamento;
                /* Checking whether this new position will break conditioned questions; in this case, returns an error */
                $questions = $this->sondaggiDomandes;
                $beforeQuestions = $pagePrima->sondaggiDomandes;
                if (!empty($questions) && !empty($beforeQuestions)) {
                    $questionIds = \yii\helpers\ArrayHelper::map($beforeQuestions, 'id', 'domanda');
                    foreach($questions as $question) {
                        if (!empty($question->domanda_condizionata_testo_libero) &&
                            array_key_exists($question->domanda_condizionata_testo_libero, $questionIds)) {
                            return false;
                        }
                        $conditioned = $question->getSondaggiRispostePreCondMm()->with('sondaggiRispostePredefinite')->all();
                        if (!empty($conditioned)) {
                            foreach($conditioned as $item) {
                                if (array_key_exists($item->sondaggiRispostePredefinite->sondaggi_domande_id, $questionIds)) {
                                    return false;
                                }
                            }
                        }
                    }
                }
                $paginePrima = $this->getAllPoolPages()->andWhere(['<', 'ordinamento', $ordPrima])->andWhere([
                    '!=', 'id', $this->id]);
                $this->ordinamento = $ordPrima - 1;
                $this->save();
                foreach ($paginePrima->all() as $pagina) {
                    $aggiorna              = SondaggiDomandePagine::findOne(['id' => $pagina['id']]);
                    $aggiorna->ordinamento = ($aggiorna->ordinamento - 1);
                    $aggiorna->save();
                }
                return true;
            } else {
                $this->ordinamento = 1;
                $this->save();
                return true;
            }
        }
    }

    public function getAllPoolPages() {
        return SondaggiDomandePagine::find()->andWhere(['sondaggi_id' => $this->sondaggi_id]);
    }
}
