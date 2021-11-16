<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\models\File;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\i18n\grammar\SondaggiGrammar;
use open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use open20\amos\sondaggi\models\SondaggiMap;
use open20\amos\sondaggi\models\SondaggiInvitations;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\seo\interfaces\SeoModelInterface;
use yii\helpers\VarDumper;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;

/**
 * Class Sondaggi
 * This is the model class for table "sondaggi".
 *
 * @method \cornernote\workflow\manager\components\WorkflowDbSource getWorkflowSource()
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 *
 * @package open20\amos\sondaggi\models
 */
class Sondaggi extends \open20\amos\sondaggi\models\base\Sondaggi implements NewsletterInterface, SeoModelInterface
{
    // Workflow ID
    const WORKFLOW                   = 'SondaggiWorkflow';
    // Workflow statuses IDs
    const WORKFLOW_STATUS_BOZZA      = 'SondaggiWorkflow/BOZZA';
    const WORKFLOW_STATUS_DAVALIDARE = 'SondaggiWorkflow/DAVALIDARE';
    const WORKFLOW_STATUS_VALIDATO   = 'SondaggiWorkflow/VALIDATO';
    const SONDAGGI_LIVE_CHART_PIE    = 1;
    const SONDAGGI_LIVE_CHART_COLUMN = 2;

    const FRONTEND_COMPILE_COOKIE_NAME = 'FILL_Q';
    const ROOT_TAG_CUSTOM_POLLS = 'root_polls_tag_custom';

    //public $regola_pubblicazione;
    //public $destinatari;
    //public $validatori;
    public $file;
    public $destinatari_pubblicazione;
    public $tipologie_entita;
    public $pubblico;
    public $attivita_formativa;
    public $punto_pei;
    public $mail_subject;
    public $mail_message;
    public $text_not_compilable;
    public $text_end;
    public $text_end_title;
    public $text_end_html;
    public $text_not_compilable_html;

