<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\attachments\components\FileImport;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;
use yii\db\Query;

class m170518_150831_migrate_userprofiles_images extends Migration
{
    /**
     * @var string $backendDir The entire path of the 'backend' directory
     */
    private $backendDir;

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (is_null(Yii::$app->getModule('upload'))) {
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', '#migrate_image_not_necessary'));
            return true;
        }
        Yii::getLogger()->flushInterval = 1;
        Yii::$app->log->targets = [];
        $ok = $this->migrateUserProfileImages();
        return $ok;
    }

    /**
     * This method migrate news picture and all news related attachments
     * @return bool
     */
    private function migrateUserProfileImages()
    {
        $found = $this->searchBackendDir();

        if ($found) {
            $this->backendDir .= 'backend' . DIRECTORY_SEPARATOR . 'web';
            $query = new Query();
            $query->from(UserProfile::tableName())->orderBy(['id' => SORT_ASC]);
            $allUsers = $query->all();
            foreach ($allUsers as $user_attributes) {
                $users = new UserProfile($user_attributes);
                $users->detachBehaviors();
                $this->printConsoleMsg('********************************************************************************************************************************************************************');
                $this->migrateUserProfileImage($users);
            }
        } else {
            $this->printConsoleMsg('Cartella backend non trovata');
        }

        return $found;
    }

    /**
     * This method search the 'backend' directory
     * @return bool
     */
    private function searchBackendDir()
    {
        $this->backendDir = __DIR__;
        $lastDirChar = substr($this->backendDir, -1);
        if ($lastDirChar != DIRECTORY_SEPARATOR) {
            $this->backendDir .= DIRECTORY_SEPARATOR;
        }
        $found = false;
        while (!$found) {
            $this->backendDir .= '..' . DIRECTORY_SEPARATOR;
            $dirHandle = opendir($this->backendDir);
            if ($dirHandle) {
                $dirElement = readdir($dirHandle);
                while (!$found && $dirElement) {
                    if (strpos($dirElement, 'backend') !== false) {
                        $found = true;
                    } else {
                        $dirElement = readdir($dirHandle);
                    }
                }
                closedir($dirHandle);
            }
        }
        return $found;
    }

    /**
     * This method print a console message
     * @param $msg
     */
    private function printConsoleMsg($msg)
    {
        print_r($msg);
        print_r("\n");
    }

    /**
     * This method migrate news image
     * @param UserProfile $users
     * @return array|bool
     */
    private function migrateUserProfileImage($users)
    {
        if (!$users->avatar_id) {
            $this->printConsoleMsg('UserProfile ID = ' . $users->id . " => Il profilo non ha immagine.");
            return false;
        }
        $usersImageUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $users->avatar_id])->scalar();
        if (!$usersImageUrl) {
            $this->printConsoleMsg('UserProfile ID = ' . $users->id . " => Url immagine non trovato");
            return false;
        }
        $filePath = $this->backendDir . $usersImageUrl;
        if (!file_exists($filePath)) {
            $this->printConsoleMsg('UserProfile ID = ' . $users->id . " => Immagine '" . $filePath . "' non presente sul file system.");
            return false;
        }
        $ok = $this->migrateFile($users, 'userProfileImage', $filePath);
        if (!$ok) {
            $this->printConsoleMsg('UserProfile ID = ' . $users->id . " => Errore durante la migrazione dell'immagine del profilo '" . $filePath . "'");
        } else {
            $this->printConsoleMsg('UserProfile ID = ' . $users->id . ' => Migrazione immagine del profilo  ok');
        }
        return $ok;
    }

    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param UserProfile $news
     * @param string $attribute
     * @param string $filePath
     * @return array
     */
    private function migrateFile($news, $attribute, $filePath)
    {
        $fileImport = new FileImport();
        $ok = $fileImport->importFileForModel($news, $attribute, $filePath);
        return $ok;
    }


    public function safeDown()
    {
        if (is_null(Yii::$app->getModule('upload'))) {
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', '#migrate_image_not_necessary'));
            return true;
        }
        echo "m170518_150831_migrate_userprofiles_images  non può essere revertata.\n";
        return false;
    }
}
