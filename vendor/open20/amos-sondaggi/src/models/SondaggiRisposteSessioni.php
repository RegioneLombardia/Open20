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

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use kartik\mpdf\Pdf;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use open20\amos\sondaggi\utility\SondaggiUtility;

/**
 * Class SondaggiRisposteSessioni
 * This is the model class for table "sondaggi_risposte_sessioni".
 * @package open20\amos\sondaggi\models
 */
class SondaggiRisposteSessioni extends \open20\amos\sondaggi\models\base\SondaggiRisposteSessioni
{
    public $byBassRuleCwh = true;

    // Workflow ID
    const WORKFLOW                            = 'SondaggiSessioniWorkflow';
    // Workflow states IDS
    const WORKFLOW_STATUS_BOZZA               = 'SondaggiSessioniWorkflow/BOZZA';
    const WORKFLOW_STATUS_RICHIESTA_INVIO     = 'SondaggiSessioniWorkflow/RICHIESTAINVIO';
    const WORKFLOW_STATUS_INVIATO             = 'SondaggiSessioniWorkflow/INVIATO';
    const WORKFLOW_STATUS_BOZZA_KEY           = 'BOZZA';
    const WORKFLOW_STATUS_RICHIESTA_INVIO_KEY = 'RICHIESTAINVIO';
    const WORKFLOW_STATUS_INVIATO_KEY         = 'INVIATO';

