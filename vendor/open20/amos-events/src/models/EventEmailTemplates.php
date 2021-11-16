<?php

namespace open20\amos\events\models;

use open20\amos\een\AmosEen;
use open20\amos\events\AmosEvents;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_email_templates".
 */
class EventEmailTemplates extends \open20\amos\events\models\base\EventEmailTemplates
{
    const TEMPLATE_SAVE_THE_DATE = 'save_the_date';
    const TEMPLATE_REGISTRATION_EMAIL = 'registration_email';
    const TEMPLATE_CONFIRM_REGISTRATION = 'confirm_registration';
    const TEMPLATE_SEND_TICKETS = 'send_tickets';
    const TEMPLATE_WAITING_LIST = 'info_waiting_list';


    const FOOTER_TYPE_NO_FOOTER = 'no_footer';
    const FOOTER_TYPE_TAG_AND_UNSUBSCRIBE = 'tag_unsubscribe_platform_footer';
    const FOOTER_TYPE_ALL_FOOTER = 'all_footer';

    public $email_test_1;
    public $email_test_2;
    public $email_test_3;
    public $email_test_4;
    public $email_test_5;

    public function getTypeEmailTest($string)
    {
        switch ($string) {
            case 'email_test_1' :
                return 'save_the_date';
                break;
            case 'email_test_2' :
                return 'registration_email';
                break;
            case 'email_test_3' :
                return 'confirm_registration';
                break;
            case 'email_test_4' :
                return 'send_tickets';
                break;
            case 'email_test_5' :
                return 'info_waiting_list';
                break;
            default:
                return 'save_the_date';
        }

    }


    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->save_the_date = self::getDefaultText()[self::TEMPLATE_SAVE_THE_DATE];
            $this->registration_email = self::getDefaultText()[self::TEMPLATE_REGISTRATION_EMAIL];
            $this->confirm_registration = self::getDefaultText()[self::TEMPLATE_CONFIRM_REGISTRATION];
            $this->send_tickets = self::getDefaultText()[self::TEMPLATE_SEND_TICKETS];
            $this->info_waiting_list = self::getDefaultText()[self::TEMPLATE_WAITING_LIST];

