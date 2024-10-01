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

use open20\amos\sondaggi\AmosSondaggi;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\sondaggi\models\SondaggiDomandeRuleMm;

/**
 * Class SondaggiDomande
 * This is the model class for table "sondaggi_domande".
 * @package open20\amos\sondaggi\models
 */
class SondaggiDomande extends \open20\amos\sondaggi\models\base\SondaggiDomande
{
    //public $regola_pubblicazione;
    //public $destinatari;
    //public $validatori;
    public $condizione_necessaria;
    public $ordine;
    public $ordina_dopo;
    public $validazione;
    public $byBassRuleCwh = true;

    /**
     * @var File[] $file
     */
    public $file;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'domanda'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
                [
                //[['regola_pubblicazione', 'destinatari', 'validatori'], 'safe'],
                [['file'], 'file', 'maxFiles' => 0],
                [['ordina_dopo'], 'integer'],
                [['is_parent'], 'boolean'],
                [['parent_id'], 'default'],
                [['condizione_necessaria', 'validazione', 'is_parent', 'parent_id', 'multi_columns'], 'safe'],
                ['ordine', 'string'],
                ['min_int_multipla', 'number', 'min' => 0],
                ['max_int_multipla', 'number', 'min' => 0],
                ['max_int_multipla', 'checkIntervalloSelezioniMultiple'],
                ['nome_classe_validazione', 'required', 'when' => function ($model) {
                        return $model->sondaggi_domande_tipologie_id == 9;
                    }, 'whenClient' => "function (attribute, value) {"
                    ."return $('#sondaggidomande-sondaggi_domande_tipologie_id').val() == 9;"
                    ."}"],//da aggiornare nel caso di modifiche alla tipologia custom
                ['modello_risposte_id', 'required', 'when' => function ($model) {
                        return $model->sondaggi_domande_tipologie_id == 14;
                    }, 'whenClient' => "function (attribute, value) {"
                    ."return $('#sondaggidomande-sondaggi_domande_tipologie_id').val() == 14;"
                    ."}"],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className(),
                'permission' => 'COMPILA_SONDAGGIO'
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        if (empty($this->file))
            $this->getFile();
        $this->multi_columns = json_decode($this->multi_columns, true);
        return true;
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->multi_columns = json_encode($this->multi_columns);
        return true;
    }

    /**
     * Getter for $this->file;
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        if (empty($this->file)) {
            $query             = $this->hasMultipleFiles('file');
            $query->multiple   = false;
            $this->file = $query->all();
        }
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function checkIntervalloSelezioniMultiple($model, $attribute)
    {
        $min = $this->min_int_multipla;
        $max = $this->max_int_multipla;
        if ($min > $max) {
            if ($max != 0) {
                $this->addError($model,
                    AmosSondaggi::t('amossondaggi',
                        'Le selezioni massime non possono essere minori delle selezioni minime, se non si vogliono limiti massimi impostare il valore a 0'));
            }
        }
    }
    /*
      public function attributeLabels()
      {
      return
      ArrayHelper::merge(
      parent::attributeLabels(),
      [
      'tagValues' => '',
      'regola_pubblicazione' => 'Pubblicata per',
      'destinatari' => 'Per i condominii',
      ]);
      }

      public static function find()
      {
      $Sondaggi DomandeQuery = new Sondaggi DomandeQuery(get_called_class());
      $Sondaggi DomandeQuery->andWhere('sondaggi Domande.deleted_at IS NULL');
      return $Sondaggi DomandeQuery;
      }
     */

    /**
     * Funzione che restituisce tutte le domande del sondaggio
     *
     * @return ActiveRecord Ritorna un oggetto con la risposta della query con tutte le domande del sondaggio
     */
    public function getTutteDomandeSondaggio()
    {
        return $this->find()->andWhere(['sondaggi_id' => $this->sondaggi->id]);
    }

    public function getTutteDomandeLibere()
    {
        return $this->find()
                ->andWhere(['sondaggi_id' => $this->sondaggi->id])
                ->andFilterWhere(['sondaggi_domande_pagine_id' => $this->sondaggi_domande_pagine_id])
                ->andFilterWhere(['!=', 'id', $this->id])
                ->andWhere(['in', 'sondaggi_domande_tipologie_id', [5, 6]]);
    }

