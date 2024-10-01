<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\behaviors
 * @category   CategoryName
 */

namespace open20\socialwall\behaviors;

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\components\CustomUploadFile;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\FileModuleTrait;
use open20\amos\attachments\interfaces\VirusScanInterface;
use open20\amos\attachments\models\AttachDatabankFile;
use open20\amos\attachments\models\AttachGalleryImage;
use open20\amos\attachments\models\AttachGenericFile;
use open20\amos\attachments\models\AttachGenericImage;
use open20\amos\attachments\models\File;
use open20\amos\core\views\toolbars\StatsToolbarPanels;
use open20\amos\core\utilities\ClassUtility;

use Imagine\Image\Box;
use Imagine\Image\Point;
use open20\socialwall\models\Socialwall;
use open20\socialwall\models\SocialwallLiveChoices;
use open20\socialwall\models\SocialwallLiveContents;
use open20\socialwall\models\SocialwallPostsInSocialwall;
use open20\socialwall\models\SocialwallPostsStaticSearch;
use open20\socialwall\Module;
use open20\socialwall\SocialwallModuleTrait;
use open20\socialwall\utility\Social;
use yii\db\BaseActiveRecord;
use yii\helpers\VarDumper;
use yii\imagine\Image;

use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\base\InvalidArgumentException;

/**
 * Class FileBehavior
 * @property ActiveRecord $owner
 * @package file\behaviors
 */
class SocialwallBehavior extends Behavior
{
    /**
     *
     */
    use SocialwallModuleTrait;

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteSocialwallRelatedData',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveSocialwallSelection',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveSocialwallSelection',
        ];

        return $events;
    }

    public function saveSocialwallSelection($event) {
        if (\Yii::$app->request->isConsoleRequest || !\Yii::$app->request->isPost) {
            return false;
        }

        // Check if model is enabled in socialwall config
        if(!empty(\Yii::$app->getModule(Module::getModuleName())) &&
            !empty(\Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable) &&
            is_array(\Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable)) {

            if(array_key_exists(get_class($this->owner), \Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable)) {
                $moduleMappedId = \Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable[get_class($this->owner)];
                $socialwallPostData = \Yii::$app->request->post('Socialwall');

                Social::saveSocialwallSelection($moduleMappedId, $this->owner->id, $socialwallPostData);
            }
        }
    }

    public function deleteSocialwallRelatedData($event) {
        $this->removeAllSocialwallData($this->owner->id);
    }

    private function removeAllSocialwallData($socialwallModelId) {
        Social::removeAllSocialwallData($socialwallModelId);
    }

    private function removeLiveData($socialwallModelId) {
        Social::removeLiveData($socialwallModelId);
    }

    private function removePickedPostsData($socialwallModelId) {
        Social::removePickedPostsData($socialwallModelId);
    }

}
