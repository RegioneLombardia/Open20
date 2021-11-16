<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\utility
 * @category   CategoryName
 */

namespace backend\modules\campains\utility;

use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\utility\CampainsUtility;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * 
 * 
 * 
 * 
 * 
 * 
 * Class UIUtility
 * @package backend\modules\campains\utility
 */
class ExternalContentUtility
{

    /*
        1
        Innovazione
        Progetti, eventi e incontri promossi da Regione Lombardia per supportare l\'innovazione.
        innovazione.svg

        2
        Ambiente e Sviluppo sostenibile
        Iniziative, progetti e incentivi  attivati da Regione Lombardia per tutelare la qualità dell\'aria, dell\'ambiente, la biodiversità, la sostenibilità
        ambiente-e-sviluppo-sostenibile.svg

        3
        Cultura, tempo libero e turismo
        Idee e proposte per il tempo libero: dalla cultura (mostre, musei, concerti) al territorio (agriturismi, rifugi, itinerari turistici).
        cultura-tempo-libero-e-turismo.svg

        4
        Mobilità, trasporti e blocchi del traffico
        Aggiornamenti su cambi di viabilità, tariffe e abbonamenti agevolati per i mezzi pubblici, incentivi per la mobilità sostenibile.
        mobilita-e-trasporti.svg

        5
        Informazioni istituzionali
        Comunicazioni, provvedimenti, ordinanze e avvisi ufficiali di Regione Lombardia.
        informazioni-istituzionali.svg

        6
        Istruzione, formazione e lavoro
        Offerta formativa in Lombardia, agevolazioni e opportunità di inserimento nel mondo de lavoro per giovani e adulti. 
        istruzione-formazione-e-lavoro.svg

        7
        Bandi, contributi e concorsi
        Notizie sui bandi promossi da Regione Lombardia e finanziati con fondi regionali, nazionali ed europei. Avvisi sui concorsi e borse di studio.
        bandi-contributi-e-concorsi.svg

        8
        Allerta e info di pubblica utilità
        Informazioni e segnalazioni di pubblica utilità, sia in prevenzione (es. allerta meteo) che in emergenza (es. sanitaria, idrogeologica). 
        allerta-e-info-di-pubblica-utilita.svg

        9
        Salute e prevenzione
        Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.
        salute-e-prevenzione.svg

        10
        Sport
        Grandi eventi, servizi, impianti, incentivi per la pratica dello sport in Lombardia.
        sport.svg

        11
        Agricoltura e alimentazione
        Eventi e iniziative di promozione dei prodotti agroalimentari lombardi e di educazione alimentare.
        agricoltura-e-alimentazione.svg

        12
        Sociale
        Iniziative attivate da Regione Lombardia per sostenere le persone in difficoltà e le famiglie in condizioni di vulnerabilità economica e sociale.
        sociale.svg

        13
        Commercio
        Informazioni, aggiornamenti normativi, progetti e finanziamenti per gli operatori commerciali.
        commercio.svg

        14
        Agricoltura
        Informazioni, servizi, avvisi, bandi e agevolazioni per gli operatori del settore agricolo e agroalimentare.
        agricoltura-e-alimentazione.svg

        15
        Sicurezza
        Aggiornamenti sulle iniziative promosse a favore di enti e operatori per polizia locale, sicurezza stradale e sicurezza urbana. 
        sicurezza.svg

        16
        Territorio
        Aggiornamenti in materia di Piani territoriali, paesaggio, difesa del suolo, assetti idrogeologici, valutazioni ambientali.
        territorio.svg

        17
        Sanità
        Notizie, bandi e informazioni per gli enti e gli operatori del settore sanitario. 
        salute-e-prevenzione.svg
    */

    private static $externalContentStructure = [
        1 => [
            'name' => 'Portale Regione Lombardia',
            'resources' => [
                1 => [
                    'id' => 1,
                    'name' => 'Schede informative',
                    'link_context' => [8],
                    'url' => 'https://www.regione.lombardia.it/wps/wcm/connect/DisegnoPRL/FeedRSS/stFeedRssRedazionali',
                ],
                2 => [
                    'id' => 2,
                    'name' => 'Eventi',
                    'link_context' => [5],
                    'url' => 'https://www.regione.lombardia.it/wps/wcm/connect/DisegnoPRL/FeedRSS/stFeedRssEventi',
                ],
                3 => [
                    'id' => 3,
                    'name' => 'Servizi e procedimenti',
                    'link_context' => [5],
                    'url' => 'https://www.regione.lombardia.it/wps/wcm/connect/DisegnoPRL/FeedRSS/stFeedRssServiziProcedimenti',
                ],
                4 => [
                    'id' => 4,
                    'name' => 'Avvisi',
                    'link_context' => [8],
                    'url' => 'https://www.regione.lombardia.it/wps/wcm/connect/DisegnoPRL/FeedRSS/stFeedRssAvvisi',
                ],
                5 => [
                    'id' => 5,
                    'name' => 'Pubblicazioni',
                    'link_context' => [8],
                    'url' => 'https://www.regione.lombardia.it/wps/wcm/connect/DisegnoPRL/FeedRSS/stFeedRssPubblicazioni',
                ],
            ]
        ],
        // https://braintechdev.it/feed/pcenter?cat=1004,3151&posts_per_page=10&date_query=before&inclusive1=1&year1=2020&month1=9&day1=30&csrp_key=Ln.2020-Online
        2 => [
            'name' => 'Portale Lombardia Notizie',
            'resources' => []
        ],

        3 => [
            'name' => 'Portale Gestione Eventi',
            'resources' => []
        ],

        // https://events.devel.demotestwip.it/rss/rss/rss
    ];

