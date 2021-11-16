<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 24/01/2020
 * Time: 15:49
 */

namespace open20\amos\events\utility;

use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\events\AmosEvents;
use open20\amos\admin\AmosAdmin;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventCalendarsSlots;
use open20\amos\events\models\EventEmailTemplates;
use open20\amos\events\models\EventGroupReferentMm;
use yii\helpers\Url;
use yii\log\Logger;
use Yii;

class EventMailUtility
{

    /**
     * @param $model
     */
    public static function sendEmailSlotBooked($model)
    {
        $to = [];
        $event = $model->eventCalendars->event;
        $link = \Yii::$app->params['platform']['backendUrl'] . "/community/join?id=" . $event->community_id . "#my-booking";
        if ($model->user) {
            $to[] = $model->user->email;
        }

        $subject = html_entity_decode($event->title . " - " . AmosEvents::t('amosevents', "prenotazione confermata"));
        $message = AmosEvents::t('amosevents',
            "La prenotazione dell’appuntamento è andata a buon fine.<br>In caso di impossibilità a partecipare ti chiediamo cortesemente di disdire l’appuntamento direttamente dal tuo calendario.<br>Puoi vedere il tuo calendario accedendo alla <a href='{link}'>community</a>",
            ['link' => $link]);
        $from = self::getFromMail($event);
        self::sendEmailGeneral($from, $to, $subject, $message);
    }

    /**
     * @param $model
     */
    public static function sendEmailSlotUnbooked($model)
    {
        $to = [];
        $event = $model->eventCalendars->event;
        $link = \Yii::$app->params['platform']['backendUrl'] . "/community/join?id=" . $event->community_id . "#my-booking";
        if ($model->user) {
            $to[] = $model->user->email;
        }

        $subject = html_entity_decode($event->title . " - " . AmosEvents::t('amosevents', "prenotazione annullata"));
        $message = AmosEvents::t('amosevents',
            "La cancellazione dell’appuntamento è andata a buon fine.<br>Ti ricordiamo che in qualsiasi momento puoi accedere alla <a href='{link}'>community</a> per verificare nel tuo calendario tutti gli appuntamenti prenotati",
            ['link' => $link]);
        $from = self::getFromMail($event);
        self::sendEmailGeneral($from, $to, $subject, $message);
    }

    /**
     * @param $model EventCalendarsSlots
     */
    public static function sendEmailPartnerSlotBooked($model)
    {
        $to = [];
        $event = $model->eventCalendars->event;
        $userPartner = $model->eventCalendars->partnerUser;
        $nomeCognome = '';
        $data = '';
        $ora = '';

        $link = \Yii::$app->params['platform']['backendUrl'] . "/events/event-calendars/view?id=" . $model->eventCalendars->id . "&url=/community/join?id=" . $event->community_id . "";
        if ($userPartner) {
            $to[] = $userPartner->email;
            $nomeCognome = $model->user->userProfile->nomeCognome;
            $data = \Yii::$app->formatter->asDate($model->date);
            $ora = \Yii::$app->formatter->asTime($model->hour_start);
        }
        $subject = html_entity_decode($event->title . " - " . AmosEvents::t('amosevents', "prenotazione confermata"));
        $message = AmosEvents::t('amosevents',
            "Ti informiamo che <strong>{nomeCognome}</strong> ha prenotato un appuntamento per il {data} alle ore {ore}.",
            [
                'nomeCognome' => $nomeCognome,
                'data' => $data,
                'ore' => $ora,
            ]);
        $message .= AmosEvents::t('amosevents', "<br><br>Informazioni aggiuntive");
        $message .= AmosEvents::t('amosevents', "<br><strong>Cellulare</strong>: ") . $model->cellphone;
        $message .= AmosEvents::t('amosevents', "<br><strong>Affilia<ione</strong>: ") . $model->affiliation;
        $from = self::getFromMail($event);
        self::sendEmailGeneral($from, $to, $subject, $message);
    }

