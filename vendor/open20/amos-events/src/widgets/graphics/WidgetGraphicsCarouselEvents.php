<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\events\widgets\graphics;

use DateTime;
use DateInterval;
use yii\db\Query;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\events\models\search\EventSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetGraphicsCarouselEvents
 * @package open20\amos\events\widgets\graphics
 */
class WidgetGraphicsCarouselEvents extends WidgetGraphic
{

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();

        $this->setLabel(AmosEvents::tHtml('amosevents', 'Events'));
        $this->setDescription(AmosEvents::t('amosevents', 'Elenca gli ultimi Eventi'));

        setlocale(LC_TIME, 'ita', 'it_IT.utf8');
    }
    /**
     * Method for extracting and displaying events in the fronend carousel
     * 
     * @param int $number_of_events
     * @param boolean $current_mounth 
     */

    /**
     * Metodo per l'estrazione degli eventi nel range delle date di pubblicazioni del mese corrente
     * 
     * return array con gli eventi per ogni giorno dell'evento del mese corrente
     *
     * @param int $number_of_events
     * @param boolean $current_mounth
     * @return array $list_events_grouped
     */
    public function getHtml($number_of_events = null, $current_mounth = true)
    {

        // set view 
        $viewToRender = 'agid_carousel_events';

        $moduleLayout = \Yii::$app->getModule('layout');
        if (is_null($moduleLayout)) {
            $viewToRender .= '_old';
        }

        // get current date
        $current_date = new DateTime('now');
        $next         = \Yii::$app->request->get('cal_next');
        $plus         = 0;
        if (!empty($next)) {
            $plus         = $next *4 ;
            $current_date = $current_date->add(new DateInterval("P{$plus}D"));
        }
        // $current_date->setTime(0, 0);
        $last_month_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') + 1, 0, date('Y-m-d H:i:s')));

        // extract id events
        $list_events = Event::find()
            ->select([
                "event.*",
                // "DATE_FORMAT(begin_date_hour, '%d-%m-%Y') as begin_date_hour",
                // "DATE_FORMAT(end_date_hour, '%d-%m-%Y') as end_date_hour",
                // "DATE_FORMAT(publication_date_begin, '%d-%m-%Y') as publication_date_begin",
                // "DATE_FORMAT(publication_date_end, '%d-%m-%Y') as publication_date_end",
                "DATE_FORMAT(begin_date_hour, '%Y-%m-%d') as begin_date_hour",
                "DATE_FORMAT(end_date_hour, '%Y-%m-%d') as end_date_hour",
                "DATE_FORMAT(publication_date_begin, '%Y-%m-%d') as publication_date_begin",
                "DATE_FORMAT(publication_date_end, '%Y-%m-%d') as publication_date_end",
            ])
            ->andWhere(['or',
                // casistica nel caso ci siano soltanto le date  del'evento e non quelle di pubblicazione
                ['and',
                    ['status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED],
                    ['in_evidenza' => '1'],
                    ['primo_piano' => '1'],
                    ['deleted_at' => null],
                    // dove data fine evento è settata oppure non è settata
                    ['or',
                        ['>=', 'end_date_hour', $current_date->format('Y-m-d')],
                        ['end_date_hour' => null]
                    ],
                    // data inizio pubblicazione
                    ['<=', 'publication_date_begin', $current_date->format('Y-m-d')],
                    // data fine pubblicazione o null oppure maggiore della data corrente
                    ['or',
                        ['>=', 'publication_date_end', $current_date->format('Y-m-d')],
                        ['publication_date_end' => null],
                    ]
                ]
            ])
            ->orderBy('begin_date_hour, end_date_hour, publication_date_begin, publication_date_end')
            ->all();

//        $id_events = ArrayHelper::getColumn(
//                    $list_events,
//                function ($element) {
//                    return $element['id'];
//                }
//            );
//
//        $list_events = Event::find()->andWhere(['id' => $id_events])->all();
        // data inizio degli eventi oggi
        $current_date_day_1 = clone ($current_date);
        $current_date_day_2 = clone ($current_date);
        $current_date_day_3 = clone ($current_date);



        $current_date_day_4 = clone ($current_date);
        $current_date_day_5 = clone ($current_date);
        $current_date_day_6 = clone ($current_date);
        $current_date_day_7 = clone ($current_date);
        

        $arrayDateEvents    = [];
        $arrayDateEvents[]  = $current_date->format('Y-m-d');
        $arrayDateEvents[]  = $current_date_day_1->add(new DateInterval('P1D'))->format('Y-m-d');
        $arrayDateEvents[]  = $current_date_day_2->add(new DateInterval('P2D'))->format('Y-m-d');
        $arrayDateEvents[]  = $current_date_day_3->add(new DateInterval('P3D'))->format('Y-m-d');



        $arrayDateEvents[]  = $current_date_day_4->add(new DateInterval('P4D'))->format('Y-m-d');
        $arrayDateEvents[]  = $current_date_day_5->add(new DateInterval('P5D'))->format('Y-m-d');
        $arrayDateEvents[]  = $current_date_day_6->add(new DateInterval('P6D'))->format('Y-m-d');
        $arrayDateEvents[]  = $current_date_day_7->add(new DateInterval('P7D'))->format('Y-m-d');
      

        // data fine degli eventi ultimo giorno del mese
        $last_month_day = (int) date('d', mktime(0, 0, 0, date('m') + 1, 0, date('Y')));

        // array di ragruppamento degli eventi per gg
        $list_events_grouped = array();

        // ciclo per i giorni a partire dal giorno corrente del mese fino all'ultimo giorno del messe
        foreach ($arrayDateEvents as $i) {

            // ciclo per ogni evento della lista
            foreach ($list_events as $key => $event) {

                $begin_date_hour = (null != $event->begin_date_hour) ? new DateTime($event->begin_date_hour) : null;
                $end_date_hour   = (null != $event->end_date_hour) ? new DateTime($event->end_date_hour) : null;

                $publication_date_begin = (null != $event->publication_date_begin) ? new DateTime($event->publication_date_begin)
                        : null;
                $publication_date_end   = (null != $event->publication_date_end) ? new DateTime($event->publication_date_end)
                        : null;


                $current_date_cicle_for = new DateTime($i);


                /**
                 * controllo che
                 * la data del ciclo sia minore della data fine 
                 */
                if (($end_date_hour == null || $current_date_cicle_for->format('Y-m-d') <= $end_date_hour->format('Y-m-d'))
                    && $current_date_cicle_for->format('Y-m-d') >= $begin_date_hour->format('Y-m-d')) {

                    // inserimento raggruppato dell'evento per giorno
                    $tmp                         = $current_date_cicle_for->format('Y-m-d');
                    $list_events_grouped[$tmp][] = $event;
                }
            }
        }

        return $this->render($viewToRender,
                [
                'list_events_grouped' => $list_events_grouped,
                'widget' => $this,
                    'next' => $next,
        ]);
    }
}