    public $customTags;
    public $customTagsDefault;

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
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::WORKFLOW)->getInitialStatusId();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
                [
                //[['regola_pubblicazione', 'destinatari', 'validatori'], 'safe'],
                [['file'], 'file'],
                [['destinatari_pubblicazione'], 'safe'],
                //[['destinatari_pubblicazione', 'tipologie_entita'], 'required'],
                [['tipologie_entita'], 'safe']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
//      'tagValues' => '',
//      'regola_pubblicazione' => 'Pubblicata per',
//      'destinatari' => 'Per i condominii',
                'file' => AmosSondaggi::t('amossondaggi', 'Immagine'),
                'text_end_html' => AmosSondaggi::t('amossondaggi', 'Messaggio di fine sondaggio in HTML'),
                'text_not_compilable_html' => AmosSondaggi::t('amossondaggi',
                    'Messaggio di sondaggio non compilabile in HTML'),
                'mail_message' => AmosSondaggi::t('amossondaggi', 'Testo della e-mail di notifica'),
                'mail_subject' => AmosSondaggi::t('amossondaggi', 'Oggetto della e-mail di notifica'),
                'text_not_compilable' => AmosSondaggi::t('amossondaggi', 'Messaggio di sondaggio non compilabile'),
                'text_end' => AmosSondaggi::t('amossondaggi', 'Messaggio di fine sondaggio'),
                'text_end_title' => AmosSondaggi::t('amossondaggi', 'Titolo della pagina di fine sondaggio'),
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
                'NotifyBehavior' => [
                    'class' => NotifyBehavior::className(),
                    'conditions' => []
                ],
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'workflowLog' => [
                    'class' => WorkflowLogFunctionsBehavior::className()
                ],
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::className(),
                    'imageAttribute' => 'file',
                    'defaultOgType' => 'article',
                    'schema' => 'NewsArticle'
                ]
        ]);
    }
    /*
      public static function find()
      {
      $SondaggiQuery = new SondaggiQuery(get_called_class());
      $SondaggiQuery->andWhere('sondaggi.deleted_at IS NULL');
      return $SondaggiQuery;
      }
     */

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

    private function getFullUrl($url)
    {
        if (!empty($url)) {
            return Url::toRoute(["/".$url, "id" => $this->id]);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getCreateUrl()
    {
        return 'sondaggi/dashboard/create';
    }

    /**
     * @inheritdoc
     */
    public function getFullCreateUrl()
    {
        return '/'.$this->getCreateUrl();
    }

    /**
     * @inheritdoc
     */
    public function getUpdateUrl()
    {
        return 'sondaggi/dashboard/dashboard';
    }

    /**
     * @inheritdoc
     */
    public function getFullUpdateUrl()
    {
        return $this->getFullUrl($this->getUpdateUrl());
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
     * Funzione che verifica se il sondaggio è pubblicabile o meno
     * @return boolean True | False - se il sondaggio è pubblicabile restituisce true, altrimenti false
     */
    public function verificaSondaggioPubblicabile()
    {
        if (AmosSondaggi::instance()->enableInvitationList) {
            if (SondaggiInvitations::find()->andWhere(['sondaggi_id' => $this->id])->andWhere(['active' => 1])->count() == 0)
                    return false;
        }
        $verifica    = true;
        $pagine      = $this->getSondaggiDomandePagines();
        $arrMapReq   = [];
        $mapRequired = SondaggiMap::find()->andWhere(['obbligatorio' => 1])->select('id')->asArray()->all();
        if ($this->abilita_registrazione == 1) {
            foreach ($mapRequired as $v) {
                $arrMapReq[] = $v['id'];
            }
        }
        if ($pagine->count() > 0) {
            foreach ($pagine->all() as $Pagina) {
                $domande = SondaggiDomande::find()->andWhere(['sondaggi_domande_pagine_id' => $Pagina['id']]);
                if ($domande->count() > 0) {
                    foreach ($domande->all() as $Domanda) {
                        if (in_array($Domanda['sondaggi_domande_tipologie_id'], [1, 2, 3, 4, 7, 8]) && empty($Domanda->parent_id)) {
                            $risposte = SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $Domanda['id']]);
                            if ($risposte->count() == 0) {
                                $verifica = false;
                            } else {
                                if ($Domanda['min_int_multipla'] > 0) {
                                    $numRisp = $Domanda['min_int_multipla'];
                                    if ($numRisp >= $risposte->count()) {
                                        return false;
                                    }
                                }
                            }
                        }
                        if ($this->abilita_registrazione == 1) {
                            $map = $Domanda->getMapField();
                            if (!empty($arrMapReq) && !empty($map) && !empty($map->one())) {
                                if (in_array($map->one()->id, $arrMapReq)) {
                                    $key = array_search($map->one()->id, $arrMapReq);
                                    if ($key !== false) {
                                        unset($arrMapReq[$key]);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $verifica = false;
                }
            }
            if (!empty($arrMapReq)) {
                $verifica = false;
            }
            $domande = $this->getSondaggiDomandes();
        } else {
            $verifica = false;
        }
        return $verifica;
    }

    /**
     * Restituisce il numero di partecipazioni al sondaggio, se non viene specificato un'utente
     * restituisce il numero totale
     * @param integer $personale 0 | 1 se viene inserito 0 restituirà il numero totale delle risposte al sondaggio, altrimenti il numero di partecipazioni personali
     * @return integer Numero di partecipazioni inclusive di quelle parziali
     */
    public function getNumeroPartecipazioni($personale = 0)
    {
        if ($personale) {
            $utente     = \Yii::$app->getUser()->getId();
            $condition1 = new \yii\db\Expression("count(distinct(IF(sondaggi_risposte_sessioni.end_date IS NOT NULL OR sondaggi_risposte.id is not null and sondaggi_risposte_sessioni.end_date is null, sondaggi_risposte_sessioni.id, null))) partecipanti");

            $sessioni = SondaggiRisposteSessioni::find()
                ->innerJoin('sondaggi_risposte',
                    'sondaggi_risposte.sondaggi_risposte_sessioni_id = sondaggi_risposte_sessioni.id')
                ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $this->id]);

            if (AmosSondaggi::instance()->compilationToOrganization) {
                $sessioni = $sessioni->andWhere(['sondaggi_risposte_sessioni.organization_id' => $this->getOrgEntity($utente)->id]);
            } else {
                $sessioni = $sessioni->andWhere(['sondaggi_risposte_sessioni.user_id' => $this->getUserEntity($utente)->id]);
            }
            $sessioni->select($condition1);
            return $sessioni->asArray()->one()['partecipanti'];
        } else {
            $condition1 = new \yii\db\Expression("count(distinct(IF(sondaggi_risposte_sessioni.end_date IS NOT NULL OR sondaggi_risposte.id is not null and sondaggi_risposte_sessioni.end_date is null, sondaggi_risposte_sessioni.id, null))) partecipanti");

            $sessioni = SondaggiRisposteSessioni::find()
                ->innerJoin('sondaggi_risposte',
                    'sondaggi_risposte.sondaggi_risposte_sessioni_id = sondaggi_risposte_sessioni.id')
                ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $this->id])
                ->select($condition1);
            //  pr($sessioni->createCommand()->rawSql);
            return $sessioni->asArray()->one()['partecipanti'];
        }
    }

    /**
     * @param array $post
     */
    public function getOtherAttributes($post = null)
    {
        $oldValues = $this->getSondaggiPubblicaziones()->one();
        if (!empty($post)) {
            if (empty($oldValues)) {
                $oldValues              = new SondaggiPubblicazione();
                $oldValues->sondaggi_id = $this->id;
                $oldValues->ruolo       = 'PUBBLICO';
            }
            if (isset($post['Sondaggi']['mail_subject'])) {

                $oldValues->mail_subject = $post['Sondaggi']['mail_subject'];
            }
            if (isset($post['Sondaggi']['mail_message'])) {
                $oldValues->mail_message = $post['Sondaggi']['mail_message'];
            }
            if (isset($post['Sondaggi']['text_end'])) {
                $oldValues->text_end = $post['Sondaggi']['text_end'];
            }
            if (isset($post['Sondaggi']['text_end_html'])) {
                $oldValues->text_end_html = $post['Sondaggi']['text_end_html'];
            }
            if (isset($post['Sondaggi']['text_end_title'])) {
                $oldValues->text_end_title = $post['Sondaggi']['text_end_title'];
            }
            if (isset($post['Sondaggi']['text_not_compilable'])) {
                $oldValues->text_not_compilable = $post['Sondaggi']['text_not_compilable'];
            }
            if (isset($post['Sondaggi']['text_not_compilable_html'])) {
                $oldValues->text_not_compilable_html = $post['Sondaggi']['text_not_compilable_html'];
            }
            $oldValues->save(false);
        } else {
            $this->mail_subject             = $oldValues['mail_subject'];
            $this->mail_message             = $oldValues['mail_message'];
            $this->text_end                 = $oldValues['text_end'];
            $this->text_end_html            = $oldValues['text_end_html'];
            $this->text_end_title           = $oldValues['text_end_title'];
            $this->text_not_compilable      = $oldValues['text_not_compilable'];
            $this->text_not_compilable_html = $oldValues['text_not_compilable_html'];
        }
    }

    /**
     * @return bool
     * in base all'utente loggato, si recupera il numero di volte che è stato compilato il sondaggio
     * se questo è >= del valore inserito in 'compilazioni_disponibili' allora non sarà possibile compilare ulteriormente il sondaggio.
     * NB: se il valore in 'compilazioni_disponibili' è 0 (zero) si assume non ci sia limite
     */
    public function hasCompilazioniSuperate()
    {
        $utente_id = \Yii::$app->getUser()->getId();

        //se il numero di compilazioni è 0 (zero) => nessun limite di compilazione
        $compilazioni_disponibili = $this->compilazioni_disponibili;
        if ((!$compilazioni_disponibili || $compilazioni_disponibili === 0) && $this->abilita_criteri_valutazione == 0) {
            return false;
        }

        $q = $this->getTuttiPartecipanti($utente_id);

        $numero_compilazioni_x_utente = $q->count();

        $compilazioniSuperate = ($compilazioni_disponibili > 0 && $numero_compilazioni_x_utente >= $compilazioni_disponibili);
        $valutatoriSuperati   = $this->valutazioniSuperate();

        return ($compilazioniSuperate || $valutatoriSuperati);

    }

    /**
     *
     * @return boolean
     */
    public function valutazioniSuperate()
    {
        $valutatori      = 0;
        $limite_superato = false;
        if ($this->abilita_criteri_valutazione == 1) {
            $valutatori = $this->getNumeroValutatori();
            if ($this->abilita_criteri_valutazione == 1 && $valutatori >= $this->n_max_valutatori && $this->n_max_valutatori
                != 0) {
                $limite_superato = true;
            }
        }
        return $limite_superato;
    }

    /**
     *
     * @return type
     */
    public function getNumeroValutatori()
    {
        return SondaggiRisposteSessioni::find()->andWhere(['sondaggi_id' => $this->id])->count();
    }

    public function getOrgEntity($user_id)
    {
        if (!empty($user_id)) {
            // Finding all current user - organization combinations (should be only one) to check whether the poll was compiled already
            $orgResult = \open20\amos\organizzazioni\Module::getUserOrganizations($user_id);
            $userOrgs  = [];
            foreach ($orgResult as $org) {
                $userOrgs[] = $org->id;
            }
            return $this->getOrganizations()->andWhere(['profilo.id' => $userOrgs])->one();
        }
        return null;
    }

    public function getUserEntity($user_id)
    {
        if (!empty($user_id)) {
            return \open20\amos\core\user\User::find()->andWhere(['id' => $user_id])->one();
        }
        return null;
    }

    /**
     *
     * @param type $utente_id
     * @return type
     */
    public function getTuttiPartecipanti($utente_id = null)
    {
        $sondaggiRisposteTable         = SondaggiRisposte::tableName();
        $sondaggiRisposteSessioniTable = SondaggiRisposteSessioni::tableName();
        /** @var ActiveQuery $q */
        $q                             = SondaggiRisposteSessioni::find();
        $q->select($sondaggiRisposteSessioniTable.'.id, '.$sondaggiRisposteSessioniTable.'.user_id, '.$sondaggiRisposteSessioniTable.'.sondaggi_id')
            ->innerJoin($sondaggiRisposteTable,
                $sondaggiRisposteTable.'.sondaggi_risposte_sessioni_id = '.$sondaggiRisposteSessioniTable.'.id')
            ->andWhere([$sondaggiRisposteSessioniTable.'.sondaggi_id' => $this->id]);

        if (!empty($utente_id)) {
            if (AmosSondaggi::instance()->compilationToOrganization) {
                $q = $q->andWhere([$sondaggiRisposteSessioniTable.'.organization_id' => $this->getOrgEntity($utente_id)->id]);
            } else {
                $q = $q->andWhere([$sondaggiRisposteSessioniTable.'.user_id' => $this->getUserEntity($utente_id)->id]);
            }
        }
        $q = $q->groupBy($sondaggiRisposteSessioniTable.'.id');
        return $q;
    }

    public function getSondaggiRisposteSessionis()
    {
        return $this->hasMany(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(),
                ['sondaggi_id' => 'id']);
    }

    public function getSondaggiRisposteSessionisByEntityId($entityId = null)
    {
        $query = $this->hasMany(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(),
            ['sondaggi_id' => 'id']);
        if (AmosSondaggi::instance()->compilationToOrganization) {
            if (empty($entityId)) {
                $userId = \Yii::$app->getUser()->getId();
                $org = $this->getOrgEntity($userId);
                $entityId = $org->id;
            }
            $query = $query->andWhere([SondaggiRisposteSessioni::tableName().'.organization_id' => $entityId]);
        }
        else {
            if (empty($entityId)) {
                $userId = \Yii::$app->getUser()->getId();
                $entityId = $userId;
            }
            $query = $query->andWhere([SondaggiRisposteSessioni::tableName().'.user_id' => $entityId]);
        }
        return $query;
    }

    public function getSondaggiRisposteSessionisByEntity()
    {
        $userId = \Yii::$app->getUser()->getId();
        $query  = $this->hasMany(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(),
            ['sondaggi_id' => 'id']);
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $org   = $this->getOrgEntity($userId);
            $query = $query->andWhere([SondaggiRisposteSessioni::tableName().'.organization_id' => $org->id]);
        } else {
            $user  = $this->getUserEntity($userId);
            $query = $query->andWhere([SondaggiRisposteSessioni::tableName().'.user_id' => $user->id]);
        }
        return $query;
    }

    public function getLastSondaggiRisposteSessioniByEntity()
    {
        $userId = \Yii::$app->getUser()->getId();
        $query  = $this->hasOne(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(),
            ['sondaggi_id' => 'id']);
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $org   = $this->getOrgEntity($userId);
            $query = $query->andWhere([SondaggiRisposteSessioni::tableName().'.organization_id' => $org->id]);
        } else {
            $user  = $this->getUserEntity($userId);
            $query = $query->andWhere([SondaggiRisposteSessioni::tableName().'.user_id' => $user->id]);
        }
        $query->orderBy('updated_at DESC');
        return $query;
    }


    public function getEntiCheHannoCompilato()
    {
        $entiAttivi = SondaggiInvitationMm::find()->andWhere(['sondaggi_id' => $this->id])->select('to_id');
        return $this->hasMany(\open20\amos\organizzazioni\models\Profilo::className(),
                ['id' => 'organization_id'])->viaTable(SondaggiRisposteSessioni::tableName(), ['sondaggi_id' => 'id'])->andWhere([
                'in', 'profilo.id', $entiAttivi]);
    }

    public function sondaggioLiveVoted()
    {
        $userId = \Yii::$app->user->id;
        $q      = SondaggiRisposteSessioni::find()
            ->innerJoin('sondaggi_risposte',
                'sondaggi_risposte.sondaggi_risposte_sessioni_id = sondaggi_risposte_sessioni.id')
            ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $this->id])
            ->andWhere(['sondaggi_risposte_sessioni.user_id' => $userId])
            ->count();
        return $q;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "sondaggi/pubblicazione/compila";
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/".$this->getViewUrl(), "id" => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->titolo;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->descrizione;
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->descrizione;
        if ($truncate) {
            $ret = $this->__shortText($this->descrizione, 200);
        }
        return $ret;
    }

    public function getInvitationMm()
    {
        return $this->hasMany(\open20\amos\sondaggi\models\SondaggiInvitationMm::className(),
                ['sondaggi_id' => 'id']);
    }

    public function getUserInvitationMm()
    {
        return $this->hasMany(\open20\amos\sondaggi\models\SondaggiUsersInvitationMm::className(),
                ['sondaggi_id' => 'id']);
    }

    /**
     * @return Organization
     */
    public function getOrganizations()
    {
        return $this->hasMany(\open20\amos\organizzazioni\models\Profilo::className(), ['id' => 'to_id'])
                ->via('invitationMm');
    }

    public function getOrganizationUsers()
    {
        return $this->hasMany(\open20\amos\core\user\User::className(), ['id' => 'user_id'])
                ->via('userInvitationMm');
    }

    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconSondaggi::className();
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::WORKFLOW_STATUS_BOZZA;
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::WORKFLOW_STATUS_DAVALIDARE;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::WORKFLOW_STATUS_VALIDATO;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'AMMINISTRAZIONE_SONDAGGI';
    }

    /**
     * @return SondaggiGrammar
     */
    public function getGrammar()
    {
        return new SondaggiGrammar();
    }

    /**
     * @inheritdoc
     */
    public function getCwhValidationStatuses()
    {
        return [self::WORKFLOW_STATUS_VALIDATO];
    }

    /**
     * @return array
     */
    public function getStatusToRenderToHide()
    {
        $statusToRender     = [
            self::WORKFLOW_STATUS_BOZZA => AmosSondaggi::t('amossondaggi', 'Modifica in corso'),
        ];
        $isCommunityManager = false;
        if (\Yii::$app->getModule('community')) {
            $isCommunityManager = CommunityUtil::isLoggedCommunityManager();
        }
        // if you are a community manager a validator/facilitator or ADMIN you Can publish directly
        if (\Yii::$app->user->can('ADMIN') || $isCommunityManager) {
            $statusToRender  = ArrayHelper::merge($statusToRender,
                    [self::WORKFLOW_STATUS_VALIDATO => AmosSondaggi::t('amossondaggi', 'Pubblicata')]);
            $hideDraftStatus = [];
        } else {
            $statusToRender    = ArrayHelper::merge($statusToRender,
                    [
                    self::WORKFLOW_STATUS_DAVALIDARE => AmosSondaggi::t('amossondaggi', 'Richiedi pubblicazione'),
            ]);
            $hideDraftStatus[] = self::WORKFLOW_STATUS_VALIDATO;
        }

        return ['statusToRender' => $statusToRender, 'hideDraftStatus' => $hideDraftStatus];
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowBaseStatusLabel()
    {
        return AmosSondaggi::t('amossondaggi', parent::getWorkflowBaseStatusLabel());
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowStatusLabel()
    {
        return AmosSondaggi::t('amossondaggi', parent::getWorkflowStatusLabel());
    }

    /**
     * @inheritdoc
     */
    public function getModelImage()
    {
        return $this->getFile()->one();
    }

    /**
     * @inheritdoc
     */
    public function getModelImageUrl($size = 'original', $protected = true, $url = '/img/img_default.jpg',
                                     $absolute = false, $canCache = false)
    {
        /** @var File $surveyImage */
        $surveyImage = $this->getModelImage();
        if (!is_null($surveyImage)) {
            if ($protected) {
                $url = $surveyImage->getUrl($size, $absolute, $canCache);
            } else {
                $url = $surveyImage->getWebUrl($size, $absolute, $canCache);
            }
        }
        return $url;
    }

    /**
     * @return string
     */
    public function getModelImageUrlForSummaries()
    {
        return $this->getModelImageUrl('square_large', true, '/img/img_default.jpg', true, true);
    }

    /**
     * @return string
     */
    public function newsletterOrderByField()
    {
        return 'created_at';
    }

    /**
     * @return string
     */
    public function newsletterPublishedStatus()
    {
        return self::WORKFLOW_STATUS_VALIDATO;
    }

    /**
     * @param string $searchParam
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function newsletterSearchFilter($searchParam, $query)
    {
        if ($searchParam) {
            $query->andFilterWhere(['like', self::tableName().'.titolo', $searchParam]);
        }
        return $query;
    }

    /**
     * @return string
     */
    public function newsletterContentTitle()
    {
        return $this->titolo;
    }

    /**
     * @return string
     */
    public function newsletterContentTitleField()
    {
        return 'titolo';
    }

    /**
     * @return string
     */
    public function newsletterContentStatusField()
    {
        return 'status';
    }

    /**
     * @return array
     */
    public function newsletterContentGridViewColumns()
    {
        return [
            [
                'label' => $this->getAttributeLabel('file'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Sondaggi $model */
                    $url = '/img/img_default.jpg';
                    if ($model->file) {
                        $url = $model->file->getUrl('original');
                    }
                    $contentImage = Html::img($url,
                            ['class' => 'gridview-image', 'alt' => AmosSondaggi::t('amossondaggi',
                                'Immagine del sondaggio')]);
                    return $contentImage;
                }
            ],
            'titolo:ntext',
            'status' => [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var Sondaggi $model */
                    return $model->getWorkflowBaseStatusLabel();
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => AmosSondaggi::t('amossondaggi', '#published_on')
            ]
        ];
    }

    /**
     * @return array
     */
    public function newsletterSelectContentsGridViewColumns()
    {
        return [
            [
                'label' => $this->getAttributeLabel('file'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Sondaggi $model */
                    $url = '/img/img_default.jpg';
                    if ($model->file) {
                        $url = $model->file->getUrl('original');
                    }
                    $contentImage = Html::img($url,
                            ['class' => 'gridview-image', 'alt' => AmosSondaggi::t('amossondaggi',
                                'Immagine del sondaggio')]);
                    return $contentImage;
                }
            ],
            'titolo:ntext'
        ];
    }

    /**
     * @return bool
     */
    public function hasAlreadyPage()
    {
        return $this->getSondaggiDomandePagines()->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasAlreadyDomande()
    {
        return $this->getSondaggiDomandes()->count() > 0;
    }

    /**
     * @return array
     */
    public static function sondaggiLiveTypeCharts()
    {
        return [
            self::SONDAGGI_LIVE_CHART_COLUMN => AmosSondaggi::t('amossondaggi', 'Grafico a barre'),
            self::SONDAGGI_LIVE_CHART_PIE => AmosSondaggi::t('amossondaggi', 'Grafico a torta')
        ];
    }

    /**
     * @param $id
     * @param $idPagina
     * @param bool $disableTitle
     * @return string
     */
    public static function renderSondaggiLive($id, $idPagina, $disableTitle = false)
    {
        $risposte = new Risposte();

        $model       = Sondaggi::findOne(['id' => $id]);
        $pagineQuery = $model->getSondaggiDomandePagines()
            ->joinWith('sondaggiDomandes')
            ->andWhere(['IN', 'sondaggi_domande.sondaggi_domande_tipologie_id', [1, 2, 3, 4]])
            ->groupBy('sondaggi_domande_pagine.id')
            ->orderBy('sondaggi_domande_pagine.id ASC');

        $pagine         = $pagineQuery->all();
        $prossimaPagina = null;
        $arrayPag       = [];
        foreach ($pagine as $Pag) {
            $arrayPag[] = $Pag['id'];
        }

        if (!empty($model)) {
            if ($idPagina > -2) {
                return \Yii::$app->controller->view->render('@vendor/open20/amos-sondaggi/src/views/sondaggi/risultati_live',
                        [
                        'model' => $model,
                        'idPagina' => $idPagina,
                        'risposte' => $risposte->getDati($id, $idPagina),
                        //  'report' => $risposte->getReport($id, $idPagina),
                        'domande' => $risposte->getDomandeStatistiche($id, $idPagina),
                        'tipo' => $risposte->getTipologia($id),
                        'filter' => $risposte,
                        'disableTitle' => $disableTitle,
                ]);
            }
        }
        return '';
    }

    public function isCompilable()
    {
        $compilable = true;
        if ($this->frontend) {
            // Se settato questionario di frontend allora controllo un cookie
            // se l'ha termineto lo fermo, non ne deve compilare altri
            $cookieValues = $this->getFrontendCookie($this->id);
            if (isset($cookieValues['idSessione']) && !empty($cookieValues['idSessione'])) {
                $idSessione = $cookieValues['idSessione'];
                $srs = SondaggiRisposteSessioni::findOne(['id' => $idSessione]);
                if (!empty($srs) && ($srs->completato == 1)) {
                    $compilable = false;
                } else {
                    $compilable = ($this->status == self::WORKFLOW_STATUS_VALIDATO);
                }
            } else {
                $compilable = ($this->status == self::WORKFLOW_STATUS_VALIDATO);
            }
        } else {
            $compilable = !$this->hasCompilazioniSuperate() && $this->status == self::WORKFLOW_STATUS_VALIDATO;
        }

//        VarDumper::dump(((new \DateTime($this->publish_date)) > (new \DateTime())),3, true);
        if (!empty($this->publish_date) && ((new \DateTime($this->publish_date)) > (new \DateTime())) )
                $compilable = false;
        $closeDate  = new \DateTime($this->close_date);
        $closeDate->setTime(23, 59, 59);
        if (!empty($this->close_date) && $closeDate <= (new \DateTime())) $compilable = false;
        return $compilable;
    }

    /**
     *
     * @return type
     */
    public function getSchema()
    {
        $news        = new \simialbi\yii2\schemaorg\models\NewsArticle();
        $publisher   = new \simialbi\yii2\schemaorg\models\Organization();
        $author      = new \simialbi\yii2\schemaorg\models\Person();
        $userProfile = $this->createdUserProfile;
        if (!is_null($userProfile)) {
            $logo            = new \simialbi\yii2\schemaorg\models\ImageObject();
            $publisher->name = $userProfile->nomeCognome;
            $img             = $userProfile->userProfileImage;
            if (!is_null($img)) {
                $logo->url = $img->getWebUrl(false, true);
            }
            $publisher->logo = $logo;
            $author->name    = $userProfile->nomeCognome;
        }
        $image     = new \simialbi\yii2\schemaorg\models\ImageObject();
        $sondImage = $this->getFile();
        if (!empty($sondImage)) {
            $image->url = $sondImage->getWebUrl(false, true);
        }
        $news->author        = $author;
        $news->datePublished = $this->data_pubblicazione;
        $news->headline      = substr($this->getShortDescription(), 0, 110);
        $news->image         = $image;
        $news->publisher     = $publisher;

        \simialbi\yii2\schemaorg\helpers\JsonLDHelper::add($news);

        return \simialbi\yii2\schemaorg\helpers\JsonLDHelper::render();
    }

    /**
     * Return numbero of compilation with specific stauts
     * @param string|array $status
     * @param string|array $completed
     * @return int
     */
    public function getCompilazioniStatus($status = null, $completed = 0)
    {
        $condition1 = new \yii\db\Expression("count(distinct(IF(sondaggi_risposte_sessioni.end_date IS NOT NULL OR sondaggi_risposte.id is not null and sondaggi_risposte_sessioni.end_date is null, sondaggi_risposte_sessioni.id, null))) partecipanti");

        $sessioni = SondaggiRisposteSessioni::find()
            ->innerJoin('sondaggi_risposte',
                'sondaggi_risposte.sondaggi_risposte_sessioni_id = sondaggi_risposte_sessioni.id')
            ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $this->id])
            ->andWhere(['sondaggi_risposte_sessioni.status' => $status])
            ->andWhere(['sondaggi_risposte_sessioni.completato' => $completed])
            ->select($condition1);
        \Yii::debug($sessioni->createCommand()->rawSql, 'sondaggi');
        return $sessioni->asArray()->one()['partecipanti'];
    }

    /**
     *
     * @return type
     */
    public function getEntiInvitati()
    {
        return $this->hasMany(SondaggiInvitationMm::className(), ['sondaggi_id' => 'id']);
    }

    /**
     * Per nomenclatura corretta anche se fa la stessa cosa di getEntiInvitati
     *
     * @return type
     */
    public function getElementsInvitated()
    {
        return $this->hasMany(SondaggiInvitationMm::className(), ['sondaggi_id' => 'id']);
    }

    /**
     *
     * @return type
     */
    public function getEntiInvitatiNonCompilato($enti)
    {
        return $this->hasMany(SondaggiInvitationMm::className(), ['sondaggi_id' => 'id'])->andWhere(['not in', 'sondaggi_invitation_mm.to_id',
                $enti]);
    }

    /**
     *
     * @return type
     */
    public function getInvitations()
    {
        return $this->hasMany(SondaggiInvitations::className(), ['sondaggi_id' => 'id']);
    }

    /**
     * @param $idSessione
     */
    public function setFrontendCookie($idSessione = null) {
        if ($this->frontend) {

            $cookies = \Yii::$app->request->cookies;
            $arrayCookieContent = [];
            if ($cookies->has(self::FRONTEND_COMPILE_COOKIE_NAME)) {
                $arrayCookieContent = unserialize($cookies->getValue(self::FRONTEND_COMPILE_COOKIE_NAME));
            }

            // Aggiungo e se esiste sovrascrivo
            $arrayCookieContent[$this->id] = [
                'id' => $this->id,
                'idSessione' => $idSessione,
            ];

            $cookiesOnResponse = \Yii::$app->response->cookies;
            $cookiesOnResponse->add(new \yii\web\Cookie([
                'name' => self::FRONTEND_COMPILE_COOKIE_NAME,
                'value' => serialize($arrayCookieContent),
                'expire' => time() + 86400 * 365,
            ]));

        }
    }

    /**
     *
     */
    public function getFrontendCookie($idSondaggio) {
        $cookies = \Yii::$app->request->cookies;

        $cookieValue = null;
        if ($cookies->has(self::FRONTEND_COMPILE_COOKIE_NAME)) {
            $arrayCookieContent = unserialize($cookies->getValue(self::FRONTEND_COMPILE_COOKIE_NAME));
            if (isset($arrayCookieContent[$idSondaggio]) && is_array($arrayCookieContent[$idSondaggio])) {
                $cookieValue = $arrayCookieContent[$idSondaggio];
            }
        }

        return $cookieValue;
    }

    public function getTitleStartDateLabel()
    {
        $startDate = \Yii::$app->formatter->asDate($this->publish_date);
        if (!empty($startDate)) {
            $startDate = ' - pubblicato il ' . $startDate;
        }
        return $this->title . $startDate;
    }


    /**
     *
     */
    public function saveCustomTags()
    {
        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_CUSTOM_POLLS])->one();
        if ($root) {
            EntitysTagsMm::deleteAll(['root_id' => $root->id, 'record_id' => $this->id, 'classname' => Sondaggi::className()]);
            $exploded = explode(',', $this->customTags);
            foreach ($exploded as $tagString) {
                $tag = Tag::find()->andWhere(['nome' => $tagString, 'root' => $root->id])->one();
                if (empty($tag)) {
                    $tag = new Tag();
                    $tag->nome = $tagString;
                    $tag->appendTo($root);
                    $ok = $tag->save(false);
                }
                if (!empty($tag->id)) {
                    $tagsMm = new EntitysTagsMm();
                    $tagsMm->tag_id = $tag->id;
                    $tagsMm->record_id = $this->id;
                    $tagsMm->root_id = $root->id;
                    $tagsMm->classname = Sondaggi::className();
                    $tagsMm->save(false);
                }
            }
            $explodedDefault = $this->customTagsDefault;
            foreach ($explodedDefault as $tagId) {
                $defaultTag = Tag::findOne($tagId);
                if ($defaultTag) {
                    $tagsMm = new EntitysTagsMm();
                    $tagsMm->tag_id = $defaultTag->id;
                    $tagsMm->record_id = $this->id;
                    $tagsMm->root_id = $root->id;
                    $tagsMm->classname = Sondaggi::className();
                    $tagsMm->save(false);
                }

            }

        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function loadCustomTags()
    {
        $this->customTags = [];
        $this->customTagsDefault = [];

        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_CUSTOM_POLLS])->one();
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => Sondaggi::className()])
                ->andWhere(['record_id' => $this->id])
                ->andWhere(['root_id' => $root->id])
                ->andWhere(['IS', 'codice', null])
                ->all();
            foreach ($tagsMm as $tagMm) {
                $this->customTags [] = $tagMm->tag->nome;
            }
            $this->customTags = implode(',', $this->customTags);


            $tagsMmDefault = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => Sondaggi::className()])
                ->andWhere(['record_id' => $this->id])
                ->andWhere(['root_id' => $root->id])
                ->andWhere(['like', 'codice', 'custom_tags_default'])
                ->all();
            foreach ($tagsMmDefault as $tagMm) {
                $this->customTagsDefault [] = $tagMm->tag;
            }
        }
    }
}
