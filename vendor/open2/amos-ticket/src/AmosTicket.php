<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket
 * @category   CategoryName
 */

namespace open2\amos\ticket;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\user\User;
use open20\amos\core\widget\WidgetAbstract;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\widgets\icons\WidgetIconTicketAdminFaq;
use open2\amos\ticket\widgets\icons\WidgetIconTicketAll;
use open2\amos\ticket\widgets\icons\WidgetIconTicketCategorie;
use open2\amos\ticket\widgets\icons\WidgetIconTicketClosed;
use open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard;
use open2\amos\ticket\widgets\icons\WidgetIconTicketFaq;
use open2\amos\ticket\widgets\icons\WidgetIconTicketProcessing;
use open2\amos\ticket\widgets\icons\WidgetIconTicketWaiting;
use Yii;

/**
 * Class AmosTicket
 * @package open2\amos\ticket
 */
class AmosTicket extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface
{
    public static $CONFIG_FOLDER = 'config';

    public $config = [];
    public $fieldsConfigurations = [
        'required' => [
            'ticket_categoria_id',
            'titolo',
            'descrizione'
        ],
    ];

    public $categoryFieldsHide = [
//        For example...
//        'attiva',
//        'abilita_ticket',
    ];
    public $name = 'Tickets';

    public $categoryReferentsHide = false;

    public $enableOrganizationNameString = false;

    public $disableInfoFields = false;

    public $disableCategory = false;

    public $disableTicketOrganization = false;

    public $disableForwardToAnotherCategory = false;

    public $oneLevelCategories = false;

    public $enableCategoryIcon = true;

    public $searchTicketCreatorWithString = false;

    public $hideUpdateButtonOnTickets = false;

    public $disableTicketManagement = false;

    /**
     * @var array
     */
    public $ticketSearchFieldsHide = [

    ];

    /**
     * @var string emailUtilClass ClassName to make override of open2\amos\ticket\utility\EmailUtil
     */
    public $emailUtilClass = '';

    /**
     * @var bool $enableAdministrativeTicketCategory
     */
    public $enableAdministrativeTicketCategory = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@open2/amos/' . static::getModuleName() . '/models', __DIR__ . '/models');
        \Yii::setAlias('@open2/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');
        \Yii::setAlias('@open2/amos/' . static::getModuleName() . '/widgets/icons', __DIR__ . '/widgets/icons');
        \Yii::setAlias('@open2/amos/' . static::getModuleName() . '/migrations', __DIR__ . '/migrations');

        // \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        //
        // initialize the module with the configuration loaded from config.php
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        Yii::configure($this, $config);
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'ticket';
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconTicketDashboard::className(),
            WidgetIconTicketAdminFaq::className(),
            WidgetIconTicketCategorie::className(),
            WidgetIconTicketAll::className(),
            WidgetIconTicketClosed::className(),
            WidgetIconTicketFaq::className(),
            WidgetIconTicketProcessing::className(),
            WidgetIconTicketWaiting::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'Ticket' => __NAMESPACE__ . '\\' . 'models\Ticket',
            'TicketCategorie' => __NAMESPACE__ . '\\' . 'models\TicketCategorie',
            'TicketCategorieUsersMm' => __NAMESPACE__ . '\\' . 'models\TicketCategorieUsersMm',
            'TicketFaq' => __NAMESPACE__ . '\\' . 'models\TicketFaq',
            'TicketSearch' => __NAMESPACE__ . '\\' . 'models\search\TicketSearch',
            'TicketFaqSearch' => __NAMESPACE__ . '\\' . 'models\search\TicketFaqSearch',
            'TicketCategorieSearch' => __NAMESPACE__ . '\\' . 'models\search\TicketCategorieSearch',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            return 'assistenza';
        } else {
            return 'feed';
        }
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return AmosTicket::instance()->model('TicketFaq');
    }

    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return AmosTicket::instance()->model('TicketFaqSearch');
    }

    /**
     * @param $model
     * @param $data
     * @param array $post
     */
    public static function createTicket($model, $data, $post)
    {
        $user = User::findByUsername($post['RecordDynamicModel']['email']);

        $categoria_id = intval($post['RecordDynamicModel']['categoria']);
        $messageText = $post['RecordDynamicModel']['messaggio'];
        $styledMessageText = AmosTicket::createMessage($messageText);

        $ticket = new Ticket();
        $ticket->ticket_categoria_id = $categoria_id;
        $ticket->titolo = $post['RecordDynamicModel']['oggetto'];
        $ticket->descrizione = $styledMessageText;
        $ticket->created_by = $user->id;

        $ticket->save(false);
    }

    /**
     * @param string $messageText
     * @return string
     */
    public static function createMessage($messageText)
    {
        $styledMessageText = '<p>' . $messageText . '</p>';
        return $styledMessageText;
    }
}
