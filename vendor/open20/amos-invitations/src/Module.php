<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations
 * @category   CategoryName
 */

namespace open20\amos\invitations;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\invitations\widgets\icons\WidgetIconInvitations;
use open20\amos\invitations\widgets\icons\WidgetIconInvitationsAll;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package open20\amos\invitations
 */
class Module extends AmosModule implements ModuleInterface
{
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @var string $name
     */
    public $name = 'invitations';

    /**
     *
     * @var string $subjectPlaceholder
     * Valore del placeholder per la label che corrisponde all'oggetto della mail invito (in traduzione)
     * Il default è #subject-invite
     */
    public $subjectPlaceholder = '#subject-invite';

    /**
     *
     * @var string $subjectCategory
     * Valore della categoria per la label che corrisponde all'oggetto della mail invito (in traduzione)
     * Il default è amosinvitations
     */
    public $subjectCategory = 'amosinvitations';
    
    /**
     * @var bool $enableInviteMessage If is true the invite message is enabled. Default to true.
     */
    public $enableInviteMessage = true;
    
    /**
     * @var bool $enableFiscalCode If is true the fiscal code is enabled. Default to false.
     */
    public $enableFiscalCode = false;
    
    /**
     * @var bool $enableFiscalCode When this param is true the controller in create invite allow to create only one invite for each email.
     */
    public $allowOneInvitePerMail = false;

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "invitations";
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');

        //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, $this));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconInvitations::className(),
            WidgetIconInvitationsAll::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [];
    }

    /**
     * Get default model classes
     */
    protected function getDefaultModels()
    {
        return [
            'GoogleInvitationForm' => __NAMESPACE__ . '\\' . 'models\GoogleInvitationForm',
            'Invitation' => __NAMESPACE__ . '\\' . 'models\Invitation',
            'InvitationUser' => __NAMESPACE__ . '\\' . 'models\InvitationUser',
            'UserToInvite' => __NAMESPACE__ . '\\' . 'models\UserToInvite',
            'InvitationSearch' => __NAMESPACE__ . '\\' . 'models\search\InvitationSearch',
        ];
    }
}
