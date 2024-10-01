<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\controllers
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\Risposte;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\models\SondaggiDomandeCondizionate;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral;
use open20\amos\upload\models\FilemanagerMediafile;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * Class SondaggiController
 * SondaggiController implements the CRUD actions for Sondaggi model.
 *
 * @property \open20\amos\sondaggi\models\Sondaggi $model
 * @property \open20\amos\sondaggi\models\search\SondaggiSearch $modelSearch
 *
 * @package open20\amos\sondaggi\controllers
 */
class ConsoleController extends \yii\console\Controller
{

    /**
     * @param $id
     * @throws \yii\web\NotFoundHttpException
     */
    public static function actionExtract($id, $task_id = null)
    {
        $model   = Sondaggi::findOne($id);
        $xlsData = [];

// INTESTAZIONE EXCEL
        $xlsData[0] = ["Nome", "Cognome", "Email", "Iniziato il", "Completato il"];
        $pagine     = $model->getSondaggiDomandePagines()->orderBy('sondaggi_domande_pagine.ordinamento');
        $domande    = [];
        foreach ($pagine->all() as $pagina) {
            $domandePagina = $pagina->getSondaggiDomandes()->orderBy('ordinamento ASC')->all();
            foreach ($domandePagina as $domandaPag) {
                $domande[] = $domandaPag;
            }
        }
        //$domande         = $model->getSondaggiDomandes()->orderBy('ordinamento ASC')->all();
        $count           = 1;
        $totCount        = 5;
        $colRisp         = [];
        $colRispLibere   = [];
        $colRispAllegati = [];
        foreach ($domande as $domanda) {
            $rispostePredefinite = $domanda->getSondaggiRispostePredefinites();
            $countRisposte       = $rispostePredefinite->count();
            $localCount          = 1;
            if (in_array($domanda->sondaggi_domande_tipologie_id, [10, 11])) {
                $xlsData[0][]                  = "D.".$count." ".$domanda->domanda;
                $colRispAllegati[$domanda->id] = $totCount;
                $totCount++;
            } else if (in_array($domanda->sondaggi_domande_tipologie_id, [5, 6, 13, 12, 14])) {
                $xlsData[0][]                = "D.".$count." ".$domanda->domanda;
                $colRispLibere[$domanda->id] = $totCount;
                $totCount++;
            } else {
                if (!empty($countRisposte) && in_array($domanda->sondaggi_domande_tipologie_id, [1, 2, 3, 4])) {
                    foreach ($rispostePredefinite->orderBy('ordinamento ASC')->all() as $rispPre) {
                        $xlsData[0][]          = "D.".$count." ".$domanda->domanda."\nR.".$localCount." ".$rispPre->risposta;
                        $colRisp[$rispPre->id] = $totCount;
                        $localCount++;
                        $totCount++;
                    }
                }
            }
            $count ++;
        }


// CORPO FILE EXCEL
        $sondaggiRisposte = SondaggiRisposteSessioni::find()
            ->distinct()
            ->innerJoin('sondaggi_risposte',
                'sondaggi_risposte_sessioni.id = sondaggi_risposte.sondaggi_risposte_sessioni_id')
            ->leftJoin('user_profile', 'user_profile.user_id = sondaggi_risposte_sessioni.user_id')
            ->leftJoin('user', 'user_profile.user_id = user.id')
            ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $id])
            ->orderBy('sondaggi_risposte_sessioni.begin_date')
            ->all();

        $row = 1;

        foreach ($sondaggiRisposte as $sondRisposta) {
            $profile = null;
            if (!empty($sondRisposta->user_id)) {
                /** @var AmosAdmin $adminModule */
                $adminModule = AmosAdmin::instance();
                /** @var UserProfile $userProfileModel */
                $userProfileModel = $adminModule->createModel('UserProfile');
                $profile = $userProfileModel::find()->andWhere(['user_id' => $sondRisposta->user_id])->one();
            }
            if (empty($profile)) {
                $xlsData [$row][0] = ($model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                        'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                        'L\'utente non è stato registrato'));
                $xlsData [$row][1] = ($model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                        'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                        'L\'utente non è stato registrato'));
                $xlsData [$row][2] = ($model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                        'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                        'L\'utente non è stato registrato'));
            } else {
                $xlsData [$row][0] = $profile->nome;
                $xlsData [$row][1] = $profile->cognome;
                $xlsData [$row][2] = $profile->user->email;
            }
            $dateDiff = (new \DateTime())->diff(new \DateTime($sondRisposta->updated_at));
            if (($dateDiff->invert * $dateDiff->days) > 730 && AmosSondaggi::instance()->resetGdpr) {
                $xlsData [$row][0] = "#####";
                $xlsData [$row][1] = "#####";
                $xlsData [$row][2] = "#####";
            }
            $xlsData [$row][3] = $sondRisposta->begin_date;
            $xlsData [$row][4] = $sondRisposta->end_date;
            $session_id        = $sondRisposta->id;


            /** @var  $domanda SondaggiDomande */
            foreach ($domande as $domanda) {

                $query = $domanda->getRispostePerUtente((empty($profile) ? null : $profile->user_id), $session_id);
// RISPOSTE LIBERE
                if ($domanda->sondaggi_domande_tipologie_id == 6 || $domanda->sondaggi_domande_tipologie_id == 5) {
//                    pr($query->one()->risposta_libera, 'D. ' . $domanda->id);
                    $risposta = $query->one();
                    if ($risposta) {
                        $xlsData[$row][$colRispLibere[$domanda->id]] = $risposta->risposta_libera;
                    } else {

                    }
//ALLEGATI
                } else if ($domanda->sondaggi_domande_tipologie_id == 13) {
                    $risposta = $query->one();
                    if ($risposta) {
                        $xlsData[$row][$colRispLibere[$domanda->id]] = \Yii::$app->formatter->asDate($risposta->risposta_libera);
                    } else {

                    }
                } else if ($domanda->sondaggi_domande_tipologie_id == 12) {

                } else if ($domanda->sondaggi_domande_tipologie_id == 10 || $domanda->sondaggi_domande_tipologie_id
                    == 11) {
                    $risposta = $query->one();
                    if ($risposta) {
                        $attribute = 'domanda_'.$domanda->id;
                        if (!empty($risposta->$attribute)) {
                            $attachments    = $risposta->$attribute;
                            $listAttachUrls = [];
                            foreach ($attachments as $attach) {
                                $listAttachUrls [] = \Yii::$app->params['platform']['backendUrl'].$attach->getUrl();
                            }
                            $xlsData[$row][$colRispAllegati[$domanda->id]] = implode("\n", $listAttachUrls);
                        }
                    } else {

                    }
                } else if ($domanda->sondaggi_domande_tipologie_id == 14) {
                    $risposta = $query->one();
                    if ($risposta) {
                        $xlsData[$row][$colRispLibere[$domanda->id]] = $risposta->sondaggiRispostePredefinite->risposta;
                    } else {

                    }
                } else {
                    $risposteArray = [];
                    foreach ($query->all() as $risposta) {
                        if ($risposta->sondaggiRispostePredefinite) {
                            $xlsData[$row][$colRisp[$risposta->sondaggiRispostePredefinite->id]] = $risposta->sondaggiRispostePredefinite->risposta;
                        }
                    }
                }
            }
            $row++;
            gc_collect_cycles();
        }

        /** @var  $domanda SondaggiDomande */
        $basePath    = \Yii::getAlias('@vendor/../common/uploads/temp');
//inizializza l'oggetto excel
        $nomeFile    = $basePath.'/Risposte_sondaggio_'.$id.'_'.$task_id.'.xls';
        $objPHPExcel = new \PHPExcel();

// set Style first row
        $lastColumn       = $totCount;
        $lastColumnLetter = \PHPExcel_Cell::stringFromColumnIndex($lastColumn);

        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$lastColumnLetter.'1')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('C0C0C0');

        for ($i = 1; $i <= $row; $i++) {
            for ($c = 0; $c <= $lastColumn; $c++) {
                if (empty($xlsData[$i]) || !array_key_exists($c, $xlsData[$i])) {
                    $xlsData[$i][$c] = '';
                }
            }
        }

        foreach ($xlsData as $key => $value) {
            ksort($xlsData[$key]);
        }

//li pone nella tab attuale del file xls
        $objPHPExcel->getActiveSheet()->fromArray($xlsData, NULL, 'A1');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($nomeFile);

        if (!is_null($task_id)) {
            $task = \open20\amos\sondaggi\modules\v1\models\TaskSondaggi::findOne($task_id);
            if (!is_null($task)) {
                $task->filename = $nomeFile;
                $task->status   = 3;
                $task->save();
            }
        }
    }
}