    public function getChildren() {
        return SondaggiDomande::find()->andWhere(['parent_id' => $this->id]);
    }

    public function getParent() {
        return SondaggiDomande::findOne($this->parent_id);
    }

    public function getTutteDomandeDellePagine()
    {
        $idSondaggio     = $this->getSondaggi()->one()->id;
        $idPaginaAttuale = $this->getSondaggiDomandePagine()->one()->id;
        if ($this->id) {
            $domande = $this->find()
                ->andWhere(['sondaggi_domande.sondaggi_id' => $this->sondaggi->id])
                //->andWhere(['sondaggi_domande.sondaggi_domande_pagine_id' => $this->sondaggi_domande_pagine_id])
                ->andWhere(['!=', 'sondaggi_domande.id', $this->id])
                ->andFilterWhere(['<=', 'sondaggi_domande.sondaggi_domande_pagine_id', $idPaginaAttuale])
                ->andWhere(['in', 'sondaggi_domande_tipologie_id', [1, 2, 3, 4]]);
        } else {
            $domande = $this->find()
                ->andWhere(['sondaggi_domande.sondaggi_id' => $this->sondaggi->id])
                //->andWhere(['sondaggi_domande.sondaggi_domande_pagine_id' => $this->sondaggi_domande_pagine_id])
                ->andFilterWhere(['<=', 'sondaggi_domande.sondaggi_domande_pagine_id', $idPaginaAttuale])
                ->andWhere(['in', 'sondaggi_domande_tipologie_id', [1, 2, 3, 4]]);
        }
        $arrDomande = [];
        foreach ($domande->all() as $Domande) {
            $arrDomande[] = $Domande['id'];
        }
        $preQuery  = (new \yii\db\Query())->from('sondaggi_risposte_predefinite as R')->where(['IN', 'sondaggi_domande_id',
            $arrDomande]);
        $newExp    = new \yii\db\Expression("D.id as domandaid, concat('[', P.titolo, ']', ' - ' , D.id, ' - ', D.domanda) as domanda, R.id, R.risposta, R.ordinamento");
        $risultato = $preQuery->innerJoin('sondaggi_domande as D', 'D.id = R.sondaggi_domande_id')
            ->innerJoin('sondaggi_domande_pagine as P', 'P.id = D.sondaggi_domande_pagine_id')
            ->andWhere(['R.deleted_at' => null])
            ->andWhere(['P.deleted_at' => null])
            ->orderBy('P.ordinamento, D.ordinamento, R.ordinamento')
            ->select($newExp);

        return $risultato;
    }