    /**
     * @param $model EventCalendarsSlots
     */
    public static function sendEmailPartnerSlotUnbooked($model)
    {
        $to = [];
        $event = $model->eventCalendars->event;
        $userPartner = $model->eventCalendars->partnerUser;
        $nomeCognome = '';
        $data = '';
        $ora = '';

        $link = \Yii::$app->params['platform']['backendUrl'] . "/events/event-calendars/view?id=" . $model->eventCalendars->id . "&url=/community/join?id=" . $event->community_id . "";
        if ($userPartner) {
            $to[] = $userPartner->email;
            $nomeCognome = $model->user->userProfile->nomeCognome;
            $data = \Yii::$app->formatter->asDate($model->date);
            $ora = \Yii::$app->formatter->asTime($model->hour_start);
        }

        $subject = html_entity_decode($event->title . " - " . AmosEvents::t('amosevents', "prenotazione annullata"));
        $message = AmosEvents::t('amosevents',
            "Ti informiamo che <strong>{nomeCognome}</strong> ha annullato l’appuntamento del {data} alle ore {ore}.",
            [
                'nomeCognome' => $nomeCognome,
                'data' => $data,
                'ore' => $ora,
            ]);
        $from = self::getFromMail($event);
        self::sendEmailGeneral($from, $to, $subject, $message);
    }

