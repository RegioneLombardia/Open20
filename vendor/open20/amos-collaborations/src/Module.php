<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
namespace open20\amos\collaborations;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\module\AmosModule;
use Yii;



/**
 * registry module definition class
 *
 * @property-read null $widgetGraphics
 * @property-read array $widgetIcons
 * @property-read array $defaultModels
 */
class Module extends AmosModule implements CmsModuleInterface
{

    /**
     *
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\collaborations\controllers';

    public $newFileMode = 0666;

    public $name = 'Proposte di collaborazione';

    /**
     * hide block on _form relative to seo module even if it is present
     *
     * @var bool
     */
    public $hideSeoModule = false;

    /**
     * Enable external link icon in Collaboration Proposals summary emails with specified url
     *
     * @var string
     */
    public $emailExternalUrl = null;

    /**
     * Configure array for expression of interest modal in proposal view for Guest.
     * If empty, modal is disabled.
     *
     * @var array
     */
    public $modalExpressionOfInterest = [];

    /**
     * Summary notifications view override
     *
     * @var array
     */
    public $viewPathEmailSummary = [
        'open20\amos\collaborations\models\CollaborationProposals' => '@vendor/open20/amos-collaborations/src/views/email/notify_summary'
    ];

    /**
     * Summary notifications title view override
     *
     * @var string[]
     */
    public $viewPathEmailSummaryTitle = [
        'open20\amos\collaborations\models\CollaborationProposals' => '@vendor/open20/amos-collaborations/src/views/email/notify_summary_title'
    ];

    public $categoriesCustom = null;

    /**
     *
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require (__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    protected function getDefaultModels()
    {
        return [
            'CollaborationProposalsSearch' => __NAMESPACE__ . '\\' . 'models\search\CollaborationProposalsSearch',
            'CollaborationProposals' => __NAMESPACE__ . '\\' . 'models\CollaborationProposals',
            'CollaborationExpressionsOfInterestSearch' => __NAMESPACE__ . '\\' . 'models\search\CollaborationExpressionsOfInterestSearch',
            'CollaborationExpressionsOfInterest' => __NAMESPACE__ . '\\' . 'models\CollaborationExpressionsOfInterest',

        ];
    }

    /**
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'collaborations';
    }

    public function getWidgetGraphics()
    {
        return NULL;
    }

    public function getWidgetIcons()
    {
        return [];
    }

    /**
     * Model class name
     *
     * @return string
     */
    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\CollaborationProposals';
    }

    /**
     * ModelSearch class name
     *
     * @return string
     */
    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\CollaborationProposalsSearch';
    }

}
