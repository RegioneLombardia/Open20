<?php
namespace amos\podcast;


use amos\podcast\models\Podcast;
use amos\podcast\models\search\PodcastSearch;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\interfaces\BreadcrumbInterface;


class Module extends \open20\amos\core\module\AmosModule implements SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface
{

    public $canCreateOnlyCommunityManager = false;
    public $basicUserCannotCreate = false;

    public $name = 'podcast';

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'podcast';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'PageContent' => __NAMESPACE__ . '\\' . 'models\PageContent',
        ];
    }

    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKey()
    {
        return 'beginCreateNewUrl_' . self::getModuleName();
    }

    /**
     * This method return the session key that must be used to add in session
     * the url date and time creation from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKeyDateTime()
    {
        return 'beginCreateNewUrlDateTime_' . self::getModuleName();
    }


    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName() {
        return PodcastSearch::className();
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName() {
        return Podcast::className();
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName() {
        return 'feed';
    }


    /**
     * @return array
     */
    public function getIndexActions(){
        return [
            'podcast/index',
            'podcast/own',
            'podcast/admin',
            'podcast/to-validate',
            'podcast/to-validate-episodes',
            'podcast-episode/index',
            'podcast-category/index',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames(){
        $names =  [
            'podcast' => self::t('amospodcast', "Podcast"),
            'podcast-episode'  => self::t('amospodcast', "Episodi podcast"),
            'podcast-category'  => self::t('amospodcast', "Categorie podcast"),
        ];

        return $names;
    }

}