    /**
     * Struttura fissa 
     * key 1 'Portale Regione Lombardia'
     * key 2 'Portale Lombardia notizie'
     * key 3 'Portale Eventi',
     *
     * @param string $date
     * @return array
     */
    public static function getExternalContentStructure($date = null)
    {
        $data = new DateTime($date);

        // PORTALE LOMBARDIA NOTIZIE
        // PROD https://www.lombardianotizie.online/feed/pcenter?cat=1004,3151&posts_per_page=10&date_query=before&inclusive1=1&year1=2020&month1=9&day1=30&csrp_key=Ln.2020-Online
        // PRE-PROD https://www.lnews-preprod.servizirl.it/feed/pcenter?cat=1004,3151&posts_per_page=10&date_query=before&inclusive1=1&year1=2020&month1=9&day1=30&csrp_key=Ln.2020-Online
        $urlBase = 'https://www.lombardianotizie.online/feed/pcenter';
        $getToday = '&year1='.$data->format('Y').'&month1='.$data->format('m').'&day1=' . $data->format('d');

        /*
        $resourcesPLN = [
            1 => [
                'id' => 1,
                'name' => 'Attualità',
                'link_context' => [3,5,1,8],
                'url' => $urlBase . '?cat=5,25,1035,1004,36,40,20,42&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            2 => [
                'id' => 2,
                'name' => 'Salute e sociale',
                'link_context' => [6],
                'url' => $urlBase . '?cat=21,22,9,24,41&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            3 => [
                'id' => 3,
                'name' => 'Territorio',
                'link_context' => [2,4,8],
                'url' => $urlBase . '?cat=7,14,71,19,8&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            4 => [
                'id' => 4,
                'name' => 'Economia',
                'link_context' => [5],
                'url' => $urlBase . '?cat=1289,37,11,3&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
        ];
        */

        $resourcesPLN = [
            1 => [
                'id' => 1,
                'name' => 'ATTUALITA\'',
                'link_context' => [],
                'url' => $urlBase . '?cat=5&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            2 => [
                'id' => 2,
                'name' => 'ATTUALITA\' - Autonomia e cultura',
                'link_context' => [3],
                'url' => $urlBase . '?cat=25&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            3 => [
                'id' => 3,
                'name' => 'ATTUALITA\' - Olimpiadi',
                'link_context' => [3],
                'url' => $urlBase . '?cat=1035&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            4 => [
                'id' => 4,
                'name' => 'ATTUALITA\' - Presidenza',
                'link_context' => [5],
                'url' => $urlBase . '?cat=1004&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            5 => [
                'id' => 5,
                'name' => 'ATTUALITA\' - Istruzione, Università, Ricerca, Innovazione e semplificazione',
                'link_context' => [1],
                'url' => $urlBase . '?cat=36&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            6 => [
                'id' => 6,
                'name' => 'ATTUALITA\' - Sicurezza',
                'link_context' => [8],
                'url' => $urlBase . '?cat=40&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            7 => [
                'id' => 7,
                'name' => 'ATTUALITA\' - Sport',
                'link_context' => [3],
                'url' => $urlBase . '?cat=20&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            23 => [
                'id' => 23,
                'name' => 'ATTUALITA\' - Sviluppo Città metropolitana, Giovani e Comunicazione',
                'link_context' => [],
                'url' => $urlBase . '?cat=6024&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            8 => [
                'id' => 8,
                'name' => 'ATTUALITA\' - Turismo',
                'link_context' => [3],
                'url' => $urlBase . '?cat=42&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            9 => [
                'id' => 9,
                'name' => 'SALUTE E SOCIALE',
                'link_context' => [6],
                'url' => $urlBase . '?cat=21&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            10 => [
                'id' => 10,
                'name' => 'SALUTE E SOCIALE - Famiglia, Disabilità e Pari opportunità',
                'link_context' => [],
                'url' => $urlBase . '?cat=22&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            12 => [
                'id' => 12,
                'name' => 'SALUTE E SOCIALE - Casa e Housing sociale',
                'link_context' => [],
                'url' => $urlBase . '?cat=24&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            13 => [
                'id' => 13,
                'name' => 'SALUTE E SOCIALE - Welfare',
                'link_context' => [9],
                'url' => $urlBase . '?cat=41&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            14 => [
                'id' => 14,
                'name' => 'TERRITORIO',
                'link_context' => [],
                'url' => $urlBase . '?cat=7&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            15 => [
                'id' => 15,
                'name' => 'TERRITORIO - Ambiente e clima',
                'link_context' => [2],
                'url' => $urlBase . '?cat=14&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            16 => [
                'id' => 16,
                'name' => 'TERRITORIO - Enti locali e montagna',
                'link_context' => [],
                'url' => $urlBase . '?cat=71&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            17 => [
                'id' => 17,
                'name' => 'TERRITORIO - Infrastrutture e trasporti',
                'link_context' => [4],
                'url' => $urlBase . '?cat=19&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            18 => [
                'id' => 18,
                'name' => 'TERRITORIO - Urbanistica e protezione civile',
                'link_context' => [8],
                'url' => $urlBase . '?cat=8&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            19 => [
                'id' => 19,
                'name' => 'ECONOMIA',
                'link_context' => [],
                'url' => $urlBase . '?cat=1289&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            20 => [
                'id' => 20,
                'name' => 'ECONOMIA - Agricoltura',
                'link_context' => [],
                'url' => $urlBase . '?cat=37&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            21 => [
                'id' => 21,
                'name' => 'ECONOMIA - Bilancio',
                'link_context' => [5],
                'url' => $urlBase . '?cat=11&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            22 => [
                'id' => 22,
                'name' => 'ECONOMIA - Sviluppo economico',
                'link_context' => [5],
                'url' => $urlBase . '?cat=3&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
            11 => [
                'id' => 11,
                'name' => 'ECONOMIA - Formazione e Lavoro',
                'link_context' => [6],
                'url' => $urlBase . '?cat=9&posts_per_page=30&date_query=before&inclusive1=1'.$getToday.'&csrp_key=Ln.2020-Online',
            ],
        ];
        // PORTALE EVENTI
        $urlBase = 'https://events-pre-prod.stage.demotestwip.it/rss/rss/rss';
        
        $resourcesPE = [
            1 => [
                'id' => 1,
                'name' => 'Agricoltura',
                'link_context' => [11,14],
                'url' => $urlBase . '?code=preference_1',
            ],
            2 => [
                'id' => 2,
                'name' => 'Ambiente e territorio',
                'link_context' => [2],
                'url' => $urlBase . '?code=preference_2',
            ],
            3 => [
                'id' => 3,
                'name' => 'Cultura e tempo libero',
                'link_context' => [3],
                'url' => $urlBase . '?code=preference_3',
            ],
            4 => [
                'id' => 4,
                'name' => 'Famiglia e società',
                'link_context' => [12],
                'url' => $urlBase . '?code=preference_4',
            ],
            5 => [
                'id' => 5,
                'name' => 'Istituzionale',
                'link_context' => [5],
                'url' => $urlBase . '?code=preference_5',
            ],
            6 => [
                'id' => 6,
                'name' => 'Istruzione e lavoro',
                'link_context' => [6],
                'url' => $urlBase . '?code=preference_6',
            ],
            7 => [
                'id' => 7,
                'name' => 'Salute e welfare',
                'link_context' => [9, 17],
                'url' => $urlBase . '?code=preference_7',
            ],
            8 => [
                'id' => 8,
                'name' => 'Sport',
                'link_context' => [10],
                'url' => $urlBase . '?code=preference_8',
            ],
            9 => [
                'id' => 9,
                'name' => 'Trasporto e mobilità',
                'link_context' => [4],
                'url' => $urlBase . '?code=preference_9',
            ],
            10 => [
                'id' => 10,
                'name' => 'Turismo',
                'link_context' => [3],
                'url' => $urlBase . '?code=preference_10',
            ],
        ];

        self::$externalContentStructure[2]['resources'] = $resourcesPLN;
        self::$externalContentStructure[3]['resources'] = $resourcesPE;

        return self::$externalContentStructure;
    }


    public static function getSources()
    {
        return ArrayHelper::getColumn(self::getExternalContentStructure(), 'name');
    }

    public static function getSourceById($id)
    {
        if(isset(self::getExternalContentStructure()[$id])) {
            return self::getExternalContentStructure()[$id];
        }

        return null;
    }

    public static function getUrlByIdAndResource($id, $resourceId)
    {
        if(isset(self::getExternalContentStructure()[$id]['resources'][$resourceId])) {
            return self::getExternalContentStructure()[$id]['resources'][$resourceId]['url'];
        }
        return null;
    }

}