    /**
     * @param $to
     * @param $profile
     * @param $subject
     * @param $message
     * @param array $files
     * @return bool
     */
    public static function sendEmailGeneral($from, $to, $subject, $message, $files = [])
    {
        try {


            /** @var \open20\amos\core\utilities\Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message, $files);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return true;
    }

    /**
     *
     * @param UserProfile $model
     * @param type $subject
     * @param type $contentView
     * @param type $from
     * @param type $layout
     * @param \open20\amos\community\models\Community $community
     * @return boolean
     */
    public static function sendCredentialsMail($model, $subject, $contentView, $from = null, $layout = null,
                                               $community = null)
    {
        try {
            $model->user->generatePasswordResetToken();
            $model->user->save(false);
            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
            if (empty($layout)) {
                $mailModule = Yii::$app->getModule("email");
                if (isset($mailModule)) {
                    $mailModule->defaultLayout = $layout;
                }
            }

            if (empty($subject)) {
                $subjectView = $adminModule->htmlMailSubject;
                $subject = Email::renderMailPartial($subjectView, ['profile' => $model], $model->user->id);
            }

            $mail = Email::renderMailPartial($contentView, ['profile' => $model, 'community' => $community],
                $model->user->id);
            return Email::sendMail((empty($from) ? $from : Yii::$app->params['email-assistenza']),
                [$model->user->email], $subject, $mail, []);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        return false;
    }

    public static function setLayoutMail($layout = null)
    {
        if (!empty($layout)) {
            $mailModule = Yii::$app->getModule("email");
            if (isset($mailModule)) {
                $mailModule->defaultLayout = $layout;
            }
        }
    }

    protected static function getFromMail($event)
    {
        $from = '';
        if (!empty(trim($event->email_ticket_sender))) {
            $from = $event->email_ticket_sender;
        } else if (isset(Yii::$app->params['adminEmail'])) {
            $from = Yii::$app->params['adminEmail'];
        } else if (isset(Yii::$app->params['email-assistenza'])) {
            $from = Yii::$app->params['email-assistenza'];
        }
        return $from;
    }

    /**
     * @param $event
     * @param $user_id
     * @param $email
     * @param $template_attr
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function sendEmailTemplate($event, $user_id, $email, $template_attr)
    {
        $from = self::getFromMail($event);
        $template = $event->eventEmailTemplates;
        $attrSubject = $template_attr . '_subject';
        $text = $template->$template_attr;

        if ($template_attr == 'registration_email') {
            $text .= "<br><a href='{UrlYes}'>Si, parteciperò</a>";
        }
        $subject = $template->$attrSubject;
        $subjectWithParams = self::parseEmailWithParams($event, $user_id, $subject, false);
        $textWithParms = self::parseEmailWithParams($event, $user_id, $text);

        return self::sendEmailGeneral($from, [$email], $subjectWithParams, $textWithParms);
    }

    /**
     * @param $event
     * @param $user_id
     * @param $email
     * @param $text
     * @param $subject
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function sendCommunication($event, $user_id, $email, $text, $subject)
    {
        $from = self::getFromMail($event);
        $subjectWithParams = self::parseEmailWithParams($event, $user_id, $subject, false);
        $textWithParms = self::parseEmailWithParams($event, $user_id, $text);

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $user = User::findOne(\Yii::$app->user->id);
            $decode = $mailService->sendTransationalEmail(2, $subjectWithParams, $textWithParms, ['user' => $user->userProfile->nomeCognome, 'email' => $email]);
            return true;
        }
//        return self::sendEmailGeneral($from, [$email], $subjectWithParams, $textWithParms);
    }

    /**
     * @param $event
     * @param $user_id
     * @param $text
     * @param bool $setStrong
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function parseEmailWithParams($event, $user_id, $text, $setStrong = true)
    {
        $url = \Yii::$app->params['platform']['backendUrl'] . "/img/img_default.jpg";
        if ($event) {
            $logo = $event->eventLogo;
            if ($logo) {
                $url = \Yii::$app->params['platform']['backendUrl'] . $logo->getWebUrl();
            }
            if ($user_id) {
                $user = User::findOne($user_id);
                $urlYes = EventsUtility::getUrlLanding($event) . "?pName={$user->userProfile->nome}&pSurname={$user->userProfile->cognome}&pEmail={$user->email}";

//            $urlYes = \Yii::$app->params['platform']['backendUrl'] . Url::toRoute(['/events/event/event-signup', 'eid' => $event->id, 'pName' => $user->userProfile->nome, 'pSurname' => $user->userProfile->cognome, 'pEmail' => $user->email]);
            } else {
                $urlYes = \Yii::$app->params['platform']['backendUrl'] . Url::toRoute(['/events/event/event-signup', 'eid' => $event->id, 'pName' => "[nome]", 'pSurname' => "[cognome]", 'pEmail' => "[email]"]);
//            $urlYes = str_replace('?', ':rQS$:', $urlYes);
//            $urlYes = str_replace('&', ':rQS*:', $urlYes);
                $urlYes = 'http://[track]/' . $urlYes;
            }
            $urlLanding = 'http://[track]/' . EventsUtility::getUrlLanding($event);
            $urlLandingNoTracking = EventsUtility::getUrlLanding($event);

            $urlCommunity = '';
            $urlCommunityNoTracking = '';
            if ($event->community_id) {
                $urlCommunity = 'http://[track]/' . \Yii::$app->params['platform']['backendUrl'] . '/community/join?id=' . $event->community_id;
                $urlCommunityNoTracking = \Yii::$app->params['platform']['backendUrl'] . '/community/join?id=' . $event->community_id;
            }
            if (is_null($user_id)) {
                $textWithParms = AmosEvents::t('amosevents', $text, [
                        'NOME' => self::setStrong('[nome]', $setStrong),
                        'USER_ID' => '[user_id]',
                        'COGNOME' => self::setStrong('[cognome]', $setStrong),
                        'EMAIL' => self::setStrong('[email]', $setStrong),
                        'CODICE_FISCALE' => self::setStrong('[codice_fiscale]', $setStrong),
                        'GENERE' => self::setStrong('[genere]', $setStrong),
                        'ETA' => self::setStrong('[eta]', $setStrong),
                        'PROVINCIA' => self::setStrong('[provincia]', $setStrong),
                        'COMUNE' => self::setStrong('[città]', $setStrong),
                        'AZIENDA' => self::setStrong('[azienda]', $setStrong),
                        'TITOLO' => self::setStrong($event->title, $setStrong),
                        'DESCRIZIONE' => self::setStrong($event->description, $setStrong),
                        'TIPOLOGIA' => self::setStrong($event->eventType->title, $setStrong),
                        'DATA_INIZIO' => self::setStrong(\Yii::$app->formatter->asDate($event->begin_date_hour), $setStrong),
                        'DATA_FINE' => self::setStrong(\Yii::$app->formatter->asDate($event->end_date_hour), $setStrong),
                        'ORA_INIZIO' => self::setStrong(\Yii::$app->formatter->asTime($event->begin_date_hour), $setStrong),
                        'ORA_FINE' => self::setStrong(\Yii::$app->formatter->asTime($event->end_date_hour), $setStrong),
                        'DATA_CHIUSURA_ISCRIZIONI' => self::setStrong(\Yii::$app->formatter->asDate($event->registration_date_end), $setStrong),
                        'ORA_CHIUSURA_ISCRIZIONI' => self::setStrong(\Yii::$app->formatter->asTime($event->registration_date_end), $setStrong),
                        'LOCATION' => $event->eventLocation ? self::setStrong($event->eventLocation->name, $setStrong) : null,
                        'INDIRIZZO' => $event->eventLocationEntrance ? self::setStrong($event->eventLocationEntrance->name, $setStrong) : null,
                        'URL_LANDING' => $urlLanding,
                        'URL_COMMUNITY' => "<a href=\"$urlCommunity\">" . $urlCommunity . "</a>",
                        'SUMMARY' => self::setStrong($event->summary, $setStrong),
                        'IMMAGINE_EVENTO' => Html::img($url),
                        'UrlYes' => $urlYes,
                        'URL' => $urlLanding,
                        'DISACTIVATE_USER_URL' => \Yii::$app->params['platform']['backendUrl'] . '/admin/user-profile/disactivate-user',
                        'DATA_INIZIO_STREAMING' => $event->eventLanding ? self::setStrong(\Yii::$app->formatter->asDate($event->eventLanding->date_begin_streaming), $setStrong) : '',
                        'ORA_INIZIO_STREAMING' => $event->eventLanding ? self::setStrong(\Yii::$app->formatter->asTime($event->eventLanding->date_begin_streaming), $setStrong) : '',
                        'ORA_ENTRATA' => $event->enter_time ? self::setStrong(\Yii::$app->formatter->asTime($event->enter_time), $setStrong) : '',
                    ]
                );
            } else {
                $textWithParms = AmosEvents::t('amosevents', $text, [
                        'NOME' => self::setStrong($user->userProfile->nome, $setStrong),
                        'USER_ID' => $user->id,
                        'COGNOME' => self::setStrong($user->userProfile->cognome, $setStrong),
                        'EMAIL' => self::setStrong($user->email, $setStrong),
                        'CODICE_FISCALE' => self::setStrong($user->userProfile->codice_fiscale, $setStrong),
                        'GENERE' => self::setStrong($user->userProfile->sesso, $setStrong),
                        'ETA' => self::setStrong($user->userProfile->userProfileAgeGroup->age_group, $setStrong),
                        'PROVINCIA' => self::setStrong($user->userProfile->nascitaProvince->nome, $setStrong),
                        'COMUNE' => self::setStrong($user->userProfile->nascitaComuni->nome, $setStrong),
                        'AZIENDA' => self::setStrong($user->userProfile->azienda, $setStrong),
                        'TITOLO' => self::setStrong($event->title, $setStrong),
                        'DESCRIZIONE' => self::setStrong($event->description, $setStrong),
                        'TIPOLOGIA' => self::setStrong($event->eventType->title, $setStrong),
                        'DATA_INIZIO' => self::setStrong(\Yii::$app->formatter->asDate($event->begin_date_hour), $setStrong),
                        'DATA_FINE' => self::setStrong(\Yii::$app->formatter->asDate($event->end_date_hour), $setStrong),
                        'ORA_INIZIO' => self::setStrong(\Yii::$app->formatter->asTime($event->begin_date_hour), $setStrong),
                        'ORA_FINE' => self::setStrong(\Yii::$app->formatter->asTime($event->end_date_hour), $setStrong),
                        'DATA_CHIUSURA_ISCRIZIONI' => self::setStrong(\Yii::$app->formatter->asDate($event->registration_date_end), $setStrong),
                        'ORA_CHIUSURA_ISCRIZIONI' => self::setStrong(\Yii::$app->formatter->asTime($event->registration_date_end), $setStrong),
                        'LOCATION' => $event->eventLocation ? self::setStrong($event->eventLocation->name, $setStrong) : null,
                        'INDIRIZZO' => $event->eventLocationEntrance ? self::setStrong($event->eventLocationEntrance->name, $setStrong) : null,
                        'URL_LANDING' => $urlLandingNoTracking,
                        'URL' => $urlLandingNoTracking,
                        'URL_COMMUNITY' => "<a href=\"$urlCommunityNoTracking\">" . $urlCommunity . "</a>",
                        'SUMMARY' => self::setStrong($event->summary, $setStrong),
                        'IMMAGINE_EVENTO' => Html::img($url),
                        'UrlYes' => $urlYes,
                        'DISACTIVATE_USER_URL' => \Yii::$app->params['platform']['backendUrl'] . '/admin/user-profile/disactivate-user',
                        'DATA_INIZIO_STREAMING' => $event->eventLanding ? self::setStrong(\Yii::$app->formatter->asDate($event->eventLanding->date_begin_streaming), $setStrong) : '',
                        'ORA_INIZIO_STREAMING' => $event->eventLanding ? self::setStrong(\Yii::$app->formatter->asTime($event->eventLanding->date_begin_streaming), $setStrong) : '',
                        'ORA_ENTRATA' => $event->enter_time ? self::setStrong(\Yii::$app->formatter->asTime($event->enter_time), $setStrong) : '',
                    ]
                );
            }
        } else {
            $textWithParms = AmosEvents::t('amosevents', $text, [
                    'NOME' => self::setStrong('[nome]', $setStrong),
                    'COGNOME' => self::setStrong('[cognome]', $setStrong),
                    'EMAIL' => self::setStrong('[email]', $setStrong),
                    'TOKEN' => '[token]',
                ]
            );
        }
        return $textWithParms;
    }

    /**
     * @param $str
     * @param bool $set
     * @return string
     */
    public static function setStrong($str, $set = true)
    {
        if ($set) {
            return "<strong>" . $str . "</strong>";
        }
        return $str;
    }

    /**
     * @return array
     */
    public static function getVariablesEmail()
    {
        $variables = [
            '{NOME}' => '{NOME}',
            '{COGNOME}' => '{COGNOME}',
            '{EMAIL}' => '{EMAIL}',
            '{CODICE_FISCALE}' => '{CODICE_FISCALE}',
            '{GENERE}' => '{GENERE}',
            '{ETA}' => '{ETA}',
            '{PROVINCIA}' => '{PROVINCIA}',
            '{COMUNE}' => '{COMUNE}',
            '{AZIENDA}' => '{AZIENDA}',
            '{TITOLO}' => '{TITOLO}',
            '{DESCRIZIONE}' => '{DESCRIZIONE}',
            '{TIPOLOGIA}' => '{TIPOLOGIA}',
            '{SUMMARY}' => '{SUMMARY}',
            '{DATA_INIZIO}' => '{DATA_INIZIO}',
            '{DATA_FINE}' => '{DATA_FINE}',
            '{ORA_INIZIO}' => '{ORA_INIZIO}',
            '{ORA_FINE}' => '{ORA_FINE}',
            '{DATA_CHIUSURA_ISCRIZIONI}' => '{DATA_CHIUSURA_ISCRIZIONI}',
            '{ORA_CHIUSURA_ISCRIZIONI}' => '{ORA_CHIUSURA_ISCRIZIONI}',
            '{LOCATION}' => '{LOCATION}',
            '{INDIRIZZO}' => '{INDIRIZZO}',
            '{URL_LANDING}' => '{URL_LANDING}',
            '{URL_COMMUNITY}' => '{URL_COMMUNITY}',
            '{IMMAGINE_EVENTO}' => '{IMMAGINE_EVENTO}',
            '{DATA_INIZIO_STREAMING}' => '{DATA_INIZIO_STREAMING}',
            '{ORA_INIZIO_STREAMING}' => '{ORA_INIZIO_STREAMING}',
            '{ORA_ENTRATA}' => '{ORA_ENTRATA}',
        ];
        return $variables;
    }


    /**
     * @param $from
     * @param $to
     * @param $subjectWithParams
     * @param $textWithParms
     * @param null $event
     * @return bool
     */
    public static function sendEmailTest($from, $to, $subjectWithParams, $textWithParms, $event = null, $footerType = '')
    {
        $newsletterModule = \Yii::$app->getModule('newsletter');
        $eventModule = \Yii::$app->getModule('events');
        if ($newsletterModule && $eventModule) {
            // GET MAILUP TEMPLATE AND SUBTITUTION OF [CONTENT] WITH PLATFORM PERSONALIZED EMAIL TEXT
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $mailupListId = $eventModule->mailup_configurations['mailup_list_id'];
            $originalMessageId = $eventModule->mailup_configurations['original_message_id'];
            $emailDecoded = $mailService->getEmail($mailupListId, $originalMessageId);


            $logo = $event->eventLogo;
            $urlImage = \Yii::$app->params['platform']['backendUrl'] . "/img/img_default.jpg";
            if ($logo) {
                $logoUrl = $logo->getWebUrl();
                $logoUrl = str_replace('it/', '', $logoUrl);
                $urlImage = \Yii::$app->params['platform']['backendUrl'] . $logoUrl;
            }

            $htmlWithParams = self::parseDynamicContentMailup($urlImage, $textWithParms, $emailDecoded, $footerType);

            $htmlWithParams = str_replace('http://[track]/', '', $htmlWithParams);
            $mailModule = \Yii::$app->getModule("email");
            if (isset($mailModule)) {
                $mailModule->defaultLayout = 'layout_without_header_and_footer';
                return $mailModule->send($from, $to, $subjectWithParams, $htmlWithParams);
            }
        }
        return false;
    }


    /**
     * @param $event
     * @throws \yii\base\InvalidConfigException
     */
    public static function sendEventPublishedEmail($event)
    {
        $tos = [];

        $dg = $event->eventGroupReferent;
        $from = \Yii::$app->params['email-assistenza'];
        $userIdsSocial = \Yii::$app->authManager->getUserIdsByRole('EVENT_SOCIAL');
        $userIdsDg = \Yii::$app->authManager->getUserIdsByRole('EVENT_DG');
        $creatorUser = User::findOne($event->created_by);

        $publisher = User::findOne(\Yii::$app->user->id);
        if ($publisher) {
            $tos [] = $publisher->email;

        }
        // get email creator
        if ($creatorUser) {
            $tos [] = $creatorUser->email;
        }

        // Get email user social
        foreach ($userIdsSocial as $user_id) {
            $userSocial = User::find()
                ->andWhere(['user.id' => $user_id])
                ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                ->andWhere(['user_profile.attivo' => true])
                ->one();
            if ($userIdsSocial) {
                $tos [] = $userSocial->email;
            }
        }

        /** get emails dgs **/
        $userDgs = EventGroupReferentMm::find()
            ->andWhere(['event_group_referent_id' => $event->event_group_referent_id])
            ->andWhere(['exclude_from_query' => 1])
            ->andWhere(['user_id' => $userIdsDg])->all();
        foreach ($userDgs as $member) {
            $userdg = User::find()
                ->andWhere(['user.id' => $member->user_id])
                ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                ->andWhere(['user_profile.attivo' => true])
                ->one();
            if ($userdg) {
                $tos [] = $userdg->email;
            }
        }

        $text = AmosEvents::t('amosevents', "L'utente <strong>{nomeCognomePubbl}</strong> appartenente alla DG <strong>{dg}</strong> ha effettuato la pubblicazione per l'evento <strong>{TITOLO}</strong>", [
            'dg' => $dg ? $dg->denominazione : '',
            'nomeCognomePubbl' => $publisher ? $publisher->userProfile->nomeCognome : ''
        ]);

        $text .= "<br><br>" . AmosEvents::t('amosevents', "Di seguito alcuni dettagli relativi all'evento pubblicato:");
        $text .= "<br>" . AmosEvents::t('amosevents', "<strong>Descrizione dell'evento:</strong> {DESCRIZIONE}") . "";
        $text .= "<br>" . AmosEvents::t('amosevents', "<strong>Location dell'evento:</strong> {LOCATION}, {INDIRIZZO}");
        $text .= "<br>" . AmosEvents::t('amosevents', "<strong>Link della landing page:</strong> {URL_LANDING}");
        $text .= "<br>" . AmosEvents::t('amosevents', "<strong>Link per la gestione evento lato backoffice:</strong> " . \Yii::$app->params['platform']['backendUrl'] . "/events/event-dashboard/view?id=" . $event->id);

        $subject = AmosEvents::t('amosevents', "Pubblicazione dell'evento {TITOLO}");
        $subject = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, null, $subject, false);
        $message = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, null, $text, false);
        $result = \open20\amos\events\utility\EventMailUtility::sendEmailTest($from, $tos, $subject, $message, $event);
    }


    /**
     * @param $groupReferentMm
     * @param $user_id
     * @return bool
     */
    public static function sendEmailEnableOperator($groupReferentMm, $user_id)
    {
        $from = \Yii::$app->params['email-assistenza'];;
        $operator = User::findOne($user_id);
        if ($operator) {
            $dg = $groupReferentMm->eventGroupReferent;
            $superUsersIds = \Yii::$app->authManager->getUserIdsByRole('SUPER_USER_EVENT');
            foreach ($superUsersIds as $userId) {
                $user = User::find()
                    ->andWhere(['user.id' => $userId])
                    ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                    ->andWhere(['user_profile.attivo' => true])
                    ->one();
                if ($user) {
                    $to[] = $user->email;
                }
            }
            $subject = AmosEvents::t('amosevents', "L'utente {NomeCognome} ha completato il percorso formativo", [
                'NomeCognome' => $operator->userProfile->nomeCognome,
                'appname' => \Yii::$app->name,
            ]);
            $text = AmosEvents::t('amosevents', "L'utente <strong>{NomeCognome}</strong> della DG {dg} ha completato il percorso formativo ed è pronto ad usufruire del modulo per la gestione di eventi e delle relative anagrafiche di Regione Lombardia.", [
                'NomeCognome' => $operator->userProfile->nomeCognome,
                'dg' => $dg ? $dg->denominazione : ''
            ]);
            $text .= "<br>" . AmosEvents::t('amosevents', "Per renderlo operativo, devi prima associarlo ad un ruolo. Per associare l’utente ad un ruolo, clicca <a href='{link}'>qui</a>.", [
                    'link' => \Yii::$app->params['platform']['backendUrl'] . '/events/event-group-referent-mm/assign-operator-role?id=' . $groupReferentMm->id
                ]);

            return self::sendEmailGeneral($from, $to, $subject, $text);
        }
        return false;

    }

    /**
     * @param $groupReferentMm
     * @param $user_id
     * @param $ruolo
     * @return bool
     */
    public static function sendEmailOperatorEnabledConfirmed($groupReferentMm, $user_id, $ruolo, $permission)
    {
        $from = \Yii::$app->params['email-assistenza'];;
        $operator = User::find()
            ->andWhere(['user.id' => $user_id])
            ->innerJoin('user_profile', 'user_profile.user_id = user.id')
            ->andWhere(['attivo' => true])
            ->one();
        if ($operator) {
            $to[] = $operator->email;
            $dg = $groupReferentMm->eventGroupReferent;
            $subject = AmosEvents::t('amosevents', "Ciao {NomeCognome}, ora sei un utente operativo", [
                'NomeCognome' => $operator->userProfile->nomeCognome,
                'appname' => \Yii::$app->name,
            ]);
            $text = AmosEvents::t('amosevents', "Ciao {NomeCognome}, ora sei un utente operativo.
<br>Ti è stato associato il ruolo <strong>{ruolo}</strong> per la Direzione Generale <strong>{dg}</strong> e puoi iniziare ad utilizzare il Modulo per la gestione di eventi e delle relative anagrafiche di Regione Lombardia.", [
                'NomeCognome' => $operator->userProfile->nomeCognome,
                'dg' => $dg ? $dg->denominazione : '',
                'ruolo' => $ruolo
            ]);
            if ($permission == "EVENT_DG") {
                $text .= "<br>" . AmosEvents::t('amosevents', "Il ruolo Responsabile ti permetterà di gestire gli operatori all’interno della Direzione Generale di tua appartenenza: potrai inserire e associare gli utenti e riceverai una notifica ogni volta che un operatore pubblicherà un nuovo evento. 
                Potrai inoltre creare e gestire gli eventi e importare utenti nel tuo database. 
                Per accedere al backoffice ed iniziare ad usare il Modulo per la gestione di eventi e delle relative anagrafiche di Regione Lombardia, Clicca <a href='{link}'>qui</a>.", [
                        'link' => \Yii::$app->params['platform']['backendUrl']
                    ]);
            } else {
                $text .= "<br>" . AmosEvents::t('amosevents', "Il ruolo Operatore ti permetterà di creare e gestire gli eventi nella Direzione Generale di tua appartenenza. 
Potrai inoltre importare utenti e inserirli nel tuo database. Per accedere al backoffice ed iniziare a creare eventi, clicca <a href='{link}'>qui</a>", [
                        'link' => \Yii::$app->params['platform']['backendUrl']
                    ]);
            }


