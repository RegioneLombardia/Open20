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
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\attachments\models\File;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\GeneratoreSondaggio;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use open20\amos\sondaggi\models\SondaggiRisposte;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\utility\SondaggiUtility;
use open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral;
use kartik\mpdf\Pdf;
use Yii;
use open20\amos\sondaggi\assets\ModuleRisultatiFrontendAsset;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use yii\web\Controller;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\utilities\Email;
use open20\amos\socialauth\controllers\SocialAuthController;
use open20\amos\socialauth\models\SocialAuthUsers;
use yii\helpers\Json;

/**
 * Class PubblicazioneController
 * PubblicazioneController implements the CRUD actions for Sondaggi model.
 *
 * @property \open20\amos\sondaggi\models\Sondaggi $model
 * @property \open20\amos\sondaggi\models\search\SondaggiSearch $modelSearch
 *
 * @package open20\amos\sondaggi\controllers
 */
class FrontendController extends Controller
{
    public $base_dir;
    public $percorso_model;
    public $percorso_view;
    public $percorso_validator;
    public $alias_path;
    public $model;
    public $modelSearch;

    /**
    /**
     * @inheritdoc
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config                   = []);
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if (!isset($this->alias_path)) $this->alias_path         = Yii::getAlias('@common');
        if (!isset($this->base_dir)) $this->base_dir           = "sondaggi".DS."pubblicazione";
        if (!isset($this->percorso_model)) $this->percorso_model     = "common\\uploads\\sondaggi\\pubblicazione\\models\\q";
        if (!isset($this->percorso_view)) $this->percorso_view      = "common\\uploads\\sondaggi\\pubblicazione\\views\\q";
        if (!isset($this->percorso_validator)) $this->percorso_validator = "common".DS."uploads".DS."sondaggi".DS."validators".DS;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Action che permette di compilare il sondaggio
     * @param int $id Id del sondaggio da compilare
     * @param int|null $idPagina
     * @param int|null $utente User ID
     * @param null $idSessione
     * @param null $accesso
     * @param null $url
     * @return string
     */
    public function actionCompila($id, $idPagina = null, $utente = null, $idSessione = null, $accesso = null,
                                  $url = null, $language = null, $field_extra = null)
    {

        $sondaggiModule = Yii::$app->getModule('sondaggi');
        if (empty($sondaggiModule)) {
            throw new InvalidConfigException('Module Sondaggi not configured');
        }

        $pageNonCompilabile = '/pubblicazione/non_compilabile_frontend';
        $thankYouPage       = '/pubblicazione/compilato_frontend';
        $thankYouPageisUrl  = false;
        $this->layout       = $sondaggiModule->frontendControllerLayoutPath;
        $pathFront          = 'uploads/';//\Yii::getAlias("common/uploads/");
        ModuleRisultatiFrontendAsset::register(\Yii::$app->getView());
//        if (!$utente) {
//            $utente = Yii::$app->getUser()->getId();
//        }
        $this->model        = Sondaggi::findOne(['id' => $id]);

        if ($this->model->frontend !== 1 || $this->model->status !== Sondaggi::WORKFLOW_STATUS_VALIDATO) {
            return $this->goHome();
        }
        if($this->model->sondaggio_chiuso_frontend == 1 && !empty($this->model->thank_you_page_sondaggio_chiuso)){
            return $this->redirect($this->model->thank_you_page_sondaggio_chiuso);
        }
        if (!empty(trim($this->model->thank_you_page))) {
            if (strpos($this->model->thank_you_page, '@') === 0) {
                $thankYouPage = \Yii::getAlias($this->model->thank_you_page);
            } else {
                $thankYouPage      = $this->model->thank_you_page;
                $thankYouPageisUrl = true;
            }
        }
        $pagineQuery         = $this->model->getSondaggiDomandePagines()->orderBy('ordinamento, id ASC');
        $pagine              = $pagineQuery->all();
        $primaPagina         = $pagine[0]['id'];
        $ultimaPagina        = $pagine[$pagineQuery->count() - 1]['id'];
        $prossimaPagina      = null;
        $arrayPag            = [];
        $completato          = false;
        $domandeWithFilesIds = [];
        foreach ($pagine as $Pag) {
            $arrayPag[] = $Pag['id'];
        }
        if ($idPagina) {
            if ($idPagina != $ultimaPagina) {
                $idPag          = array_search($idPagina, $arrayPag);
                $prossimaPagina = $arrayPag[$idPag + 1];
            }
        } else {
            $idPagina       = $primaPagina;
            $idPag          = array_search($primaPagina, $arrayPag);
            $prossimaPagina = (isset($arrayPag[$idPag + 1])) ? $arrayPag[$idPag + 1] : 0;
        }

        $risposteWithFiles = [];
        if ($primaPagina) {
            $paginaSondaggio        = SondaggiDomandePagine::findOne($primaPagina);
            $query                  = $paginaSondaggio->getSondaggiDomandesWithFiles();
            $risposteWithFiles      = [];
            $domandeWithFilesModels = $query->all();
            foreach ((Array) $domandeWithFilesModels as $domandaSondaggio) {
                $domandeWithFilesIds []        = $domandaSondaggio->id;
                $risposta                      = new SondaggiRisposte();
                $risposta->sondaggi_domande_id = $domandaSondaggio->id;
                $risposteWithFiles []          = $risposta;
            }
        }

        $valutatori = 0;
        if ($this->model->abilita_criteri_valutazione == 1) {
            $valutatori = SondaggiRisposteSessioni::find()->andWhere(['sondaggi_id' => $id])->count();
        }


        if (Yii::$app->request->isPost) {
            $data     = Yii::$app->request->post();
            $idPagina = $data['idPagina'];
            if ($idPagina != $ultimaPagina) {
                $idPag          = array_search($idPagina, $arrayPag);
                $prossimaPagina = $arrayPag[$idPag + 1];
            } else {
                $completato = true;
            }
            // $utente      = $data['utente'];
            $idSessione  = $data['idSessione'];
            $percorso    = $this->percorso_model.$id."\\Pagina_".$idPagina;
            $percorsoNew = $this->percorso_model.$id."\\Pagina_".$prossimaPagina;
            $newModel    = new $percorso;
            $newModel->session_id = $idSessione;
            \Yii::debug($data, 'sondaggi');
            if ($newModel->load($data) && $newModel->validate()) {
                \Yii::debug($newModel->validate(), 'sondaggi');
                $newModel->save($idSessione, $accesso, $completato);
                if ($this->model->frontend == 1) {
                    $this->model->setFrontendCookie($idSessione);
                }

//                foreach ($domandeWithFilesIds as $idDomanda) {
//                    $files = UploadedFile::getInstanceByName("domanda_$idDomanda");
//                    \Yii::$app->getModule('attachments')->attachFile($files->tempName, new SondaggiRisposte(), $attribute = "domanda_$idDomanda", $dropOriginFile = true, $saveWithoutModel = true);
//                }
//                foreach ($domandeWithFilesModels as $doma
                if ($completato) {
                    $path        = (!empty($pathFront) ? $pathFront : "uploads/")."Sondaggio_compilato".$idSessione.'_'.time().".pdf";
                    $user        = null;
                    $dati_utente = $this->getCampiMappati($this->model, $idSessione);
                    $this->generateSondaggiPdf($idSessione, $this->model->id, $path, $dati_utente);
                    if ($this->model->abilita_registrazione == 1) {
                        $user              = $this->registerUser($this->model->id, $idSessione);
                        $sessioneSondaggio = SondaggiRisposteSessioni::find()->andWhere(['id' => $idSessione])->one();
                        if (!empty($sessioneSondaggio) && !empty($user['user'])) {
                            $sessioneSondaggio->user_id = $user['user']->id;
                            $sessioneSondaggio->save(false);
                        }
                    }
                    if (empty($this->model->send_pdf_via_email)) {
                        $path = null;
                    }
                    $this->sendEmailSondaggioCompilato($this->model, $idSessione, $path,
                        (!empty($user['user']) ? $user['user'] : []),
                        ($this->model->abilita_registrazione ? null : $dati_utente), $user['new']);

                    if ($thankYouPageisUrl) {
                        return $this->redirect($thankYouPage);
                    } else {
                        return $this->render($thankYouPage,
                                ['url' => $url, 'pubblicazioni' => $this->model->getSondaggiPubblicaziones(), 'forzato' => $this->model->forza_lingua, 'sondaggio' => $this->model, 'sessione' => $idSessione,  'language' => $language, 'field_extra' => $field_extra]);
                    }
                } else {
                    $prossimoModel = new $percorsoNew;
                    return $this->render('/pubblicazione/compila',
                            ['model' => $prossimoModel, 'idSessione' => $idSessione, 'idPagina' => $prossimaPagina, 'utente' => $utente,
                            'id' => $id, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina,  'language' => $language, 'field_extra' => $field_extra]);
                }
            } else {
                return $this->render('/pubblicazione/compila',
                        ['model' => $newModel, 'idSessione' => $idSessione, 'idPagina' => $idPagina, 'utente' => $utente,
                        'id' => $id, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina,  'language' => $language, 'field_extra' => $field_extra]);
            }
        } else {

            // se sono in frontend ed è stato settato un cookie con l'idSessione - allora prendo quello!
            if ($this->model->frontend == 1) {
                $cookieValues = $this->model->getFrontendCookie();
                if (isset($cookieValues['idSessione']) && !empty($cookieValues['idSessione'])) {
                    $idSessione = $cookieValues['idSessione'];
                }
            }

            $inCorso = SondaggiRisposteSessioni::find()->andWhere(['sondaggi_id' => $id])->andWhere(['id' => $idSessione]);
            if ($inCorso->count() == 0) {
                if ($primaPagina) {
                    $paginaSondaggio = SondaggiDomandePagine::findOne($primaPagina);
                    $query           = $paginaSondaggio->getSondaggiDomandesWithFiles();

                    foreach ($query->all() as $domandaSondaggio) {
                        $risposta                      = new SondaggiRisposte();
                        $risposta->sondaggi_domande_id = $domandaSondaggio->id;
                        $risposteWithFiles []          = $risposta;
                    }
                }

                $idSondaggio           = $id;
                $sessione              = new SondaggiRisposteSessioni();
                $sessione->begin_date  = date('Y-m-d H:i:s');
                $sessione->end_date    = null;
                $sessione->sondaggi_id = $id;
                $sessione->user_id     = $utente;
                $sessione->lang = $language;
                $sessione->field_extra = $field_extra;
                $sessione->save();
                $idSessione            = $sessione->id;
                $modelloPagina         = $this->percorso_model.$id."\\Pagina_".$primaPagina;
                $pagina                = new $modelloPagina;
                return $this->render('/pubblicazione/compila',
                        ['model' => $pagina, 'idSessione' => $idSessione, 'idPagina' => $idPagina, 'utente' => $utente, 'id' => $id,
                        'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
            } else {
                $nonCompletato = 0;
                foreach ($inCorso->all() as $InCorso) {
                    if ($InCorso['completato'] == 0) {
                        $nonCompletato = $InCorso['id'];
                    }
                }
                if ($nonCompletato) {
                    if ($primaPagina) {
                        $paginaSondaggio     = SondaggiDomandePagine::findOne($primaPagina);
                        $query               = $paginaSondaggio->getSondaggiDomandesWithFiles();
                        $domandeWithFilesIds = [];
                        foreach ($query->all() as $domandaSondaggio) {
                            $domandeWithFilesIds [] = $domandaSondaggio->id;
                        }
                    }
                    $sessione = SondaggiRisposteSessioni::findOne(['id' => $nonCompletato]);
                    $risposte = $sessione->getSondaggiRispostes();
                    if ($risposte->count() > 0) {
                        //se esistono risposte date al sondaggio
                        $arrDomande = [];
                        foreach ($risposte->all() as $risposta) {
                            $arrDomande[] = $risposta['sondaggi_domande_id'];
                        }
                        $domande  = SondaggiDomande::find()->andWhere(['IN', 'id', $arrDomande])->orderBy('ordinamento ASC');
                        $idPagina = $domande->all()[$domande->count() - 1]['sondaggi_domande_pagine_id'];
                        if ($idPagina != $ultimaPagina) {
                            $idPag          = array_search($idPagina, $arrayPag);
                            $prossimaPagina = $arrayPag[$idPag + 1];
                        }
                        $percorso          = $this->percorso_model.$id."\\Pagina_".$idPagina;
                        $newModel          = new $percorso;
                        $newModel->session_id = $sessione->id;
                        $tutteDomande      = SondaggiDomande::find()->andWhere(['sondaggi_domande_pagine_id' => $idPagina]);
                        $risposteWithFiles = [];
                        foreach ($tutteDomande->all() as $precompilaRisposte) {
                            $rispostaDomandaQuery          = SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => $precompilaRisposte['id']])->andWhere([
                                'sondaggi_risposte_sessioni_id' => $nonCompletato]);
                            $rispostaDomandaWithFilesQuery = clone $rispostaDomandaQuery;
                            $risposteWithFiles             = ArrayHelper::merge($risposteWithFiles,
                                    $rispostaDomandaWithFilesQuery->andWhere(['sondaggi_risposte.sondaggi_domande_id' => $domandeWithFilesIds])->all());
                            $rispostaDomandaCount          = $rispostaDomandaQuery->count();
                            if ($rispostaDomandaCount == 1) {
                                $rispostaDomanda = $rispostaDomandaQuery->one();
                                if ($rispostaDomanda['risposta_libera'] != null) {
                                    $idDom            = "domanda_".$precompilaRisposte['id'];
                                    $newModel->$idDom = $rispostaDomanda['risposta_libera'];
                                } else {
                                    $idDom            = "domanda_".$precompilaRisposte['id'];
                                    $newModel->$idDom = $rispostaDomanda['sondaggi_risposte_predefinite_id'];
                                }
                            } else if ($rispostaDomandaCount > 1) {
                                $arrRisposte = [];
                                foreach ($rispostaDomandaQuery->all() as $risposteSingole) {
                                    $arrRisposte[] = $risposteSingole['sondaggi_risposte_predefinite_id'];
                                }
                                $idDom            = "domanda_".$precompilaRisposte['id'];
                                $newModel->$idDom = $arrRisposte;
                            }
                        }

                        return $this->render('/pubblicazione/compila',
                                ['model' => $newModel, 'idPagina' => $idPagina, 'idSessione' => $nonCompletato, 'id' => $id,
                                'utente' => $utente, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
                    } else {//se non esistono risposte date al sondaggio
                        $newModel = null;
                        $percorso = ($this->percorso_model.$id."\\Pagina_".$primaPagina);
                        if (class_exists($percorso)) {
                            $newModel = new $percorso;
                            return $this->render('/pubblicazione/compila',
                                    ['model' => $newModel, 'idPagina' => $primaPagina, 'idSessione' => $nonCompletato, 'id' => $id,
                                    'utente' => $utente, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
                        } else {
                            return $this->redirect(['/sondaggi/sondaggi-domande-pagine/index', 'idSondaggio' => $id]);
                        }
                    }
                } else {//Se non esiste un sondaggio incompleto da completare
                    if (($inCorso->count() < $this->model->compilazioni_disponibili || $this->model->compilazioni_disponibili
                        == 0) && (($this->model->abilita_criteri_valutazione == 1 && $valutatori < $this->model->n_max_valutatori)
                        || $this->model->n_max_valutatori == 0 || $this->model->abilita_criteri_valutazione == 0)) {
                        $idSondaggio           = $id;
                        $sessione              = new SondaggiRisposteSessioni();
                        $sessione->begin_date  = date('Y-m-d H:i:s');
                        $sessione->end_date    = null;
                        $sessione->sondaggi_id = $id;
                        $sessione->user_id     = $utente;
                        $sessione->lang = $language;
                        $sessione->field_extra = $field_extra;
                        $sessione->save();
                        $idSessione            = $sessione->id;
                        $modelloPagina         = $this->percorso_model.$id."\\Pagina_".$primaPagina;
                        $pagina                = new $modelloPagina;
                        return $this->render('/pubblicazione/compila',
                                ['model' => $pagina, 'idSessione' => $idSessione, 'idPagina' => $idPagina, 'utente' => $utente,
                                'id' => $id, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
                    } else {
                        return $this->render($pageNonCompilabile,
                                ['url' => $url, 'pubblicazioni' => $this->model->getSondaggiPubblicaziones(), 'language' => $language, 'field_extra' => $field_extra]);
                    }
                }
            }
        }
    }

    /**
     * @param $id
     * @param null $idPagina
     * @param null $utente
     * @param null $idSessione
     * @param null $accesso
     * @param null $url
     * @param null $urlLive
     * @param bool $isSondaggioLive
     * @return mixed
     */
    public function actionCompilaSenzaLayout($id, $idPagina = null, $utente = null, $idSessione = null, $accesso = null,
                                  $url = null, $urlLive = null, $isSondaggioLive = false, $language = null, $field_extra = null)
    {
        $renderAction = 'renderAjax';
        if($isSondaggioLive){
            $renderAction = 'renderPartial';
        }
        $pageNonCompilabile = '/pubblicazione/non_compilabile_frontend';
        $thankYouPage       = '/pubblicazione/compilato_frontend';
        if(!empty($urlLive)){
            $thankYouPage = $urlLive;
        }
        $thankYouPageisUrl  = false;
        $this->layout       = '@frontend/views/layouts/main';
        $pathFront          = \Yii::getAlias("@common/uploads/");
       // ModuleRisultatiFrontendAsset::register(\Yii::$app->getView());
        if (!$utente) {
            $utente = Yii::$app->getUser()->getId();
        }
        $this->model        = Sondaggi::findOne(['id' => $id]);


        if($this->model->sondaggio_chiuso_frontend == 1 && !empty($this->model->thank_you_page_sondaggio_chiuso)){
            return $this->redirect($this->model->thank_you_page_sondaggio_chiuso);
        }
        if (!empty(trim($this->model->thank_you_page))) {
            if (strpos($this->model->thank_you_page, '@') === 0) {
              //  $thankYouPage = \Yii::getAlias($this->model->thank_you_page);
            } else {
             //   $thankYouPage      = $this->model->thank_you_page;
               // $thankYouPageisUrl = true;
            }
        }
        $pagineQuery         = $this->model->getSondaggiDomandePagines()->orderBy('ordinamento, id ASC');
        $pagine              = $pagineQuery->all();
        $primaPagina         = $pagine[0]['id'];
        $ultimaPagina        = $pagine[$pagineQuery->count() - 1]['id'];
        $prossimaPagina      = null;
        $arrayPag            = [];
        $completato          = false;
        $domandeWithFilesIds = [];
        foreach ($pagine as $Pag) {
            $arrayPag[] = $Pag['id'];
        }
        if ($idPagina) {
            if ($idPagina != $ultimaPagina) {
                $idPag          = array_search($idPagina, $arrayPag);
                $prossimaPagina = $arrayPag[$idPag + 1];
            }
        } else {
            $idPagina       = $primaPagina;
            $idPag          = array_search($primaPagina, $arrayPag);
            $prossimaPagina = (isset($arrayPag[$idPag + 1])) ? $arrayPag[$idPag + 1] : 0;
        }
        $risposteWithFiles = [];
        if ($primaPagina) {
            $paginaSondaggio        = SondaggiDomandePagine::findOne($primaPagina);
            $query                  = $paginaSondaggio->getSondaggiDomandesWithFiles();
            $risposteWithFiles      = [];
            $domandeWithFilesModels = $query->all();
            foreach ((Array) $domandeWithFilesModels as $domandaSondaggio) {
                $domandeWithFilesIds []        = $domandaSondaggio->id;
                $risposta                      = new SondaggiRisposte();
                $risposta->sondaggi_domande_id = $domandaSondaggio->id;
                $risposteWithFiles []          = $risposta;
            }
        }
        $valutatori = 0;
        if ($this->model->abilita_criteri_valutazione == 1) {
            $valutatori = SondaggiRisposteSessioni::find()->andWhere(['sondaggi_id' => $id])->count();
        }


        if (Yii::$app->request->isPost) {
            $data     = Yii::$app->request->post();
            $idPagina = $data['idPagina'];
            if ($idPagina != $ultimaPagina) {
                $idPag          = array_search($idPagina, $arrayPag);
                $prossimaPagina = $arrayPag[$idPag + 1];
            } else {
                $completato = true;
            }
            // $utente      = $data['utente'];
            $idSessione  = $data['idSessione'];
            $percorso    = $this->percorso_model.$id."\\Pagina_".$idPagina;
            $percorsoNew = $this->percorso_model.$id."\\Pagina_".$prossimaPagina;
            $newModel    = new $percorso;
            $newModel->session_id = $idSessione;
            if ($newModel->load($data) && $newModel->validate()) {
                $newModel->save($idSessione, $accesso, $completato);

                if ($completato) {
                    $path        = (!empty($pathFront) ? $pathFront : "uploads/")."Sondaggio_compilato".$idSessione.'_'.time().".pdf";
                    $user        = null;
                    $dati_utente = $this->getCampiMappati($this->model, $idSessione);
                    $this->generateSondaggiPdf($idSessione, $this->model->id, $path, $dati_utente);
                    if ($this->model->abilita_registrazione == 1) {
                        $user              = $this->registerUser($this->model->id, $idSessione);
                        $sessioneSondaggio = SondaggiRisposteSessioni::find()->andWhere(['id' => $idSessione])->one();
                        if (!empty($sessioneSondaggio) && !empty($user['user'])) {
                            $sessioneSondaggio->user_id = $user['user']->id;
                            $sessioneSondaggio->save(false);
                        }
                    }
                    if (empty($this->model->send_pdf_via_email)) {
                        $path = null;
                    }
                   /*$this->sendEmailSondaggioCompilato($this->model, $idSessione, $path,
                        (!empty($user['user']) ? $user['user'] : []),
                        ($this->model->abilita_registrazione ? null : $dati_utente), $user['new']);*/

                    return $this->redirect(\Yii::$app->request->referrer . '#sondaggio_html');
                        return $this->renderAjax('/pubblicazione/compilato_frontend',
                                ['url' => $url, 'pubblicazioni' => $this->model->getSondaggiPubblicaziones(), 'forzato' => $this->model->forza_lingua, 'sondaggio' => $this->model, 'language' => $language, 'field_extra' => $field_extra]);
                } else {
                    $prossimoModel = new $percorsoNew;
                    return $this->$renderAction('/pubblicazione/compila',
                            ['model' => $prossimoModel, 'idSessione' => $idSessione, 'idPagina' => $prossimaPagina, 'utente' => $utente,
                            'id' => $id, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'urlLive' => \yii\helpers\Url::current(), 'language' => $language, 'field_extra' => $field_extra]);
                }
            } else {
                return $this->$renderAction('/pubblicazione/compila',
                        ['model' => $newModel, 'idSessione' => $idSessione, 'idPagina' => $idPagina, 'utente' => $utente,
                        'id' => $id, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
            }
        } else {
            $inCorso = SondaggiRisposteSessioni::find()->andWhere(['sondaggi_id' => $id])->andWhere(['id' => $idSessione]);
            if ($inCorso->count() == 0) {
                if ($primaPagina) {
                    $paginaSondaggio = SondaggiDomandePagine::findOne($primaPagina);
                    $query           = $paginaSondaggio->getSondaggiDomandesWithFiles();

                    foreach ($query->all() as $domandaSondaggio) {
                        $risposta                      = new SondaggiRisposte();
                        $risposta->sondaggi_domande_id = $domandaSondaggio->id;
                        $risposteWithFiles []          = $risposta;
                    }
                }

                $idSondaggio           = $id;
                $sessione              = new SondaggiRisposteSessioni();
                $sessione->begin_date  = date('Y-m-d H:i:s');
                $sessione->end_date    = null;
                $sessione->sondaggi_id = $id;
                $sessione->user_id     = $utente;
                $sessione->save();
                $idSessione            = $sessione->id;
                $modelloPagina         = $this->percorso_model.$id."\\Pagina_".$primaPagina;
                $pagina                = new $modelloPagina;

                return $this->$renderAction('@vendor/open20/amos-sondaggi/src/views/pubblicazione/compila',
                        ['model' => $pagina, 'idSessione' => $idSessione, 'idPagina' => $idPagina, 'utente' => $utente, 'id' => $id,
                        'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
            } else {
                $nonCompletato = 0;
                foreach ($inCorso->all() as $InCorso) {
                    if ($InCorso['completato'] == 0) {
                        $nonCompletato = $InCorso['id'];
                    }
                }
                if ($nonCompletato) {
                    if ($primaPagina) {
                        $paginaSondaggio     = SondaggiDomandePagine::findOne($primaPagina);
                        $query               = $paginaSondaggio->getSondaggiDomandesWithFiles();
                        $domandeWithFilesIds = [];
                        foreach ($query->all() as $domandaSondaggio) {
                            $domandeWithFilesIds [] = $domandaSondaggio->id;
                        }
                    }
                    $sessione = SondaggiRisposteSessioni::findOne(['id' => $nonCompletato]);
                    $risposte = $sessione->getSondaggiRispostes();
                    if ($risposte->count() > 0) {
                        //se esistono risposte date al sondaggio
                        $arrDomande = [];
                        foreach ($risposte->all() as $risposta) {
                            $arrDomande[] = $risposta['sondaggi_domande_id'];
                        }
                        $domande  = SondaggiDomande::find()->andWhere(['IN', 'id', $arrDomande])->orderBy('ordinamento ASC');
                        $idPagina = $domande->all()[$domande->count() - 1]['sondaggi_domande_pagine_id'];
                        if ($idPagina != $ultimaPagina) {
                            $idPag          = array_search($idPagina, $arrayPag);
                            $prossimaPagina = $arrayPag[$idPag + 1];
                        }
                        $percorso          = $this->percorso_model.$id."\\Pagina_".$idPagina;
                        $newModel          = new $percorso;
                        $newModel->session_id = $sessione->id;
                        $tutteDomande      = SondaggiDomande::find()->andWhere(['sondaggi_domande_pagine_id' => $idPagina]);
                        $risposteWithFiles = [];
                        foreach ($tutteDomande->all() as $precompilaRisposte) {
                            $rispostaDomandaQuery          = SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => $precompilaRisposte['id']])->andWhere([
                                'sondaggi_risposte_sessioni_id' => $nonCompletato]);
                            $rispostaDomandaWithFilesQuery = clone $rispostaDomandaQuery;
                            $risposteWithFiles             = ArrayHelper::merge($risposteWithFiles,
                                    $rispostaDomandaWithFilesQuery->andWhere(['sondaggi_risposte.sondaggi_domande_id' => $domandeWithFilesIds])->all());
                            $rispostaDomandaCount          = $rispostaDomandaQuery->count();
                            if ($rispostaDomandaCount == 1) {
                                $rispostaDomanda = $rispostaDomandaQuery->one();
                                if ($rispostaDomanda['risposta_libera'] != null) {
                                    $idDom            = "domanda_".$precompilaRisposte['id'];
                                    $newModel->$idDom = $rispostaDomanda['risposta_libera'];
                                } else {
                                    $idDom            = "domanda_".$precompilaRisposte['id'];
                                    $newModel->$idDom = $rispostaDomanda['sondaggi_risposte_predefinite_id'];
                                }
                            } else if ($rispostaDomandaCount > 1) {
                                $arrRisposte = [];
                                foreach ($rispostaDomandaQuery->all() as $risposteSingole) {
                                    $arrRisposte[] = $risposteSingole['sondaggi_risposte_predefinite_id'];
                                }
                                $idDom            = "domanda_".$precompilaRisposte['id'];
                                $newModel->$idDom = $arrRisposte;
                            }
                        }

                        return $this->$renderAction('/pubblicazione/compila',
                                ['model' => $newModel, 'idPagina' => $idPagina, 'idSessione' => $nonCompletato, 'id' => $id,
                                'utente' => $utente, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
                    } else {//se non esistono risposte date al sondaggio
                        $newModel = null;
                        $percorso = ($this->percorso_model.$id."\\Pagina_".$primaPagina);
                        if (class_exists($percorso)) {
                            $newModel = new $percorso;
                            return $this->$renderAction('/pubblicazione/compila',
                                    ['model' => $newModel, 'idPagina' => $primaPagina, 'idSessione' => $nonCompletato, 'id' => $id,
                                    'utente' => $utente, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
                        } else {
                            return $this->redirect(['/sondaggi/sondaggi-domande-pagine/index', 'idSondaggio' => $id]);
                        }
                    }
                } else {//Se non esiste un sondaggio incompleto da completare
                    if (($inCorso->count() < $this->model->compilazioni_disponibili || $this->model->compilazioni_disponibili
                        == 0) && (($this->model->abilita_criteri_valutazione == 1 && $valutatori < $this->model->n_max_valutatori)
                        || $this->model->n_max_valutatori == 0 || $this->model->abilita_criteri_valutazione == 0)) {
                        $idSondaggio           = $id;
                        $sessione              = new SondaggiRisposteSessioni();
                        $sessione->begin_date  = date('Y-m-d H:i:s');
                        $sessione->end_date    = null;
                        $sessione->sondaggi_id = $id;
                        $sessione->user_id     = $utente;
                        $sessione->save();
                        $idSessione            = $sessione->id;
                        $modelloPagina         = $this->percorso_model.$id."\\Pagina_".$primaPagina;
                        $pagina                = new $modelloPagina;
                        return $this->$renderAction('/pubblicazione/compila',
                                ['model' => $pagina, 'idSessione' => $idSessione, 'idPagina' => $idPagina, 'utente' => $utente,
                                'id' => $id, 'risposteWithFiles' => $risposteWithFiles, 'ultimaPagina' => $ultimaPagina, 'language' => $language, 'field_extra' => $field_extra]);
                    } else {
                        return $this->$renderAction($pageNonCompilabile,
                                ['url' => $url, 'pubblicazioni' => $this->model->getSondaggiPubblicaziones(),  'language' => $language, 'field_extra' => $field_extra]);
                    }
                }
            }
        }
    }

    /**
     * @param $model LandingPremioLombardiaRicerca2019
     */
    protected function registerUser($sondaggioId, $idSessione)
    {
        $community = null;
        $model     = Sondaggi::findOne($sondaggioId);
        $arrMap    = [];
        $new       = false;

        if (!empty($model->mail_conf_community_id)) {
            $community = \open20\amos\community\models\Community::findOne($model->mail_conf_community_id);
        }
        $campiMappati = $this->getCampiMappati($model, $idSessione);
        $user         = User::find()->andWhere(['email' => $campiMappati['email']])->one();

//         creo un nuovo utente
        if (empty($user)) {
            $new  = true;
            UserProfileUtility::createNewAccount($campiMappati['nome'], $campiMappati['cognome'],
                $campiMappati['email'], 1, (($model->mail_registrazione_custom == 1) ? false : true), null, null,
                'amosadmin');
            $user = User::find()->andWhere(['email' => $campiMappati['email']])->one();

            if (!empty($user)) {
                $forceLang                = (empty($model->forza_lingua) ? \Yii::$app->language : $model->forza_lingua);
                $this->associateLangUser($user->id, $forceLang);
                /** @var  $profile UserProfile */
                $profile                  = $user->userProfile;
                $profile->facilitatore_id = 4104; // user_id
                if (!empty($community)) {
                    //$profile->first_access_mail_url = '/community/join?id='.$model->mail_conf_community_id;
                    $profile->first_access_redirect_url = \Yii::$app->params['platform']['frontendUrl'].'/sondaggi/frontend/send-mail-after-login?userId='.$user->id.'&sondaggioId='.$model->id.'&redirect=/community/join?id='.$model->mail_conf_community_id;
                }
                $profile->privacy = 1;
                // print_r(get_class($user));die;
                $profile->save(false);

                if (!empty($community)) {
                    $this->registerToCommunity($community, $user);
                }
                if ($model->mail_registrazione_custom == 1) {
                    $this->sendMailNuovoUtenteCustom($campiMappati['email'], $model, $user);
                }
            }
        } else {
            $forceLang = (empty($model->forza_lingua) ? \Yii::$app->language : $model->forza_lingua);
            $this->associateLangUser($user->id, $forceLang);
            if (!empty($community)) {
                $this->registerToCommunity($community, $user);
            }
        }

        return ['user' => $user, 'new' => $new];
    }

    public function sendEmailSondaggioCompilato($model, $idSessione, $path = null, $utente = null, $dati_utente = null,
                                                $new = false)
    {
        $risposteSessioni = SondaggiRisposteSessioni::findOne($idSessione);
        $userDefault      = null;
        $from             = null;
        $users            = [];
        if (!empty($risposteSessioni->user)) {
            $users[] = $risposteSessioni->user;
        }

        $additionalEmails = [];
        if (!empty($model->additional_emails)) {
            $additionalEmails = explode(';', $model->additional_emails);
        }

        $compilatore = !empty($users[0]) ? $users[0] : null;

        $nome    = '';
        $cognome = '';
        if (!empty($compilatore)) {
            $nome    = $compilatore->userProfile->nome;
            $cognome = $compilatore->userProfile->cognome;
            if ($new == true) {
                $from    = $model->mail_mittente_nuovo_utente;
                $subject = $model->mail_soggetto_nuovo_utente;
                $message = \Yii::$app->formatter->asNtext($model->mail_contenuto_nuovo_utente);
            } else {
                $from    = $model->mail_mittente_utente_presente;
                $subject = $model->mail_soggetto_utente_presente;
                $message = \Yii::$app->formatter->asNtext($model->mail_contenuto_utente_presente);
            }
        } else if (!empty($dati_utente)) {
            if (isset($dati_utente['nome'])) {
                $nome = $dati_utente['nome'];
            }
            if (isset($dati_utente['cognome'])) {
                $cognome = $dati_utente['cognome'];
            }
            if ($new == true) {
                $from    = $model->mail_mittente_nuovo_utente;
                $subject = $model->mail_soggetto_nuovo_utente;
                $message = \Yii::$app->formatter->asNtext($model->mail_contenuto_nuovo_utente);
            } else {
                $from    = $model->mail_mittente_utente_presente;
                $subject = $model->mail_soggetto_utente_presente;
                $message = \Yii::$app->formatter->asNtext($model->mail_contenuto_utente_presente);
            }
        }

        $link = \Yii::$app->params['platform']['backendUrl'].'/community/join?id='.$model->mail_conf_community_id;
        if (!empty(trim($link))) {
            $linkAll = '<a href="'.$link.'">'.AmosSondaggi::t('amossondaggi', 'link').'</a>';
            $message = str_replace('{{{link}}}', $linkAll, $message);
            $message = str_replace('{{{link_esteso}}}', $link, $message);
            $message = str_replace('{{{nome}}}', $nome, $message);
            $message = str_replace('{{{cognome}}}', $cognome, $message);
        }

        if (empty($path)) {
            $files = [];
        } else {
            $files = [$path];
        }

        foreach ($users as $user) {
            if (!in_array($user->email, $additionalEmails)) {
                if ($model->send_pdf_via_email == 1 || empty($new)) {
                    SondaggiUtility::sendEmailGeneral([$user->email], $user, $subject, $message, $files, $from);
                }
            }
        }

        if ($model->send_pdf_via_email == 1) {
            if (!empty($dati_utente) && !empty($dati_utente['email'])) {
                if (!in_array($dati_utente['email'], $additionalEmails)) {
                    SondaggiUtility::sendEmailGeneral([$dati_utente['email']], $utente, $subject, $message, $files, $from);
                }
            }

            foreach ($additionalEmails as $email) {
                $user = User::find()->andWhere(['email' => $email])->one();
                if (!empty($email)) {
                    SondaggiUtility::sendEmailGeneral([trim($email)], $user, $subject, $message, $files, $from);
                }
            }
        }
    }

    /**
     *
     * @param type $email
     * @param type $model
     * @param type $user
     * @return type
     */
    public function sendMailNuovoUtenteCustom($email, $model, $user = null)
    {
        $appLink = \Yii::$app->params['platform']['backendUrl'].'/';
        $link    = '';
        $result  = false;

        /** @var \open20\amos\emailmanager\AmosEmail $mailModule */
        $mailModule = \Yii::$app->getModule("email");
        if (isset($mailModule)) {
            $from = $model->mail_registrazione_mittente;

            $tos     = $email;
            $nome    = '';
            $cognome = '';
            if ($user) {
                $user->generatePasswordResetToken();
                $user->save(false);
                $link    = $appLink.'admin/security/insert-auth-data?token='.$user->password_reset_token;
                $nome    = $user->userProfile->nome;
                $cognome = $user->userProfile->cognome;
            }

            $subject = $model->mail_registrazione_soggetto;
            $text    = \Yii::$app->formatter->asNtext($model->mail_registrazione_corpo);


            $linkAll = '<a href="'.$link.'">'.AmosSondaggi::t('amossondaggi', 'link').'</a>';
            $text    = str_replace('{{{link}}}', $linkAll, $text);
            $text    = str_replace('{{{link_esteso}}}', $link, $text);
            $text = str_replace('{{{nome}}}', $nome, $text);
            $text = str_replace('{{{cognome}}}', $cognome, $text);


            $mailModule->defaultLayout = 'layout_without_footer';
            $result                    = $mailModule->send($from, $tos, $subject, $text, [], [], []);
        }
        return $result;
    }

    public function actionSendMailAfterLogin($userId, $sondaggioId, $redirect)
    {
        $appLink    = \Yii::$app->params['platform']['backendUrl'].'/';
        $link       = '';
        $result     = false;
        /** @var \open20\amos\emailmanager\AmosEmail $mailModule */
        $mailModule = \Yii::$app->getModule("email");
        if (isset($mailModule)) {
            $sondaggio = Sondaggi::findOne($sondaggioId);
            $user      = User::findOne($userId);
            if (!empty($sondaggio) && !empty($sondaggio->mail_conf_community) && !empty($sondaggio->mail_conf_community_id)
                && !empty($user)) {
                $from = $sondaggio->mail_conf_community_mittente;
                $tos  = $user->email;

                $link = $appLink.'community/join?id='.$sondaggio->mail_conf_community_id;

                $subject                   = $sondaggio->mail_conf_community_soggetto;
                $text                      = $sondaggio->mail_conf_community_corpo;
                $linkAll                   = '<a href="'.$link.'">'.AmosSondaggi::t('amossondaggi', 'link').'</a>';
                $text                      = str_replace('{{{link}}}', $linkAll, $text);
                $text                      = str_replace('{{{link_esteso}}}', $link, $text);
                $mailModule->defaultLayout = 'layout_without_footer';
                $result                    = $mailModule->send($from, $tos, $subject, $text, [], [], []);
                return $this->redirect($link);
            }
        }
        return $this->redirect($redirect);
    }

    /**
     *
     * @param type $model
     * @param type $idSessione
     * @return type
     */
    protected function getCampiMappati($model, $idSessione)
    {
        $domande = $model->getSondaggiDomandes()->andWhere(['is not', 'sondaggi_map_id', null]);
        foreach ($domande->asArray()->all() as $k) {
            $arrMap[$k['sondaggi_map_id']] = $k['id'];
        }
        $campi    = \open20\amos\sondaggi\models\SondaggiMap::find()->asArray()->all();
        $campiArr = [];
        foreach ($campi as $v) {
            $campiArr[$v['id']] = $v['campo'];
        }
        $allCampi = [];
        $nome     = SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => $arrMap[array_search('nome', $campiArr)]])->andWhere([
                'sondaggi_risposte_sessioni_id' => $idSessione])->one();
        $cognome  = SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => $arrMap[array_search('cognome',
                    $campiArr)]])->andWhere(['sondaggi_risposte_sessioni_id' => $idSessione])->one();
        //print_r($cognome->risposta_libera);die;
        $email    = SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => $arrMap[array_search('email', $campiArr)]])->andWhere([
                'sondaggi_risposte_sessioni_id' => $idSessione])->one();
        return['nome' => $nome->risposta_libera, 'cognome' => $cognome->risposta_libera, 'email' => $email->risposta_libera];
    }

    /**
     * @param $email
     */
    public function isEmailRegisteredInPoi($email)
    {
        $user = User::find()->andWhere(
                ['LIKE', 'email', $email]
            )->one();
        return !empty($user);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserRegisteredInPoi($email)
    {
        $user = User::find()->andWhere(
                ['LIKE', 'email', $email]
            )->one();
//        $user = User::find()->andWhere(['OR',
//            ['LIKE', 'email', $email],
//            ['LIKE', 'username', $email]
//        ])->one();
        return $user;
    }

    /**
     *
     * @param integer $user_id
     * @param string $language Default en-GB
     */
    public function associateLangUser($user_id, $language = 'it-IT')
    {
        $preference = \open20\amos\translation\models\TranslationUserPreference::find()->andWhere(['user_id' => $user_id]);
        if ($preference->count()) {
            $model       = $preference->one();
            $model->lang = $language;
            $model->save(false);
        } else {
            $newPreference          = new \open20\amos\translation\models\TranslationUserPreference();
            $newPreference->user_id = $user_id;
            $newPreference->lang    = $language;
            $newPreference->save(false);
        }
        \Yii::$app->language = $language;
    }

    /**
     * @param $community
     * @param $user
     * @return bool
     */
    public function registerToCommunity($community, $user)
    {
        if ($community) {
            $moduleCommunity = \Yii::$app->getModule('community');
            if ($moduleCommunity) {
                $count = \open20\amos\community\models\CommunityUserMm::find()->andWhere(['user_id' => $user->id,
                        'community_id' => $community->id])->count();
                if ($count == 0) {
                    $moduleCommunity->createCommunityUser($community->id,
                        \open20\amos\community\models\CommunityUserMm::STATUS_ACTIVE,
                        \open20\amos\community\models\CommunityUserMm::ROLE_PARTICIPANT, $user->id);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function generateSondaggiPdf($id, $sondaggioId, $path = null, $dati_utente = null)
    {
        $this->model = Sondaggi::findOne(['id' => $sondaggioId]);
        $xlsData     = [];

        // INTESTAZIONE EXCEL
        $xlsData[0] = ["Nome", "Cognome", "Email"];
        $domande    = $this->model->getSondaggiDomandes()->orderBy('ordinamento ASC')->all();
        $count      = 1;
        foreach ($domande as $domanda) {
            $xlsData[0][] = "D.".$count." ".$domanda->domanda;
            $count ++;
        }


        // CORPO FILE EXCEL
        $sondRisposta = SondaggiRisposteSessioni::find()
            ->distinct()
            ->leftJoin('user_profile', 'user_profile.user_id = sondaggi_risposte_sessioni.user_id')
            ->joinWith('user', 'user.id = user_profile.user_id')
            ->leftJoin('sondaggi_risposte',
                'sondaggi_risposte_sessioni.id = sondaggi_risposte.sondaggi_risposte_sessioni_id')
            ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $sondaggioId])
            ->andWhere(['sondaggi_risposte_sessioni.id' => $id])
//            ->andWhere(['user_profile.user_id' => \Yii::$app->user->id])
            ->one();

        $row     = 1;
        $profile = null;
        if (!empty($sondRisposta->user_id)) {
            /** @var AmosAdmin $adminModule */
            $adminModule = AmosAdmin::instance();
            /** @var UserProfile $userProfileModel */
            $userProfileModel = $adminModule->createModel('UserProfile');
            $profile = $userProfileModel::find()->andWhere(['user_id' => $sondRisposta->user_id])->one();
        }
        $session_id = $sondRisposta->id;
        $dateDiff = (new \DateTime())->diff(new \DateTime($sondRisposta->updated_at));
        if (!empty($profile)) {
            $xlsData [$row][0] = $profile->nome;
            $xlsData [$row][1] = $profile->cognome;
            $xlsData [$row][2] = $profile->user->email;
        }elseif (!empty($dati_utente)) {
            $xlsData [$row][0] = $dati_utente['nome'];
            $xlsData [$row][1] = $dati_utente['cognome'];
            $xlsData [$row][2] = $dati_utente['email'];
        }
        if (($dateDiff->invert * $dateDiff->days) > 730 && AmosSondaggi::instance()->resetGdpr) {
            $xlsData [$row][0] = "#####";
            $xlsData [$row][1] = "#####";
            $xlsData [$row][2] = "#####";
        }

        /** @var  $domanda SondaggiDomande */
        $colum = 3;
        foreach ($domande as $domanda) {
            $query = $domanda->getRispostePerUtente((!empty($profile) ? $profile->user_id : null), $session_id);
            // RISPOSTE LIBERE
            if ($domanda->sondaggi_domande_tipologie_id == 6 || $domanda->sondaggi_domande_tipologie_id == 5) {
//                    pr($query->one()->risposta_libera, 'D. ' . $domanda->id);
                $risposta = $query->one();
                if ($risposta) {
                    $xlsData [$row][$colum] = $risposta->risposta_libera;
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
                        $attachments    = $risposta->$attribute;
                        $listAttachUrls = [];
                        $risposteString = "<ul>";

                        if ($attachments instanceof File) {
                            $risposteString .= "<li><a href='" . \Yii::$app->params['platform']['backendUrl'] . $attachments->getUrl() . "'>" . $attach->name . "</a></li>";
                        } elseif(is_array($attachments)) {
                            /** @var  $attach File */
                            foreach ($attachments as $attach) {
                                $risposteString .= "<li><a href='" . \Yii::$app->params['platform']['backendUrl'] . $attach->getUrl() . "'>" . $attach->name . "</a></li>";
                            }
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

                $xlsData [$row][$colum] = "<ul><li>".implode("</li><li>", $risposteArray)."</li></ul>";

//                    pr(implode(',', $risposteArray), 'D. ' . $domanda->id);
            }
            $colum++;
        }
        return $this->savePdf($xlsData, $this->model, $sondRisposta, $path);
    }

    /**
     * @param $data
     * @param $modelSondaggio
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function savePdf($data, $modelSondaggio, $modelSondaggioRisposta, $path = null)
    {
        $content = $this->renderPartial('@vendor/open20/amos-sondaggi/src/views/sondaggi/_view_pdf',
            [
            'data' => $data,
            'sondaggio' => $modelSondaggio,
            'rispostaModel' => $modelSondaggioRisposta
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
            'methods' => [
                'SetFooter' => ['{PAGENO}']
            ],
        ]);

//        $pdf->getApi()->SetHTMLFooter($footer);
        $pdf->getApi()->SetMargins(0, 0, 20);
//        $pdf->getApi()->SetAutoPageBreak(TRUE, 25);
        $pdf->getApi()->margin_header = '6px';
        $pdf->getApi()->margin_footer = '10px';

        if (!empty($path)) {
            return $pdf->output($content, $path, Pdf::DEST_FILE);
        } else {
            return $pdf->output($content, "Sondaggio_compilato.pdf", Pdf::DEST_DOWNLOAD);
        }
    }
}
