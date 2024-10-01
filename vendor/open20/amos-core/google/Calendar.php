<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Proscriptions/license-default.txt to change this proscription
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace open20\amos\core\google;

use open20\amos\core\user\User;
use open20\amos\core\module\BaseAmosModule;

/**
 * Class that 
 * 
 * ```php
 * $events = [];
 * $calendar = new Calendar(['pathJsonKey' => '@common/uploads/.../project-1323456abc.json']);
 * 
 * $allCalendars = $calendar->getCalendarList();
 * 
 * $calendars = [];
 * foreach($allCalendars as $v){
 * 	$calendars[$v->id] = ['title' => $v->summary, 'backgroundColor' => $v->backgroundColor];
 * }
 * 
 * foreach($calendars as $calId => $opt){
 *      $calendar->calendarId = $calId;
 *      $eventsCalendar = $calendar->getEvents();
 *          foreach($eventsCalendar as $cal){
 *      	$Event = new \yii2fullcalendar\models\Event();
 *              $Event->id = $cal->id;
 *              $Event->title = $cal->summary;
 *              $Event->start = date('Y-m-d\TH:i:s\Z', strtotime((!empty($cal->start->date)? $cal->start->date : $cal->start->dateTime)));
 *              $Event->end = date('Y-m-d\TH:i:s\Z', strtotime((!empty($cal->end->date)? $cal->end->date : $cal->end->dateTime)));
 *              $Event->color = $opt['backgroundColor'];
 *              $Event->url = (!empty($cal->hangoutLink)? $cal->hangoutLink : $cal->htmlLink);
 *              $Event->allDay = (!empty($cal->start->date)? true : false);
 *              $Event->nonstandard = [
 *                  'description' =>  $cal->description,
 *                  'attendees' => $cal->attendees,
 *              ];
 *              $events[] = $Event;
 *          }
 * }
 * 
 * echo \yii2fullcalendar\yii2fullcalendar::widget([
 *      'events'=> $events,
 * ]);
 * 
 */
class Calendar {

    static $client;
    static $email;
    public $forceEmail;
    public $pathJsonKey;
    public $scope = [
        'https://www.googleapis.com/auth/calendar.readonly',
        'https://www.googleapis.com/auth/calendar.events.readonly'
    ];
    public $maxResults = 100;
    public $maxNumCalendar = 100;
    public $singleEvents = true;
    public $iCalUID;

    /**
     * Default show all events, possibile values: see const EVENT_TYPE
     * @var string
     */
    public $eventTypes;

    /**
     * Default no restrictions, possible values: see const ACCESS_ROLE
     * @var type
     */
    public $minAccessRole;

    const ACCESS_ROLE = [
        'freeBusyReader',
        'owner',
        'reader',
        'writer',
    ];

    /**
     * Possible values: 'startTime' is singleEvent is true, 'updated' 
     * @var string 
     */
    public $orderBy;

    /**
     * 
     * @var type
     */
    public $q;

    const EVENT_TYPE = [
        'default',
        'focusTime',
        'outOfOffice',
        'workingLocation',
    ];

    /**
     * 
     * @var string $timeMin
     */
    public $timeMin;

    /**
     * 
     * @var string $timeMax
     */
    public $timeMax;

    /**
     * 
     * @var string $calendarId
     */
    public $calendarId;

    /**
     * 
     * @param array $config
     */
    public function __construct($config = []) {
        foreach ($config as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * 
     * @return Google\Client
     */
    public function getClient() {
        try {
            $email = $this->getEmail();
            if (true || empty(self::$client)) {
                $client = new \Google_Client();
                $client->setAuthConfig($this->getPathKeyJson());
                // $client->useApplicationDefaultCredentials();
                $client->addScope(['https://www.googleapis.com/auth/calendar.readonly',
                    'https://www.googleapis.com/auth/calendar.events.readonly'
                ]);
                $client->setSubject($email);
                self::$client = $client;
            }
            return self::$client;
        } catch (\Exception $e) {
            \Yii::getLogger()->log($e->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * 
     * @return string
     */
    public function getEmail() {
        try {
            if (!empty($this->forceEmail)) {
                return $this->forceEmail;
            }
            if (empty(self::$email)) {
                $user = User::findOne(\Yii::$app->user->id);
                if (!empty($user)) {
                    self::$email = $user->email;
                }
            }
            return self::$email;
        } catch (Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
            return '';
        }
    }

    /**
     * 
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function getPathKeyJson() {
        $path = $this->pathJsonKey;
        if (strpos($this->pathJsonKey, '@') !== false) {
            $path = \Yii::getAlias($this->pathJsonKey);
        }
        if (!file_exists($path)) {
            throw new \yii\web\NotFoundHttpException(BaseAmosModule::t('amoscore', 'The path to the Google Calendar service configuration Json file does not exist or is not set.'), 404);
        }
        return $path;
    }

    /**
     * See the specifics of the object at this url 
     * https://developers.google.com/resources/api-libraries/documentation/calendar/v3/php/latest/class-Google_Service_Calendar_Event.html
     * Api reference: https://developers.google.com/calendar/api/v3/reference/events/list?hl=it
     * @return array of Google\Service\Calendar\Event
     */
    public function getEvents() {
        try {
            $service = new \Google_Service_Calendar($this->getClient());

            $optParams = [
                'maxResults' => $this->maxResults,
                //  'orderBy' => 'startTime',
                'singleEvents' => $this->singleEvents,
                'timeMin' => (empty($this->timeMin) ? date('c') : date('c', strtotime($this->timeMin))),
                'timeMax' => (empty($this->timeMax) ? date('c', strtotime('+10 days', strtotime(date('Y-m-d')))) : date('c', strtotime($this->timeMax))),
            ];

            if (!empty($this->eventTypes)) {
                $optParams['eventTypes'] = $this->eventTypes;
            }
            if (!empty($this->orderBy)) {
                $optParams['orderBy'] = $this->orderBy;
            }
            if (!empty($this->q)) {
                $optParams['q'] = $this->q;
            }

            if (!empty($this->iCalUID)) {
                //Return only one event
                $optParams['iCalUID'] = $this->iCalUID;
            }

            $results = $service->events->listEvents($this->calendarId, $optParams);
            return $results->items;
        } catch (Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * See the specifics of the object at this url 
     * https://developers.google.com/resources/api-libraries/documentation/calendar/v3/php/latest/class-Google_Service_Calendar_CalendarListEntry.html 
     * Api reference: https://developers.google.com/calendar/api/v3/reference/calendarList/list?hl=it
     * @return array of Google\Service\Calendar\CalendarListEntry
     */
    public function getCalendarList() {
        try {
            $client = $this->getClient();
            $service = new \Google_Service_Calendar($client);

            $optParams = [
                'maxResults' => $this->maxNumCalendar,
            ];

            if (!empty($this->minAccessRole)) {
                $optParams['minAccessRole'] = $this->minAccessRole;
            }

            $results = $service->calendarList->listCalendarList($optParams);
            return $results->items;
        } catch (Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
            return [];
        }
    }
}