            return self::sendEmailGeneral($from, $to, $subject, $text);
        }
        return false;

    }

    /**
     * @param $urlImage
     * @param $textWithParms
     * @param $emailDecoded
     * @param string $footerType
     * @return mixed
     */
    public static function parseDynamicContentMailup($urlImage, $textWithParms, $emailDecoded, $footerType = EventEmailTemplates::FOOTER_TYPE_NO_FOOTER)
    {
        $htmlWithParams = str_replace('[content_url_image]', $urlImage, $emailDecoded->Content);
        $htmlWithParams = str_replace('[content]', $textWithParms, $htmlWithParams);

        $footerText = self::getFooterType($footerType);
        $htmlWithParams = str_replace('[footer]', $footerText, $htmlWithParams);

        return $htmlWithParams;
    }

    /**
     * @param $footerType
     * @return string
     */
    public static function getFooterType($footerType)
    {

        $footerText = '';
        $linkDisiscriviti = \Yii::$app->params['platform']['backendUrl'] . '/admin/user-profile/disactivate-user';
        $linkUpdateProfile = \Yii::$app->params['platform']['backendUrl'] . '/admin/user-profile/update-my-profile';
        $linkUnsubscribeEvent = \Yii::$app->params['platform']['backendUrl'] . '/events/event-dashboard/my-events';
        $footerDisiscriviti = "<p style=\"text-align:center;line-height:1.2;word-break:break-word;margin:0\">" . AmosEvents::t('amosevents', "<a href='$linkDisiscriviti'>Desidero cancellarmi dalla piattaforma Eventi di Regione Lombardia</a>") . "";
        $footerTagPreference = "<br>" .  AmosEvents::t('amosevents',"Puoi <a href='{link}'>modificare le preferenze del tuo profilo utente</a> in ogni momento, oppure <a href='{link2}'>non ricevere più mail come questa</a>",['link' => $linkUpdateProfile ,'link2' => $linkUpdateProfile.'?page=notify']);
        $footerUnsubscribeEvent = "<br>" . "Non parteciperò più a questo evento, <a href='$linkUnsubscribeEvent'>" . AmosEvents::t('amosevents', "accedi alla piattaforma") . " per gestire le iscrizioni ai tuoi eventi</a></p>";
        $footerCovid = "Gli eventi di Regione Lombardia si svolgono nel rispetto delle norme anticovid. Ricorda di portare con te la mascherina e di indossarla, igienizza spesso le mani e mantieni la distanza di sicurezza dalle altre persone.";

        switch ($footerType) {
            case EventEmailTemplates::FOOTER_TYPE_NO_FOOTER:
                $footerText = '';
                break;
            case EventEmailTemplates::FOOTER_TYPE_TAG_AND_UNSUBSCRIBE:
                $footerText .= $footerTagPreference;
                $footerText .= $footerDisiscriviti;

                break;
            case EventEmailTemplates::FOOTER_TYPE_ALL_FOOTER:
                $footerText .= $footerCovid."<br>";
                $footerText .= $footerTagPreference;
                $footerText .= $footerUnsubscribeEvent;
                $footerText .= $footerDisiscriviti;

                break;
            default:
                break;
        }
        return $footerText;
    }
}