    public function init()
    {
        $moduleSondaggi = AmosSondaggi::instance();
        if (($this->isNewRecord || $this->status == null) && $moduleSondaggi->enableCompilationWorkflow == true) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::WORKFLOW)->getInitialStatusId();
        }
        $user_id = \Yii::$app->getUser()->id;
        $session = $this;
        if ($moduleSondaggi->enableCompilationWorkflow == true) {
            $this->on('afterEnterStatus{'.self::WORKFLOW_STATUS_RICHIESTA_INVIO.'}',
            function() use($user_id) {
                // Deleting associated "standard" users which were enabled to compile the poll
                $result = SondaggiUsersInvitationMm::find()->andWhere(['sondaggi_id' => $this->sondaggi_id, 'user_id' => $user_id])->one();
                $result->forceDelete();
            }, $this);
            $this->on('afterEnterStatus{'.self::WORKFLOW_STATUS_INVIATO.'}',
            function($event) use ($session) {
                // Sending emails on "Sent" status
                $path = "uploads/Sondaggio_compilato".$session->id.'_'.time().".pdf";
                $session->generateSondaggiPdf($path);
                if (AmosSondaggi::instance()->enableCompilationWorkflow) {
                    if ($session->sondaggi->send_pdf_to_compiler || $session->sondaggi->send_pdf_via_email) {
                        SondaggiUtility::sendEmailSondaggioCompilato($session->sondaggi, $session->id, $path);
                    }
                }
            }, $this);
        }
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        $rules = ArrayHelper::merge(parent::rules(), [
                ['status', 'safe'],
        ]);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $moduleSondaggi = AmosSondaggi::instance();
        if ($moduleSondaggi->enableCompilationWorkflow == true) {
            $workflowBehaviors = [
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'WorkflowLogFunctionsBehavior' => [
                    'class' => WorkflowLogFunctionsBehavior::className(),
                ],
            ];
        } else {
            $workflowBehaviors = [];
        }
        return ArrayHelper::merge(parent::behaviors(), $workflowBehaviors);
    }

    /**
     * @param $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function generateSondaggiPdf($path = null)
    {
        $id = $this->id;
        $sondaggio = $this->sondaggi;
        $sondaggioId = $sondaggio->id;
        $xlsData     = [];
        // se abilita_registrazione == 1 allora i tre campi vanno visualizzati, se no, vale il parametro statisticExtractDisableNameSurnameEmail
        $viewNameSurnameEmail = $sondaggio->abilita_registrazione || !AmosSondaggi::instance()->statisticExtractDisableNameSurnameEmail;


        // INTESTAZIONE EXCEL
        $xlsData[0] = ["", "", ""];
        if ($viewNameSurnameEmail){
            $xlsData[0] = ["Nome", "Cognome", "Email"];
        }
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $xlsData[0][] = AmosSondaggi::t('amossondaggi', '#organization');
        }
        $domande = $sondaggio->getSondaggiDomandes()
            ->andWhere(['sondaggi_domande.parent_id' => null])
            ->orderBy([
                'sondaggi_domande_pagine_id' => SORT_ASC,
                'ordinamento' => SORT_ASC
            ])->all();
        $count = 1;
        foreach ($domande as $domanda) {
            $xlsData[0][] = "D.".$count." ".$domanda->domanda;
            $count ++;
        }


        // CORPO FILE EXCEL
        $sondRisposta = SondaggiRisposteSessioni::find()
            ->distinct()
            ->leftJoin('user_profile', 'user_profile.user_id = sondaggi_risposte_sessioni.user_id')
            ->leftJoin('user', 'user.id = user_profile.user_id')
            ->leftJoin('sondaggi_risposte',
                'sondaggi_risposte_sessioni.id = sondaggi_risposte.sondaggi_risposte_sessioni_id')
            ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $sondaggioId])
            ->andWhere(['sondaggi_risposte_sessioni.id' => $id])
//            ->andWhere(['user_profile.user_id' => \Yii::$app->user->id])
            ->one();

        $row = 1;
        $profile = null;
        if (!empty($sondRisposta->user_id)) {
            /** @var AmosAdmin $adminModule */
            $adminModule = AmosAdmin::instance();
            /** @var UserProfile $userProfileModel */
            $userProfileModel = $adminModule->createModel('UserProfile');
            $profile = $userProfileModel::find()->andWhere(['user_id' => $sondRisposta->user_id])->one();
        }

        $dateDiff = (new \DateTime())->diff(new \DateTime($sondRisposta->updated_at));

        if ($viewNameSurnameEmail && !empty($profile)) {
            $xlsData [$row][0] = $profile->nome;
            $xlsData [$row][1] = $profile->cognome;
            $xlsData [$row][2] = $profile->user->email;
        } else {
            $xlsData [$row][0] = "";
            $xlsData [$row][1] = "";
            $xlsData [$row][2] = "";
        }
        if (($dateDiff->invert * $dateDiff->days) > 730 && AmosSondaggi::instance()->resetGdpr) {
            $xlsData [$row][0] = "#####";
            $xlsData [$row][1] = "#####";
            $xlsData [$row][2] = "#####";
        }
        $session_id        = $sondRisposta->id;

        /** @var  $domanda SondaggiDomande */
        $colum = 3;
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $profilo = \open20\amos\organizzazioni\models\Profilo::find()->andWhere(['id' => $sondRisposta->organization_id])->one();
            $xlsData[$row][$colum] = !empty($profilo->name) ? $profilo->name : '';
            $colum++;
        }
        $divider = $colum;
        foreach ($domande as $domanda) {
            $query = $domanda->getRispostePerUtente((empty($profile) ? null : $profile->user_id), $session_id);
            // RISPOSTE LIBERE
            if ($domanda->sondaggi_domande_tipologie_id == 6 || $domanda->sondaggi_domande_tipologie_id == 5) {
//                    pr($query->one()->risposta_libera, 'D. ' . $domanda->id);
                $risposta = $query->one();
                if ($risposta) {
                    $xlsData [$row][$colum] = str_replace(['<', '>'], ' ', $risposta->risposta_libera);
                } else {
                    $xlsData [$row][$colum] = '';
                }
                //ALLEGATI
            } else if ($domanda->sondaggi_domande_tipologie_id == 13) {
                $risposta = $query->one();
                if ($risposta) {
                    $xlsData [$row][$colum] = \Yii::$app->formatter->asDate($risposta->risposta_libera);
                } else {
                    $xlsData [$row][$colum] = '';
                }
            } else if ($domanda->sondaggi_domande_tipologie_id == 12) {
                $xlsData [$row][$colum] = '';
            } else if ($domanda->sondaggi_domande_tipologie_id == 10 || $domanda->sondaggi_domande_tipologie_id == 11) {
                $risposta = $query->one();
                if ($risposta) {
                    $attribute = 'domanda_'.$domanda->id;
                    if (!empty($risposta->$attribute)) {
                        $attachments    = $risposta->getFiles();
                        $listAttachUrls = [];
                        $risposteString = "<ul>";
                        /** @var  $attach File */
                        foreach ($attachments as $attach) {
                            if (AmosSondaggi::instance()->xlsAsZip) {
                              if(!empty($profile) && !AmosSondaggi::instance()->forceOnlyFrontend){
                                $folder =  BaseInflector::slug($profile->cognome.' '.$profile->nome);
                              } else {
                                   $folder = BaseInflector::slug($this->id.' '.$this->begin_date.' sondaggio');
                              }
                                if (AmosSondaggi::instance()->compilationToOrganization) {
                                    $profilo = \open20\amos\organizzazioni\models\Profilo::find()->andWhere(['id' => $sondRisposta->organization_id])->one();
                                    $folder = BaseInflector::slug($profilo->name);
                                }
                                $risposteString .= "<li>".$folder.'/'.$attach->name.'.'.$attach->type."</li>";
                            }
                            else
                                $risposteString .= "<li><a href='".\Yii::$app->params['platform']['backendUrl'].$attach->getUrl()."'>".$attach->name."</a></li>";
                        }
                        $risposteString         .= '</ul>';
                        $xlsData [$row][$colum] = $risposteString;
//                        $xlsData [$row][$colum] = "<ul><li>".implode("</li><li>", $listAttachUrls)."</ul>";
//                            $xlsData [$row][$colum] = implode("\n", $listAttachUrls);
                    }
                } else {
                    $xlsData [$row][$colum] = '';
                }
            } else {
                $risposteArray = [];

                foreach ($query->all() as $risposta) {
                    if ($risposta->sondaggiRispostePredefinite) {
                        if (empty($risposta->sondaggiRispostePredefinite->code))
                        $risposteArray [] = $risposta->sondaggiRispostePredefinite->risposta;
                    else
                        $risposteArray [] = $risposta->sondaggiRispostePredefinite->code . ' - '. $risposta->sondaggiRispostePredefinite->risposta;
                    }
                }
//                    $xlsData [$row][$colum] = implode("\n", $risposteArray);

                $xlsData [$row][$colum] = "<ul><li>".implode("</li><li>", $risposteArray)."</ul>";

//                    pr(implode(',', $risposteArray), 'D. ' . $domanda->id);
            }
            $colum++;
        }
        return $this->savePdf($xlsData, $sondaggio, $sondRisposta, $path, $divider);
    }

    /**
     * @param $data
     * @param $modelSondaggio
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function savePdf($data, $modelSondaggio, $modelSondaggioRisposta, $path = null, $divider = 0)
    {
        $content = \Yii::$app->controller->renderPartial('@vendor/open20/amos-sondaggi/src/views/sondaggi/_view_pdf',
            [
            'data' => $data,
            'sondaggio' => $modelSondaggio,
            'rispostaModel' => $modelSondaggioRisposta,
            'divider' => $divider
        ]);
//        $footer = $this->renderPartial('@vendor/open20/amos-proposte-collaborazione-een/src/views/een-expr-of-interest/_pdf_footer', ['model' => $eenExpr]);


        $pdf = new Pdf([
            'mode' => Pdf::MODE_BLANK,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'cssInline' => '',
            'options' => [
                'title' => '',
                'setAutoBottomMargin' => 'pad',
                'autoMarginPadding' => 1
            ],
            'methods' => AmosSondaggi::instance()->pdfMethods,
        ]);

//        $pdf->getApi()->SetHTMLFooter($footer);
        $pdf->getApi()->SetMargins(0, 0, 20);
//        $pdf->getApi()->SetAutoPageBreak(TRUE, 25);
        $pdf->getApi()->margin_header = '6px';
        //$pdf->getApi()->margin_footer = '10px';

        if (!empty($path)) {
            return $pdf->output($content, $path, Pdf::DEST_FILE);
        } else {
            return $pdf->output($content, "Sondaggio_compilato.pdf", Pdf::DEST_DOWNLOAD);
        }
    }
}
