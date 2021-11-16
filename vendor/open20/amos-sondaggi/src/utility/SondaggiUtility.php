<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 29/05/2019
 * Time: 16:24
 */

namespace open20\amos\sondaggi\utility;

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
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\core\user\User;
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
            $email->sendMail($from, $to, $subject, $message, $files);
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
        if (!empty($risposteSessioni->user)) {
            $users[] = $risposteSessioni->user;
        }

        $additionalEmails = [];
        if (!empty($model->additional_emails)) {
            $additionalEmails = explode(';', $model->additional_emails);
        }

        $compilatore = !empty($users[0]) ? $users[0] : null;

        if (!empty($compilatore)) {
            $message = "<p>".AmosSondaggi::t('amossondaggi',
                    'Grazie per aver compilato il sondaggio <strong>{titolo}</strong>, in allegato trovi il sondaggio compilato.',
                    ['titolo' => $model->titolo])."</p>";
            $subject = AmosSondaggi::t("amossondaggi", "{nomeCognome} ha compilato il sondaggio '{titolo}'",
                    ['titolo' => $model->titolo, 'nomeCognome' => !empty($compilatore) ? $compilatore->userProfile->nomeCognome
                        : '']);
        } else if (!empty($dati_utente)) {
            $message = "<p>".AmosSondaggi::t('amossondaggi',
                    'Grazie per aver compilato il sondaggio <strong>{titolo}</strong>, in allegato trovi il sondaggio compilato.',
                    ['titolo' => $model->titolo])."</p>";
            $subject = AmosSondaggi::t("amossondaggi", "{nomeCognome} ha compilato il sondaggio '{titolo}'",
                    ['titolo' => $model->titolo, 'nomeCognome' => !empty($dati_utente['nome']) ? ($dati_utente['nome'].' '.$dati_utente['cognome'])
                        : AmosSondaggi::t('amossondaggi', 'Utente')]);
        }
        if (empty($path)) {
            $files = [];
        } else {
            $files = [$path];
        }

        foreach ($users as $user) {
            if (!in_array($user->email, $additionalEmails)) {
                SondaggiUtility::sendEmailGeneral([$user->email], null, $subject, $message, $files);
            }
        }

        if (!empty($dati_utente) && !empty($dati_utente['email'])) {
            if (!in_array($dati_utente['email'], $additionalEmails)) {
                SondaggiUtility::sendEmailGeneral([$dati_utente['email']], null, $subject, $message, $files);
            }
        }

        foreach ($additionalEmails as $email) {
            if (!empty($email)) {
                SondaggiUtility::sendEmailGeneral([trim($email)], null, $subject, $message, $files);
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
            SondaggiUtility::sendEmailGeneral([trim($user->email)], null, $subject, $message);
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
                SondaggiUtility::sendEmailGeneral([trim($user->email)], null, $subject, $message);
            }
        }
    }

    /**
     * @param $model
     * @return array
     */

    public static function getSidebarPages($model, $idQuestion)
    {
        $controllerDashboard = 'dashboard';
        $menu = [];
        if (!\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI'))
            return $menu;
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
                    'label' => AmosSondaggi::t('amossondaggi', 'Info questionario'),
                    'icon' => 'comment-text-alt',
                    'activeTargetAction' => 'info',
                    'activeTargetController' => 'dashboard',
                    'titleLink' => AmosSondaggi::t('amossondaggi', 'Info questionario'),
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
                        'label' => AmosSondaggi::t('amossondaggi', 'Comunicazioni'),
                        'icon' => 'settings',
                        'activeTargetAction' => 'communications',
                        'activeTargetController' => $controllerDashboard,
                        'titleLink' => AmosSondaggi::t('amossondaggi', 'Comunicazioni'),
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
            $generatore->creaView("backend" . DS . $controller->base_dir . DS . "views" . DS . "q" . $id, $pagina['id'],
                $controller->percorso_view . $id);
            $generatore->creaModel("backend" . DS . $controller->base_dir . DS . "models" . DS . "q" . $id, $pagina['id'],
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
        // altrimenti poi compaiono nell'elenco di tutte le domande del questionario

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


}
