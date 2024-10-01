<?php

use yii\db\Migration;
use yii\db\Query;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\attachments\components\FileImport;


class m170413_105835_migrate_discussions_images_and_attachments extends Migration
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
        Yii::getLogger()->flushInterval = 1;
        Yii::$app->log->targets = [];
        $ok = $this->migrateDiscussioniTopicImages();
        return $ok;
    }

    /**
     * This method migrate discussions picture and all discussions related attachments
     * @return bool
     */
    private function migrateDiscussioniTopicImages()
    {
        $found = $this->searchBackendDir();

        if ($found) {
            $this->backendDir .= 'backend' . DIRECTORY_SEPARATOR . 'web';
            $query = new Query();
            $query->from(DiscussioniTopic::tableName())->orderBy(['id' => SORT_ASC]);
            $allDiscussioniTopic = $query->all();
            foreach ($allDiscussioniTopic as $discussionsAttributes) {
                $discussions = new DiscussioniTopic($discussionsAttributes);
                $discussions->detachBehaviors();
                $this->printConsoleMsg('********************************************************************************************************************************************************************');
                $this->migrateSingleDiscussioniTopic($discussions);
                $this->migrateSingleDiscussioniTopicAttachments($discussions);
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
     * This method migrate discussions image
     * @param DiscussioniTopic $discussions
     * @return array|bool
     */
    private function migrateSingleDiscussioniTopic($discussions)
    {
        if (!$discussions->image_id) {
            $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . " => La discussione non ha immagine.");
            return false;
        }
        $discussionsImageUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $discussions->image_id])->scalar();
        if (!$discussionsImageUrl) {
            $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . " => Url immagine non trovato");
            return false;
        }
        $filePath = $this->backendDir . $discussionsImageUrl;
        if (!file_exists($filePath)) {
            $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . " => Immagine '" . $filePath . "' non presente sul file system.");
            return false;
        }
        $ok = $this->migrateFile($discussions, 'discussionsTopicImage', $filePath);
        if (!$ok) {
            $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . " => Errore durante la migrazione dell'immagine della discussione '" . $filePath . "'");
        } else {
            $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . ' => Migrazione immagine della discussione ok');
        }
        return $ok;
    }

    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param DiscussioniTopic $discussions
     * @param string $attribute
     * @param string $filePath
     * @return array
     */
    private function migrateFile($discussions, $attribute, $filePath)
    {
        $fileImport = new FileImport();
        $ok = $fileImport->importFileForModel($discussions, $attribute, $filePath);
        return $ok;
    }

    /**
     * This method migrate all discussions attachments
     * @param DiscussioniTopic $discussions
     */
    private function migrateSingleDiscussioniTopicAttachments($discussions)
    {
        $discussionsAttachmentsIds = (new Query())->select(['filemanager_mediafile_id'])->from('discussioni_allegati')->where(['discussioni_topic_id' => $discussions->id])->column();
        if (!count($discussionsAttachmentsIds)) {
            $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . ' => Allegati non presenti.');
        }
        foreach ($discussionsAttachmentsIds as $discussionsAttachmentId) {
            $discussionsAttachmentUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $discussionsAttachmentId])->scalar();
            if (!$discussionsAttachmentUrl) {
                $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . "; Filemanager media file id = " . $discussionsAttachmentId . " => Url allegato non trovato");
                continue;
            }
            $filePath = $this->backendDir . $discussionsAttachmentUrl;
            $ok = $this->migrateFile($discussions, 'attachments', $filePath);
            if (!$ok) {
                $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . "; Filemanager media file id = " . $discussionsAttachmentId . " => Errore durante la migrazione dell'allegato '" . $filePath . "'");
            } else {
                $this->printConsoleMsg('DiscussioniTopic ID = ' . $discussions->id . "; Filemanager media file id = " . $discussionsAttachmentId . ' => Migrazione allegato della discussions ok');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m170413_084535_migrate_discussions_images_and_attachments non può essere revertata";
        return true;
    }
}