            $this->save_the_date_subject = self::getDefaultSubject()[self::TEMPLATE_SAVE_THE_DATE . '_subject'];
            $this->registration_email_subject = self::getDefaultSubject()[self::TEMPLATE_REGISTRATION_EMAIL . '_subject'];
            $this->confirm_registration_subject = self::getDefaultSubject()[self::TEMPLATE_CONFIRM_REGISTRATION . '_subject'];
            $this->send_tickets_subject = self::getDefaultSubject()[self::TEMPLATE_SEND_TICKETS . '_subject'];
            $this->info_waiting_list_subject = self::getDefaultSubject()[self::TEMPLATE_WAITING_LIST . '_subject'];

        }
    }

    /**
     * @return array
     */
    public static function getLabelTemplate()
    {
        return [
            'save_the_date' => AmosEvents::t('amosevents', "Save the date"),
            'registration_email' => AmosEvents::t('amosevents', "Invito alla registrazione"),
            'info_waiting_list' => AmosEvents::t('amosevents', "Info lista di attesa"),
            'confirm_registration' => AmosEvents::t('amosevents', "Conferma registrazione"),
            'send_tickets' => AmosEvents::t('amosevents', "Invio biglietti"),
        ];
    }

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['email_test_1', 'email_test_2', 'email_test_3', 'email_test_4', 'email_test_5'], 'safe']
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'event_id',
                'label' => $labels['event_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'save_the_date',
                'label' => $labels['save_the_date'],
                'type' => 'text'
            ],
            [
                'slug' => 'save_the_date_subject',
                'label' => $labels['save_the_date_subject'],
                'type' => 'text'
            ],
            [
                'slug' => 'registration_email',
                'label' => $labels['registration_email'],
                'type' => 'text'
            ],
            [
                'slug' => 'registration_email_subject',
                'label' => $labels['registration_email_subject'],
                'type' => 'text'
            ],
            [
                'slug' => 'confirm_registration',
                'label' => $labels['confirm_registration'],
                'type' => 'text'
            ],
            [
                'slug' => 'confirm_registration_subject',
                'label' => $labels['confirm_registration_subject'],
                'type' => 'text'
            ],
            [
                'slug' => 'info_waiting_list',
                'label' => $labels['info_waiting_list'],
                'type' => 'text'
            ],
            [
                'slug' => 'info_waiting_list_subject',
                'label' => $labels['info_waiting_list_subject'],
                'type' => 'text'
            ],
            [
                'slug' => 'send_tickets',
                'label' => $labels['send_tickets'],
                'type' => 'text'
            ],
            [
                'slug' => 'send_tickets_subject',
                'label' => $labels['send_tickets_subject'],
                'type' => 'text'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return array
     */
    public function getDefaultText()
    {
        $module = \Yii::$app->getModule('events');
        $linkApp = null;
        if($module){
            $linkApp = $module->url_download_app_mobile;
        }
        return [
            'save_the_date' => AmosEvents::t('amosevents', "<p>Gentile {NOME} {COGNOME}, <br>siamo felici di presentarti in anteprima l’evento '{TITOLO}' che si terrà il {DATA_INIZIO} presso {LOCATION} {INDIRIZZO}.<br><br>Di cosa si tratta?<br>'{TITOLO}' è {DESCRIZIONE}.<br><br>Siamo sicuri che l’iniziativa sia di tuo interesse: segna la data del {DATA_INIZIO} sull’agenda, noi ti aspettiamo!<br><br> Segui la pagina dell'iniziativa per tutti gli aggiornamenti: puoi farlo <a href=\"{URL_LANDING}\">qui</a> oppure sulla nostra <a href='$linkApp'>app Eventi Lombardia</a><br><br> A presto!</p>"),
            'registration_email' => AmosEvents::t('amosevents', "<p>Gentile {NOME} {COGNOME}, <br>siamo felici di invitarti all’evento '{TITOLO}' che si terrà il {DATA_INIZIO} presso {LOCATION} {INDIRIZZO}.<br><br> Di cosa si tratta?<br>'{TITOLO}' è {DESCRIZIONE}. Siamo sicuri che l’iniziativa sia di tuo interesse: ricordati di completare la registrazione entro le {ORA_CHIUSURA_ISCRIZIONI} del {DATA_CHIUSURA_ISCRIZIONI} per non perdertelo! <br><br> Segui la pagina dell'iniziativa per tutti gli aggiornamenti: puoi farlo <a href='{URL_LANDING}'>qui</a> oppure sulla nostra <a href='$linkApp'>app Eventi Lombardia</a>.<br><br> A presto!</p>"),
            'info_waiting_list' => AmosEvents::t('amosevents', "<p>Gentile {NOME} {COGNOME}, <br>ci dispiace doverti informare che abbiamo esaurito i posti per'{TITOLO}' in programma {DATA_INIZIO} presso {LOCATION} {INDIRIZZO}.<br><br> Abbiamo però inserito il tuo nominativo in lista di attesa e ti contatteremo appena tornerà disponibile un posto. <br><br> Per ora ti ringraziamo, continua a seguire la <a href='{URL_LANDING}'>pagina dell'evento</a> per ogni aggiornamento!<br><br>C’è anche un modo più veloce per farlo: scarica <a href='$linkApp'>l’app Eventi Lombardia!</a><br><br> A presto.</p>"),
            'confirm_registration' => AmosEvents::t('amosevents', "<p>Gentile {NOME} {COGNOME}, <br>ti ricordiamo che l’inizio dell’evento {TITOLO} è in programma per le ore {ORA_INIZIO} del {DATA_INIZIO} presso {LOCATION}.<br>Continua a seguirci per ogni aggiornamento!<br>Puoi farlo <a href='{URL_LANDING}'>qui</a> oppure sulla nostra <a href='$linkApp'>app Eventi Lombardia</a>. <br><br>A presto, ti aspettiamo!</p>"),
            'send_tickets' => AmosEvents::t('amosevents', "<p>Gentile {NOME} {COGNOME}, <br>siamo felici di inviarti il biglietto per l’evento '{TITOLO}' che si terrà il {DATA_INIZIO} presso {LOCATION} {INDIRIZZO}.<br><br>Il biglietto è nominativo e ti garantirà l’accesso a {LOCATION} che ospiterà l’iniziativa. L’accesso sarà consentito solo previo riconoscimento personale. Ti aspettiamo!<br><br>Segui <a href='{URL_LANDING}'>la pagina dell'iniziativa</a> per tutti gli aggiornamenti oppure scarica <a href='$linkApp'>l’app Eventi Lombardia</a>!<br><br> A presto!</p>"),
        ];
    }

    /**
     * @return array
     */
    public function getDefaultSubject()
    {
        return [
            'save_the_date_subject' => AmosEvents::t('amosevents', "{NOME}, il {DATA_INIZIO} è in arrivo un fantastico evento!"),
            'registration_email_subject' => AmosEvents::t('amosevents', "{NOME}, ti invitiamo a iscriverti all’evento '{TITOLO}'!"),
            'info_waiting_list_subject' => AmosEvents::t('amosevents', "{NOME}, abbiamo esaurito i posti (per ora)!"),
            'confirm_registration_subject' => AmosEvents::t('amosevents', "Registrazione {TITOLO}"),
            'send_tickets_subject' => AmosEvents::t('amosevents', "{NOME}, ecco il tuo biglietto per '{TITOLO}'!"),
        ];
    }

    /**
     * @return array
     */
    public function getFooterText()
    {
        return [
            'save_the_date' => self::FOOTER_TYPE_TAG_AND_UNSUBSCRIBE,
            'registration_email' => self::FOOTER_TYPE_TAG_AND_UNSUBSCRIBE,
            'info_waiting_list' => self::FOOTER_TYPE_TAG_AND_UNSUBSCRIBE,
            'confirm_registration' => self::FOOTER_TYPE_ALL_FOOTER,
            'send_tickets' => self::FOOTER_TYPE_TAG_AND_UNSUBSCRIBE,
        ];
    }
}
