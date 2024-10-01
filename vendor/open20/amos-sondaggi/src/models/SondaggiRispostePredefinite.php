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

/**
 * Class SondaggiRispostePredefinite
 * This is the model class for table "sondaggi_risposte_predefinite".
 * @package open20\amos\sondaggi\models
 */
class SondaggiRispostePredefinite extends \open20\amos\sondaggi\models\base\SondaggiRispostePredefinite
{
    public $byBassRuleCwh = true;
    public $tipo_domanda;
    public $ordine;
    public $ordina_dopo;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'risposta'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            //[['regola_pubblicazione', 'destinatari', 'validatori'], 'safe'],
            [['tipo_domanda', 'ordina_dopo'], 'integer'],
            ['ordine', 'string'],
        ]);
    }

    /**
     * Funzione che restituisce tutte le domande del sondaggio
     * @param integer $domanda Id della domanda
     * @return ActiveRecord Ritorna un oggetto con la risposta della query con tutte le domande del sondaggio
     */
    public function getTutteRisposteSondaggio()
    {
        return $this->find()->andWhere(['sondaggi_domande_id' => $this->sondaggi_domande_id]);
    }

    /**
     * Funzione che prende il nome della tipologia di domanda
     * @param integer $id Id della tipologia di domanda
     * @return ActiveRecord Ritorna l'oggetto relativo alla tipologia di domanda
     */
    public function getTipologiaDomanda()
    {
        return SondaggiDomandeTipologie::find()->andWhere(['id' => $this->tipo_domanda]);
    }

    /**
     * Ordina le risposte in base alla posizione di quella appena salvata
     * @param string $tipo Tipologia di ordinamento che può essere 'inizio', 'fine' e 'dopo'
     * @param integer $dopo Id della domanda dopo la quale inserire la nuova, se non
     * è settata è 0 quindi questa funzione è disabilitata e la domanda verrà inserita alla fine
     */
    public function setOrdinamento($tipo, $dopo = 0)
    {
        if ($dopo > 0 && $dopo != NULL && $tipo == 'dopo') {

            $ordDopo = SondaggiRispostePredefinite::findOne(['id' => $dopo])->ordinamento;
            $RisposteDopo = $this->getTutteRisposteSondaggio()->andWhere(['>', 'ordinamento', $ordDopo])->andWhere(['!=', 'id', $this->id]);
            $this->ordinamento = $ordDopo + 1;
            $this->save();
            foreach ($RisposteDopo->all() as $Risposte) {
                $aggiorna = SondaggiRispostePredefinite::findOne(['id' => $Risposte['id']]);
                $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                $aggiorna->save();
            }

        } else {
            $TutteRisposte = $this->getTutteRisposteSondaggio()->andWhere(['!=', 'id', $this->id]);
            if ($TutteRisposte->count() == 0) {
                $this->ordinamento = 1;
                $this->save();
            } else {
                if ($tipo == 'inizio') {
                    $this->ordinamento = 1;
                    $this->save();
                    foreach ($TutteRisposte->all() as $Risposte) {
                        $aggiorna = SondaggiRispostePredefinite::findOne(['id' => $Risposte['id']]);
                        $aggiorna->ordinamento = ($aggiorna->ordinamento + 1);
                        $aggiorna->save();
                    }
                } else {
                    $this->ordinamento = ($TutteRisposte->max('ordinamento')) ? ($TutteRisposte->max('ordinamento') + 1) : 1;
                    $this->save();
                }
            }
        }
    }

    /**
     * @param $idDomanda
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public static function import($idDomanda){
        $submitImport = \Yii::$app->request->post('submit-import');
        $count = 0;
        if (!empty($submitImport)) {
            if ((isset($_FILES['import-file']['tmp_name']) && (!empty($_FILES['import-file']['tmp_name'])))) {
                $inputFileName = $_FILES['import-file']['tmp_name'];
                $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);

                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $ret['file'] = true;
                $i = 1;
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                        NULL,
                        TRUE,
                        FALSE);
                    $Array = $rowData[0];
                    $rispostaPredefinitaName   = $Array[0];
                    $rispostaPredefinitaCodice = $Array[1];
                    if(!empty($rispostaPredefinitaName)) {
                        $rispostaPredefinita = new SondaggiRispostePredefinite();
                        $rispostaPredefinita->risposta = $rispostaPredefinitaName;
                        $rispostaPredefinita->sondaggi_domande_id = $idDomanda;
                        $rispostaPredefinita->code = $rispostaPredefinitaCodice;
                        $rispostaPredefinita->ordinamento = $i;
                        $ok = $rispostaPredefinita->save();
                        if($ok){
                            $count ++;
                            $i++;
                        }
                    }
                }
                \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', "Sono state inserite {n} risposte.", ['n' => $count]));
            }
        }
        return $count > 0;
    }

    /**
     *
     * @param type $idModello
     * @param type $idDomanda
     * @return type
     */
    public static function importFromModello($idModello, $idDomanda) {
        $count = 0;

        if ($idDomanda) {
            $domanda = SondaggiDomande::findOne(['id' => $idDomanda]);
            if (!is_null($domanda)) {
                $rispostePredefinite = $domanda->sondaggiRispostePredefinites;
                $allOk = true;
                foreach ($rispostePredefinite as $rispostaPredefinita) {
                    $rispostaPredefinita->delete();
                }

                $modello = SondaggiModelliPredefiniti::find()
                                ->andWhere(['=', SondaggiModelliPredefiniti::tableName() . ".`id`", $idModello])->one();
                $tmpModel = \Yii::createObject([
                            'class' => $modello->classname,
                ]);

                $risposteByModel = $tmpModel::find()->all();
                $i = 1;
                foreach ($risposteByModel as $risposta) {
                    $rispostaPredefinita = new SondaggiRispostePredefinite();
                    $rispostaPredefinita->risposta = $risposta->nome;
                    $rispostaPredefinita->sondaggi_domande_id = $idDomanda;
                    $rispostaPredefinita->modello_id = $risposta->id;
                    $rispostaPredefinita->ordinamento = $i;
                    $ok = $rispostaPredefinita->save();
                    if ($ok) {
                        $count ++;
                        $i++;
                    }
                }

                return $count > 0;
            }
        }
    }

}
