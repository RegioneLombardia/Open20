<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 29/05/2019
 * Time: 16:24
 */

namespace open20\amos\sondaggi\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\exceptions\AmosException;
use open20\amos\core\utilities\Email;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\controllers\PubblicazioneController;
use open20\amos\sondaggi\models\GeneratoreSondaggio;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use open20\amos\sondaggi\models\SondaggiRisposte;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\core\user\User;
use PHPExcel_Exception;
use PHPExcel_Reader_Exception;
use PHPExcel_Writer_Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\BaseInflector;
use yii\log\Logger;

class SondaggiUtility
{

    /**
     * @param $to
     * @param $profile
     * @param $subject
     * @param $message
     * @param array $files
     * @return bool
     */
    public static function sendEmailGeneral($to, $profile, $subject, $message, $files = [], $from = null)
    {
        try {
            if (empty($from)) {
                $from = '';
                if (isset(\Yii::$app->params['email-assistenza'])) {
                    //use default platform email assistance
                    $from = \Yii::$app->params['email-assistenza'];
                }
            }

            /** @var \open20\amos\core\utilities\Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message, $files, [], ['profile' => $profile]);
        } catch (\Exception $ex) {
            pr($ex->getMessage());
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return true;
    }

    /**
     * @param $model Sondaggi
     * @param int $request_info
     * @param string $path
     */
    public static function sendEmailSondaggioCompilato($model, $idSessione, $path = null, $utente = null,
                                                       $dati_utente = null)
    {
        $risposteSessioni = SondaggiRisposteSessioni::findOne($idSessione);
        $userDefault      = null;
        $users            = [];
        if (!empty($risposteSessioni->user) && $model->send_pdf_to_compiler) {
            $users[] = $risposteSessioni->user;
        }

        $additionalEmails = [];
        if (!empty($model->additional_emails) && $model->send_pdf_via_email) {
            $additionalEmails = explode(';', $model->additional_emails);
        }

        $compilatore = !empty($users[0]) ? $users[0] : null;

        if (!empty($utente)) {
            $dati_utente = [
                'nome' => $utente->nome,
                'cognome' => $utente->cognome
            ];
        }
        $data = [];
        if (!empty($compilatore)) {
            $nomeCognome = $compilatore->userProfile->nomeCognome;
        } else if (!empty($dati_utente)) {
            $nomeCognome = $dati_utente['nome'] . ' ' . $dati_utente['cognome'];
        } else {
            $nomeCognome = AmosSondaggi::t('amossondaggi', 'Utente');
        }
        if (!empty($compilatore))
            $data = ['titolo' => $model->titolo, 'nomeCognome' => !empty($nomeCognome) ? $nomeCognome : ''];
        else if (!empty($dati_utente))
            $data = ['titolo' => $model->titolo, 'nomeCognome' => !empty($dati_utente['nome']) ? ($dati_utente['nome'].' '.$dati_utente['cognome']) : AmosSondaggi::t('amossondaggi', 'Utente')];

        $message = "<p>".AmosSondaggi::t('amossondaggi',
                'Grazie per aver compilato il sondaggio <strong>{titolo}</strong>, in allegato trovi il sondaggio compilato.', $data)."</p>";
        $subject = AmosSondaggi::t("amossondaggi", "{nomeCognome} ha compilato il sondaggio '{titolo}'", $data);

        if (empty($path)) {
            $files = [];
        } else {
            $files = [$path];
        }

        foreach ($users as $user) {
            if (!in_array($user->email, $additionalEmails)) {
                SondaggiUtility::sendEmailGeneral([$user->email], $user, $subject, $message, $files);
            }
        }

        if (!empty($dati_utente) && !empty($dati_utente['email'])) {
            if (!in_array($dati_utente['email'], $additionalEmails)) {
                SondaggiUtility::sendEmailGeneral([$dati_utente['email']], $user, $subject, $message, $files);
            }
        }

        $message = "<p>".AmosSondaggi::t('amossondaggi',
        'L’utente {utente} ha compilato il sondaggio <strong>{titolo}</strong>, in allegato trovi il sondaggio compilato.',
        ['titolo' => $model->titolo, 'utente' => !empty($nomeCognome) ? $nomeCognome : ''])."</p>";

        foreach ($additionalEmails as $email) {
            $user = User::find()->andWhere(['email' => $email])->one();
            if (!empty($email)) {
                SondaggiUtility::sendEmailGeneral([trim($email)], $user, $subject, $message, $files);
            }
        }
    }

    /**
     * Sends an email to the user to which a specific poll was assigned via the "Assign compiler" option.
     *
     * @param Sondaggi $model The assigned poll
     * @param User $user User to which it was assigned
     *
     * @return null
     */
    public static function sendEmailAssignedPoll($id, $user_id, $to_id = null)
    {
        $model = Sondaggi::findOne($id);
        $user = User::findOne($user_id);
        $to = null;
        if (AmosSondaggi::instance()->compilationToOrganization) $to = Profilo::findOne($to_id);

        $message = '';
        $close_date = '';

        if (!empty($model->close_date)) {
            $close_date .= AmosSondaggi::t('amossondaggi', '#email_assigned_compiler_close_date', [
                'closeDate' => \Yii::$app->formatter->asDate($model->close_date)
            ]);
        }

        $link = \Yii::$app->urlManager->createAbsoluteUrl([
            '/' . AmosSondaggi::getModuleName() . '/pubblicazione/compila',
            'id' => $model->id
        ]);

        if (!empty($to)) {
            $message .= AmosSondaggi::t('amossondaggi', '#email_assigned_compiler_to', [
                'firstName' => $user->userProfile->nome,
                'lastName' => $user->userProfile->cognome,
                'title' => $model->titolo,
                'to' => $to->name,
                'link' => $link,
                'closeDateMessage' => $close_date
            ]);
        }
        else {
            $message .= AmosSondaggi::t('amossondaggi', '#email_assigned_compiler', [
                'firstName' => $user->userProfile->nome,
                'lastName' => $user->userProfile->cognome,
                'title' => $model->titolo,
                'link' => $link
            ]);
        }

        $subject = AmosSondaggi::t('amossondaggi', '#email_assigned_compiler_subject', [
            'title' => $model->titolo
        ]);

        if (!empty($user->email)) {
            SondaggiUtility::sendEmailGeneral([trim($user->email)], $user, $subject, $message);
        }

        return;
    }

    public static function sendEmailPublishedPoll($id, $user_id) {
        $model = Sondaggi::findOne($id);
        $user = User::findOne($user_id);
        $link = \Yii::$app->urlManager->createAbsoluteUrl([
            '/' . AmosSondaggi::getModuleName() . '/dashboard/dashboard',
            'id' => $model->id
        ]);
        $message = AmosSondaggi::t('amossondaggi', '#email_poll_published', [
            'firstName' => $user->userProfile->nome,
            'lastName' => $user->userProfile->cognome,
            'title' => $model->titolo,
            'link' => $link
        ]);
        $subject = AmosSondaggi::t('amossondaggi', '#email_poll_published_subject', [
            'title' => $model->titolo
        ]);

        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
        foreach($users as $user) {
            if (!empty($user->email) && array_key_exists('SUPER_USER', \Yii::$app->authManager->getRolesByUser($user->id))) {
                SondaggiUtility::sendEmailGeneral([trim($user->email)], $user, $subject, $message);
            }
        }
    }

    public static function sendEmailRemovedCompilation($id, $entity) {
        $model = Sondaggi::findOne($id);
        $name = '';
        $email = '';
        $profile = null;
        if ($entity instanceof \open20\amos\organizzazioni\models\Profilo) {
            $profile = $entity->referenteOperativo;
            if (!is_null($profile)) {
                $name = $profile->nomeCognome;
                $email = $profile->user->email;
            } else {
                $name = $entity->name;
                $email = $entity->operativeHeadquarter->email;
            }
        } else {
            $profile = $entity;
            $name = $profile->nomeCognome;
            $email = $profile->user->email;
        }

        $message = AmosSondaggi::t('amossondaggi', '#email_compilation_removed', [
            'name' => $name,
            'title' => $model->titolo
        ]);
        $subject = AmosSondaggi::t('amossondaggi', '#email_compilation_removed_subject', [
            'title' => $model->titolo
        ]);

        if (!empty($email)) {
            SondaggiUtility::sendEmailGeneral([trim($email)], $profile, $subject, $message);
        }
    }

    /**
     * @param $model
     * @return array
     */
    public static function getSidebarPages($model, $idQuestion = null, $page = null)
    {
        $controllerDashboard = 'dashboard';
        $menu = [];
        if (!\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) {
                if (empty($page)) return [];
            else
                return [[
                    'undo' => [
                        'label' => AmosSondaggi::t('amossondaggi', 'Indietro'),
                        'activeTargetAction' => '',
                        'activeTargetController' => 'dashboard',
                        'titleLink' => AmosSondaggi::t('amossondaggi', 'Indietro'),
                        'url' => '/sondaggi/dashboard/dashboard?id='.$model->id,
                        'icon' => 'caret-left'
                    ]
                ]];
        }
        if (empty($idQuestion))
            $menu[] = [
                'mainMenu' => [
                    'label' => AmosSondaggi::t('amossondaggi', 'Dashboard'),
                    'icon' => 'view-dashboard',
                    'activeTargetAction' => 'dashboard',
                    'activeTargetController' => 'dashboard',
                    'titleLink' => AmosSondaggi::t('amossondaggi', 'Dashboard'),
                    'url' => '/sondaggi/dashboard/dashboard?id='.$model->id
                ]
            ];
        else
            $menu[] = [
                'undo' => [
                    'label' => AmosSondaggi::t('amossondaggi', 'Indietro'),
                    'activeTargetAction' => '',
                    'activeTargetController' => 'dashboard',
                    'titleLink' => AmosSondaggi::t('amossondaggi', 'Indietro'),
                    'url' => '/sondaggi/dashboard/dashboard?id='.$model->id,
                    'icon' => 'caret-left',

                ],
            ];

        array_push($menu,
            [
                'mainMenu' => [
                    'label' => AmosSondaggi::t('amossondaggi', 'Gestione sondaggio'),
                    'icon' => 'sondaggi',
                    'framework' => 'ic',
                    'activeTargetAction' => 'info',
                    'activeTargetController' => 'dashboard',
                    'titleLink' => AmosSondaggi::t('amossondaggi', 'Gestione sondaggio'),
                    'url' => '/sondaggi/dashboard/info?id='.$model->id,
                ],
            ],
            [
                'mainMenu' => [
                    'label' => AmosSondaggi::t('amossondaggi', 'Gestione pagine'),
                    'icon' => 'collection-text',
                    'activeTargetAction' => 'index',
                    'activeTargetController' => 'dashboard-domande-pagine',
                    'titleLink' => AmosSondaggi::t('amossondaggi', 'Gestione pagine'),
                    'url' => '/sondaggi/dashboard-domande-pagine?idSondaggio='.$model->id,
                ],
            ],
            [
                'mainMenu' => [
                    'label' => AmosSondaggi::t('amossondaggi', 'Gestione domande'),
                    'icon' => 'pin-help',
                    'activeTargetAction' => 'index',
                    'activeTargetController' => 'dashboard-domande',
                    'titleLink' => AmosSondaggi::t('amossondaggi', 'Gestione domande'),
                    'url' => '/sondaggi/dashboard-domande?idSondaggio='.$model->id
                ]
            ]
            // [
            //     'mainMenu' => [
            //         'label' => AmosSondaggi::t('amossondaggi', 'Landing Page'),
            //         'icon' => 'view-compact',
            //         'activeTargetAction' => 'landing-page',
            //         'activeTargetController' => $controllerDashboard,
            //         'titleLink' => AmosSondaggi::t('amossondaggi', 'Landing Page'),
            //         'url' => '/sondaggi/dashboard/landing?id='.$model->id,
            //     ],
            // ],
            // [
            //     'mainMenu' => [
            //         'label' => AmosSondaggi::t('amossondaggi', 'Modifica community'),
            //         'icon' => 'accounts',
            //         'activeTargetAction' => 'edit-community',
            //         'activeTargetController' => $controllerDashboard,
            //         'titleLink' => AmosSondaggi::t('amossondaggi', 'Modifica community'),
            //         'url' => '/sondaggi/dashboard/community?idSondaggio='.$model->id.'&url='.\yii\helpers\Url::current(),
            //     ],
            // ],
            // [
            //     'mainMenu' => [
            //         'label' => AmosSondaggi::t('amossondaggi', 'Template email'),
            //         'icon' => 'email',
            //         'activeTargetAction' => 'email-template',
            //         'activeTargetController' => $controllerDashboard,
            //         'titleLink' => AmosSondaggi::t('amossondaggi', 'Template email'),
            //         'url' => '/sondaggi/dashboard/email-template?id='.$model->id,
            //     ],
            // ],
        );

        if (AmosSondaggi::instance()->hasInvitation) {
            array_push($menu,
                [
                    'mainMenu' => [
                        'label' => AmosSondaggi::t('amossondaggi', 'Gestione inviti'),
                        'icon' => 'account-box-mail',
                        'activeTargetAction' => 'index',
                        'activeTargetController' => 'dashboard-invitations',
                        'titleLink' => AmosSondaggi::t('amossondaggi', 'Gestione inviti'),
                        'url' => '/sondaggi/dashboard-invitations?idSondaggio='.$model->id,
                    ],
                ]
            );
        }

        if (AmosSondaggi::instance()->hasComunications) {
            array_push($menu,
                [
                    'mainMenu' => [
                        'label' => AmosSondaggi::t('amossondaggi', 'Gestione comunicazioni'),
                        'icon' => 'mail-send',
                        'activeTargetAction' => 'communications',
                        'activeTargetController' => $controllerDashboard,
                        'titleLink' => AmosSondaggi::t('amossondaggi', 'Gestione comunicazioni'),
                        'url' => '/sondaggi/dashboard/communications?sondaggi_id='.$model->id,
                    ],
                ]
            );
        }

        return $menu;
    }

    /**
     * @param $controller
     * @param $id
     */
    public static function regenerateSondaggioPagesModelAndViews($id)
    {
        $controller = new PubblicazioneController('tmp-controller', AmosSondaggi::instance());
        $dir_models = $controller->alias_path . DS . $controller->base_dir . DS . "models" . DS . "q" . $id;
        $dir_views = $controller->alias_path . DS . $controller->base_dir . DS . "views" . DS . "q" . $id;

        if (!is_dir($dir_models)) {
            mkdir($dir_models, 0777, true);
        }
        if (!is_dir($dir_views)) {
            mkdir($dir_views, 0777, true);
        }
        $sondaggio = Sondaggi::findOne(['id' => $id]);
        $pagine = $sondaggio->getSondaggiDomandePagines()->orderBy('ordinamento, id ASC');
        $num_pagine = $pagine->count();
        $np = 1;
        $generatore = new GeneratoreSondaggio();
        foreach ($pagine->all() as $pagina) {
            $generatore->creaValidator($controller->percorso_validator, $pagina['id']);
            $generatore->creaView("common" . DS . "uploads" . DS . $controller->base_dir . DS . "views" . DS . "q" . $id, $pagina['id'],
                $controller->percorso_view . $id);
            $generatore->creaModel("common" . DS . "uploads" . DS . $controller->base_dir . DS . "models" . DS . "q" . $id, $pagina['id'],
                $controller->percorso_validator, $controller->percorso_model . $id);
        }
    }

    /**
     * if return string 'ok' delete is correct
     * in other cases message to display...
     *
     * @param $id
     * @return string
     * @throws AmosException
     */
    public static function deleteSondaggio($id)
    {
        // $id deve essere un sondaggio...
        /** @var Sondaggi $model */
        $model = Sondaggi::findOne(['id' => $id]);
        if (empty($model)) {
            throw new AmosException('Parametro $id='.$id.' errato, sondaggio inesistente');
        }

        // se il sondaggio è in stato bozza/disattivato non è possibile eliminare!
        if ($model->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
            return AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare il sondaggio in quanto non è in stato BOZZA.");
        }

        $model->delete();
        return 'ok';
    }

    /**
     * if return string 'ok' delete is correct
     * in other cases message to display...
     *
     * @param $id
     * @return string
     * @throws AmosException
     */
    public static function deletePage($id)
    {
        // $id deve essere un sondaggio...
        /** @var SondaggiDomandePagine $model */
        $model = SondaggiDomandePagine::findOne(['id' => $id]);
        if (empty($model)) {
            throw new AmosException('Parametro $id='.$id.' errato, pagina inesistente');
        }

        // se il sondaggio è in stato bozza/disattivato non è possibile eliminare!
        if ($model->sondaggi->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
            return AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare la pagina in quanto il sondaggio a cui è collegata non è in stato BOZZA.");
        }

        // l'eliminazione di una pagina deve eliminare anche tutte le domande al suo interno
        // altrimenti poi compaiono nell'elenco di tutte le domande del sondaggio

        $qlist = $model->sondaggiDomandes;
        if (!empty($qlist) && is_array($qlist)) {
            /** @var SondaggiDomande $q */
            foreach ($qlist as $q) {
                $q->delete();
            }
        }

        $model->delete();
        return 'ok';
    }

    /**
     * if return string 'ok' delete is correct
     * in other cases message to display...
     *
     * @param $id
     * @return string
     * @throws AmosException
     */
    public static function deleteAnswer($id)
    {
        // $id deve essere un sondaggio...
        /** @var SondaggiDomande $model */
        $model = SondaggiDomande::findOne(['id' => $id]);
        if (empty($model)) {
            throw new AmosException('Parametro $id='.$id.' errato, domanda inesistente');
        }

        // se il sondaggio è in stato bozza/disattivato non è possibile eliminare!
        if ($model->sondaggi->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
            return AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare la domanda in quanto il sondaggio a cui è collegata non è in stato BOZZA.");
        }

        \open20\amos\sondaggi\models\SondaggiRispostePredefinite::deleteAll(['sondaggi_domande_id' => $id]);
        \open20\amos\sondaggi\models\SondaggiDomande::deleteAll(['parent_id' => $id]);
        \open20\amos\sondaggi\models\SondaggiDomandeCondizionate::deleteAll(['sondaggi_domande_id' => $id]);
        $model->delete();
        return 'ok';
    }

    /**
     * Get invitation email content message
     * @param $sondaggio Sondaggi
     * @param $userProfile UserProfile
     * @return string
     * @throws InvalidConfigException
     */
    public static function getInvitationEmailContent($sondaggio, $userProfile)
    {
        return AmosSondaggi::t('amossondaggi', 'invitation_message', [
            'titolo' => $sondaggio->titolo,
            'platformName' => Yii::$app->name,
            'urlPollCompilation' => Yii::$app->urlManager->createAbsoluteUrl([
                '/' . AmosSondaggi::getModuleName() . '/pubblicazione/compila',
                'id' => $sondaggio->id
            ]),
            'urlPlatform' => Yii::$app->urlManager->createAbsoluteUrl('/'),
            'nomeCognome' => $userProfile->nomeCognome,
            'data' => Yii::$app->formatter->asDate($sondaggio->close_date),
        ]);
    }

    /**
     * Get invitation email content message for users invitations
     * @param $sondaggio Sondaggi
     * @param $userProfile UserProfile
     * @return string
     * @throws InvalidConfigException
     */
    public static function getInvitationUserEmailContent($sondaggio, $userProfile)
    {
        return AmosSondaggi::t('amossondaggi', 'invitation_message', [
            'titolo' => $sondaggio->titolo,
            'platformName' => Yii::$app->name,
            'urlPollCompilation' => Yii::$app->urlManager->createAbsoluteUrl([
                '/' . AmosSondaggi::getModuleName() . '/pubblicazione/compila',
                'id' => $sondaggio->id
            ]),
            'urlPlatform' => Yii::$app->urlManager->createAbsoluteUrl('/'),
            'nomeCognome' => $userProfile->nomeCognome,
            'data' => Yii::$app->formatter->asDate($sondaggio->close_date),
        ]);
    }

    /**
     * Get invitation organization email content message
     * @param $sondaggio
     * @param $userProfile
     * @return string
     * @throws InvalidConfigException
     */
    public static function getInvitationOrganizationEmailContent($sondaggio, $organization)
    {
        return AmosSondaggi::t('amossondaggi', 'invitation_organization_message', [
            'titolo' => $sondaggio->titolo,
            'platformName' => Yii::$app->name,
            'urlPollCompilation' => Yii::$app->urlManager->createAbsoluteUrl([
                '/' . AmosSondaggi::getModuleName() . '/pubblicazione/compila',
                'id' => $sondaggio->id
            ]),
            'urlPlatform' => Yii::$app->urlManager->createAbsoluteUrl('/'),
            'ente' => $organization->name,
            'data' => Yii::$app->formatter->asDate($sondaggio->close_date),
        ]);
    }

    /**
     * Generate xls results file
     * @param $id
     * @return string
     * @throws InvalidConfigException
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     */
    public static function generateXlsResults($id)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $model = Sondaggi::findOne($id);
        $xlsData = [];
        $basePath = \Yii::getAlias('@vendor/../common/uploads/temp');
        $zipAttachs = [];

        // se abilita_registrazione == 1 allora i tre campi vanno visualizzati, se no, vale il parametro statisticExtractDisableNameSurnameEmail
        $viewNameSurnameEmail = $model->abilita_registrazione || !AmosSondaggi::instance()->statisticExtractDisableNameSurnameEmail;


        $intestazioneStart = [];
        $offset = -3;
        if ($viewNameSurnameEmail) {
            $intestazioneStart = ["Nome", "Cognome", "Email"];
            $offset = 0;
        }

        // INTESTAZIONE EXCEL
        $xlsData[0] = array_merge($intestazioneStart, ["Iniziato il", "Completato il"]);
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $xlsData[0] = array_merge($intestazioneStart, ["Ente", "Iniziato il", "Completato il"]);
        }
        $domande = [];
        $pagine = $model->getSondaggiDomandePagines()->orderBy('sondaggi_domande_pagine.ordinamento');
        foreach ($pagine->all() as $pagina) {
            $domandePagina = $pagina->getSondaggiDomandes()->andWhere(['parent_id' => null])->orderBy('ordinamento ASC')->all();
            foreach ($domandePagina as $domandaPag) {
                $domande[] = $domandaPag;
            }
        }

        $count = 1;
        $totCount = 5;
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $totCount = 6;
        }
        if (AmosSondaggi::instance()->enableCompilationWorkflow) {
            $xlsData[0][] = "Stato";
            $totCount++;
        }
        $colRisp = [];
        $colRispLibere = [];
        $colRispAllegati = [];
        foreach ($domande as $domanda) {
            $rispostePredefinite = $domanda->getSondaggiRispostePredefinites();
            $countRisposte = $rispostePredefinite->count();
            $localCount = 1;
            if (in_array($domanda->sondaggi_domande_tipologie_id, [10, 11])) {
                $xlsData[0][] = "D." . $count . " " . $domanda->domanda;
                $colRispAllegati[$domanda->id] = $totCount;
                $totCount++;
            } else if (in_array($domanda->sondaggi_domande_tipologie_id, [5, 6, 13])) {
                $xlsData[0][] = "D." . $count . " " . $domanda->domanda;
                $colRispLibere[$domanda->id] = $totCount;
                $totCount++;
            } else {
                if (!empty($countRisposte) && in_array($domanda->sondaggi_domande_tipologie_id, [1, 2, 3, 4])) {
                    if ($domanda->is_parent) {
                        $childs = $domanda->childs;
                        foreach ($childs as $ch) {
                            foreach ($rispostePredefinite->orderBy('ordinamento ASC')->all() as $rispPre) {
                                $xlsData[0][] = "D." . $count . " " . $domanda->domanda . " \n" . $ch->domanda . " \nR." . $localCount . " " . $rispPre->risposta;
                                $colRisp[$rispPre->id][$ch->id] = $totCount;
                                $localCount++;
                                $totCount++;
                            }
                        }
                    } else {
                        foreach ($rispostePredefinite->orderBy('ordinamento ASC')->all() as $rispPre) {
                            $xlsData[0][] = "D." . $count . " " . $domanda->domanda . " \nR." . $localCount . " " . $rispPre->risposta;
                            $colRisp[$rispPre->id] = $totCount;
                            $localCount++;
                            $totCount++;
                        }
                    }
                }
            }
            $count++;
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

        $srpArray = SondaggiRispostePredefinite::find()->asArray()->all();
        $sondRispPredefAll = [];
        foreach ($srpArray as $element) {
            $sondRispPredefAll[$element['id']] = $element;
        }

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
                if ($viewNameSurnameEmail) {
                    $xlsData [$row][0 + $offset] = ($model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                        'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                        'L\'utente non è stato registrato'));
                    $xlsData [$row][1 + $offset] = ($model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                        'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                        'L\'utente non è stato registrato'));
                    $xlsData [$row][2 + $offset] = ($model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                        'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                        'L\'utente non è stato registrato'));
                }
            } else {
                if ($viewNameSurnameEmail) {
                    $xlsData [$row][0 + $offset] = $profile->nome;
                    $xlsData [$row][1 + $offset] = $profile->cognome;
                    $xlsData [$row][2 + $offset] = $profile->user->email;
                }
            }
            $dateDiff = (new \DateTime())->diff(new \DateTime($sondRisposta->updated_at));
            if (($dateDiff->invert * $dateDiff->days) > 730 && AmosSondaggi::instance()->resetGdpr) {
                $xlsData [$row][0 + $offset] = "#####";
                $xlsData [$row][1 + $offset] = "#####";
                $xlsData [$row][2 + $offset] = "#####";
            }

            if (AmosSondaggi::instance()->compilationToOrganization) {
                $profilo = \open20\amos\organizzazioni\models\Profilo::find()->andWhere(['id' => $sondRisposta->organization_id])->one();
                $xlsData [$row][3 + $offset] = !empty($profilo->name) ? $profilo->name : '';
                $xlsData [$row][4 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->begin_date, 'php:d/m/Y H:i:s');
                $xlsData [$row][5 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->end_date, 'php:d/m/Y H:i:s');
                if (AmosSondaggi::instance()->enableCompilationWorkflow) {
                    $xlsData[$row][6 + $offset] = AmosSondaggi::t('amossondaggi', $sondRisposta->status);
                }
            } else {
                $xlsData [$row][3 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->begin_date, 'php:d/m/Y H:i:s');
                $xlsData [$row][4 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->end_date, 'php:d/m/Y H:i:s');
                if (AmosSondaggi::instance()->enableCompilationWorkflow) {
                    $xlsData[$row][5 + $offset] = AmosSondaggi::t('amossondaggi', $sondRisposta->status);
                }
            }

            $session_id = $sondRisposta->id;

            /** @var  $domanda SondaggiDomande */
            foreach ($domande as $domanda) {

                $query = $domanda->getRispostePerUtente((empty($profile) ? null : $profile->user_id), $session_id);
                // RISPOSTE LIBERE
                if ($domanda->sondaggi_domande_tipologie_id == 6 || $domanda->sondaggi_domande_tipologie_id == 5 || $domanda->sondaggi_domande_tipologie_id == 13) {

                    $risposta = $query->asArray()->one();
                    if ($risposta) {
                        if ($domanda->sondaggi_domande_tipologie_id == 13) {
                            $risposta['risposta_libera'] = Yii::$app->formatter->asDate($risposta['risposta_libera'], 'php:d/m/Y');
                        }
                        $xlsData[$row][$colRispLibere[$domanda->id] + $offset] = $risposta['risposta_libera'];
                    } else {

                    }

                //ALLEGATI
                } else if ($domanda->sondaggi_domande_tipologie_id == 10 || $domanda->sondaggi_domande_tipologie_id == 11) {
                    $risposta = $query->one();
                    if ($risposta) {
                        $attribute = 'domanda_' . $domanda->id;
                        if (!empty($risposta->$attribute)) {
                            $attachments = $risposta->getFiles();
                            $listAttachUrls = [];
                            foreach ($attachments as $attach) {
                                $folder = BaseInflector::slug($profile->cognome . ' ' . $profile->nome);
                                if (AmosSondaggi::instance()->compilationToOrganization) {
                                    $profilo = \open20\amos\organizzazioni\models\Profilo::find()->andWhere(['id' => $sondRisposta->organization_id])->one();
                                    $folder = BaseInflector::slug($profilo->name);
                                }
                                if (AmosSondaggi::instance()->xlsAsZip)
                                    $listAttachUrls [] = $folder . '/' . $attach->name . '.' . $attach->type;
                                else
                                    $listAttachUrls [] = \Yii::$app->params['platform']['backendUrl'] . $attach->getUrl();
                                $zipAttachs[$folder . '/' . $attach->name . '.' . $attach->type] = $attach->path;
                            }
                            $xlsData[$row][$colRispAllegati[$domanda->id] + $offset] = implode(" \n", $listAttachUrls);
                        }
                    } else {

                    }

                } else {
                    $risposteArray = [];
                    /** @var SondaggiRisposte $risposta */
                    foreach ($query->asArray()->all() as $risposta) {
                        $srp = $sondRispPredefAll[$risposta['sondaggi_risposte_predefinite_id']];

                        if (!empty($srp)) {
                            if ($domanda->is_parent) {
                                if (empty($srp['code'])) {

                                    $xlsData[$row][$colRisp[$srp['id']][$risposta['sondaggi_domande_id']]
                                    + $offset] = $srp['risposta'];
                                } else {
                                    $xlsData[$row][$colRisp[$srp['id']][$risposta['sondaggi_domande_id']]
                                    + $offset] = $srp['code'];
                                }
                            } else {
                                if (empty($srp['code'])) {
                                    $xlsData[$row][$colRisp[$srp['id']] + $offset] = $srp['risposta'];
                                } else {
                                    $xlsData[$row][$colRisp[$srp['id']] + $offset] = $srp['code'];
                                }
                            }
                        }
                    }
                }
            }
            $row++;

            gc_collect_cycles();
        }

        $zip_filepath = $basePath . '/Risposte_sondaggio_' . $id . '.zip';
        //inizializza l'oggetto excel
        $nomeFile = $basePath . '/Risposte_sondaggio_' . $id . '.xls';
        $objPHPExcel = new \PHPExcel();

        // set Style first row
        $lastColumn = $totCount + $offset;
        $lastColumnLetter = \PHPExcel_Cell::stringFromColumnIndex($lastColumn);

        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $lastColumnLetter . '1')->getFill()
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
        if (!AmosSondaggi::instance()->xlsAsZip) {
            return $nomeFile;
        } else {
            /** @var \ZipArchive $zip */
            $zip = new \ZipArchive();
            if ($zip->open($zip_filepath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Cannot create a zip file');
            }
            $zip->addFile($nomeFile, 'Risposte_sondaggio.xls');
            foreach ($zipAttachs as $name => $file) {
                $zip->addFile($file, $name);
            }

            $zip->close();
            return $zip_filepath;
        }
    }

    /**
     * Statuses for search filters
     * @return array
     */
    public static function getSearchStatuses()
    {
        return [
            null => AmosSondaggi::t('amossondaggi', '#all'),
            Sondaggi::WORKFLOW_STATUS_BOZZA => AmosSondaggi::t('amossondaggi', Sondaggi::WORKFLOW_STATUS_BOZZA),
            Sondaggi::WORKFLOW_STATUS_VALIDATO => AmosSondaggi::t('amossondaggi', Sondaggi::WORKFLOW_STATUS_VALIDATO),
            Sondaggi::STATUS_CONCLUSO => AmosSondaggi::t('amossondaggi', 'Concluso'),
        ];
    }

    /**
     * Checks if the poll is terminated
     * @param $model
     * @return bool
     */
    public static function isTerminated($model)
    {
        return $model->close_date < date('Y-m-d');
    }

    /**
     * @return string[]
     */
    public static function getFileExtensionLabel()
    {
        return [
            'xls' => 'Excel',
            'pdf' => 'PDF',
        ];
    }

}