    /**
     * Restituisce tutte le risposte predefinite tra le quali scegliere quella che condiziona la domanda
     */
    public function getRispostePredefiniteSondaggio()
    {
        $idSondaggio = $this->getSondaggi()->one()->id;
        if ($this->id) {
            $domande = SondaggiDomande::find()->andWhere(['sondaggi_id' => $idSondaggio])->andWhere(['!=', 'id', $this->id]);
        } else {
            $domande = SondaggiDomande::find()->andWhere(['sondaggi_id' => $idSondaggio]);
        }
        $arrDomande = [];
        foreach ($domande->all() as $Domande) {
            $arrDomande[] = $Domande['id'];
        }
        $preQuery  = (new \yii\db\Query())->from('sondaggi_risposte_predefinite as R')->where(['IN', 'sondaggi_domande_id',
            $arrDomande]);
        $risultato = $preQuery->innerJoin('sondaggi_domande as D', 'D.id = R.sondaggi_domande_id')
            ->select('D.id as domandaid, D.domanda as domanda, R.id, R.risposta, R.ordinamento');
        return $risultato;
        /* return SondaggiRispostePredefinite::find()
          ->andWhere(['IN', 'sondaggi_domande_id', $arrDomande])
          ->innerJoin('sondaggi_domande as D', 'D.id = sondaggi_risposte_predefinite.sondaggi_domande_id')
          ->select('D.id as domandaid, D.domanda as domanda, sondaggi_risposte_predefinite.id, sondaggi_risposte_predefinite.risposta, sondaggi_risposte_predefinite.ordinamento'); */
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSondaggiRispostePreCondMm()
    {
        return $this->hasMany(SondaggiDomandeCondizionate::className(), ['sondaggi_domande_id' => 'id']);
    }

    public function getSondaggiDomandaCondizionaLibera()
    {
        return $this->hasOne(\open20\amos\sondaggi\models\SondaggiDomande::className(),
                ['id' => 'domanda_condizionata_testo_libero']);
    }

    /**
     * Ordina le domande in funzione di quella appena salvata
     * @param string $tipo Tipologia di ordinamento che può essere 'inizio', 'fine' e 'dopo'
     * @param integer $dopo Id della domanda dopo la quale inserire la nuova, se non settata è 0 quindi questa funzione è disabilitata e la domanda verrà inserita alla fine
     * @param integer $condizionata Indica se la domanda è condizionata e in questo caso anche se si dovesse scegliere un ordine precedente alla domanda dalla quale dipende verrà inserita subito dopo
     */
    public function setOrdinamento($tipo, $dopo = 0, $condizionata = 0)
    {
        if ($dopo > 0 && $dopo != null && $tipo == 'dopo') {
            if ($condizionata == 0) {
                $ordDopo           = SondaggiDomande::findOne(['id' => $dopo])->ordinamento;
                $DomandeDopo       = $this->getTutteDomandeSondaggio()->andWhere(['>', 'ordinamento', $ordDopo])->andWhere([
                    '!=', 'id', $this->id]);
                $this->ordinamento = $ordDopo + 1;
                $this->save();
                foreach ($DomandeDopo->all() as $Domande) {
                    $aggiorna              = SondaggiDomande::findOne(['id' => $Domande['id']]);
                    $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                    $aggiorna->save();
                }
            } else {
                $ordDopoObj = SondaggiDomande::findOne(['id' => $dopo]);
                $ordDopo    = $ordDopoObj->ordinamento;
                $paginaId   = $ordDopoObj->sondaggi_domande_pagine_id;
                $arrCond    = [];
                foreach ($condizionata as $cond) {
                    $condDom    = SondaggiRispostePredefinite::findOne(['id' => $cond])->sondaggi_domande_id;
                    $ordCondObj = SondaggiDomande::find()
                        ->andWhere(['id' => $condDom])
                        ->andWhere(['sondaggi_domande_pagine_id' => $paginaId])
                        ->one();
                    if (!empty($ordCondObj)) {
                        $ordCond   = $ordCondObj->ordinamento;
                        $arrCond[] = $ordCond;
                    }
                }
                if ($ordDopo > max($arrCond)) {
                    $DomandeDopo       = $this->getTutteDomandeSondaggio()->andWhere(['>', 'ordinamento', $ordDopo])->andWhere([
                        '!=', 'id', $this->id]);
                    $this->ordinamento = $ordDopo + 1;
                    $this->save();
                    foreach ($DomandeDopo->all() as $Domande) {
                        $aggiorna              = SondaggiDomande::findOne(['id' => $Domande['id']]);
                        $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                        $aggiorna->save();
                    }
                } else {
                    $DomandeDopo       = $this->getTutteDomandeSondaggio()->andWhere(['>', 'ordinamento', max($arrCond)])->andWhere([
                        '!=', 'id', $this->id]);
                    $this->ordinamento = max($arrCond) + 1;
                    $this->save();
                    foreach ($DomandeDopo->all() as $Domande) {
                        $aggiorna              = SondaggiDomande::findOne(['id' => $Domande['id']]);
                        $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                        $aggiorna->save();
                    }
                }
            }
        } else {
            $TutteDomande = $this->getTutteDomandeSondaggio()->andWhere(['!=', 'id', $this->id]);
            if ($TutteDomande->count() == 0) {
                $this->ordinamento = 1;
                $this->save();
            } else {
                if ($tipo == 'inizio' && $condizionata == 0) {
                    $this->ordinamento = 1;
                    $this->save();
                    foreach ($TutteDomande->all() as $Domande) {
                        $aggiorna              = SondaggiDomande::findOne(['id' => $Domande['id']]);
                        $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                        $aggiorna->save();
                    }
                } else if ($tipo == 'inizio' && !empty($condizionata)) {
                    $arrDopo = [];

                    $ordDopoObj = SondaggiDomande::findOne(['id' => $dopo]);
                    $ordDopo    = $ordDopoObj->ordinamento;
                    $paginaId   = $ordDopoObj->sondaggi_domande_pagine_id;

                    foreach ($condizionata as $cond) {
                        $condDom    = SondaggiRispostePredefinite::findOne(['id' => $cond])->sondaggi_domande_id;
                        $ordCondObj = SondaggiDomande::find()
                            ->andWhere(['id' => $condDom])
                            ->andWhere(['sondaggi_domande_pagine_id' => $paginaId])
                            ->one();
                        if (!empty($ordCondObj)) {
                            $ordDopo   = $ordCondObj->ordinamento;
                            $arrDopo[] = $ordDopo;
                        }
                    }
                    $DomandeDopo       = $this->getTutteDomandeSondaggio()->andWhere(['>', 'ordinamento', max($arrDopo)])->andWhere([
                        '!=', 'id', $this->id]);
                    $this->ordinamento = max($arrDopo) + 1;
                    $this->save();
                    foreach ($DomandeDopo->all() as $Domande) {
                        $aggiorna              = SondaggiDomande::findOne(['id' => $Domande['id']]);
                        $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                        $aggiorna->save();
                    }
                } else {
                    $this->ordinamento = ($TutteDomande->max('ordinamento')) ? ($TutteDomande->max('ordinamento') + 1) : 1;
                    $this->save();
                }
            }
        }
    }

    /**
     * Restituisce l'id della domanda precedente
     */
    public function getDomandaPrecedente()
    {
        if ($this->ordinamento) {
            $ordine  = $this->ordinamento;
            $domande = SondaggiDomande::find()->andWhere(['<', 'ordinamento', $ordine])->andWhere(['sondaggi_domande_pagine_id' => $this->sondaggi_domande_pagine_id])->orderBy('ordinamento DESC');
            if ($domande->count() > 0) {
                if ($ordine > $domande->one()['ordinamento']) {
                    $domanda = SondaggiDomande::findOne(['ordinamento' => $domande->one()['ordinamento'], 'sondaggi_domande_pagine_id' => $this->sondaggi_domande_pagine_id]);
                    if (count($domanda) == 1) {
                        return $domanda->id;
                    }
                }
            }
        }
        return null;
    }

    /**
     *
     * @param int $user_id
     * @param int $session_id
     * @return \yii\db\ActiveQuery
     */
    public function getRispostePerUtente($user_id = null, $session_id = null)
    {
		if($this->is_parent){
			$query = $this->getSondaggiRisposteChilds()
            ->leftJoin('sondaggi_risposte_sessioni',
                'sondaggi_risposte_sessioni.id = sondaggi_risposte.sondaggi_risposte_sessioni_id')
            ->andWhere(['sondaggi_risposte_sessioni.id' => $session_id]);
        if (!empty($user_id)) {
            $query->andWhere(['sondaggi_risposte_sessioni.user_id' => $user_id]);
        }
		} else {
        $query = $this->getSondaggiRispostes()
            ->leftJoin('sondaggi_risposte_sessioni',
                'sondaggi_risposte_sessioni.id = sondaggi_risposte.sondaggi_risposte_sessioni_id')
            ->andWhere(['sondaggi_risposte_sessioni.id' => $session_id]);
        if (!empty($user_id)) {
            $query->andWhere(['sondaggi_risposte_sessioni.user_id' => $user_id]);
        }
		}
        return $query;
    }

    /**
     *
     * @param array $ids_validazioni
     */
    public function setValidazione($ids_validazioni)
    {
        if (!empty($this->id)) {
            SondaggiDomandeRuleMm::deleteAll(['sondaggi_domande_id' => $this->id]);
            if (!empty($ids_validazioni)) {
                if (in_array($this->sondaggiDomandeTipologie->id, [5, 6])) { // 5 = libera breve, 6 = libera lunga
                    foreach ($ids_validazioni as $k => $v) {
                        $model                           = new SondaggiDomandeRuleMm();
                        $model->sondaggi_domande_id      = $this->id;
                        $model->sondaggi_domande_rule_id = $v;
                        $model->save();
                    }
                }
            }
        }
    }
}
