<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\attachments\components\FileImport;
use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m170213_105953_migrate_news_images_and_attachments
 */
class m170213_105953_migrate_news_images_and_attachments extends Migration
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
            MigrationCommon::printConsoleMessage(AmosNews::t('amosnews', '#migrate_image_not_necessary'));
            return true;
        }
        Yii::getLogger()->flushInterval = 1;
        Yii::$app->log->targets = [];
        $ok = $this->migrateNewsImages();
        return $ok;
    }

    /**
     * This method migrate news picture and all news related attachments
     * @return bool
     */
    private function migrateNewsImages()
    {
        $found = $this->searchBackendDir();

        if ($found) {
            $this->backendDir .= 'backend' . DIRECTORY_SEPARATOR . 'web';
            $query = new Query();
            $query->from(News::tableName())->orderBy(['id' => SORT_ASC]);
            $allNews = $query->all();
            foreach ($allNews as $newsAttributes) {
                $news = new News($newsAttributes);
                $news->detachBehaviors();
                $this->printConsoleMsg('********************************************************************************************************************************************************************');
                $this->migrateSingleNews($news);
                $this->migrateSingleNewsAttachments($news);
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
     * @param News $news
     * @return array|bool
     */
    private function migrateSingleNews($news)
    {
        if (!$news->immagine) {
            $this->printConsoleMsg('News ID = ' . $news->id . " => La news non ha immagine.");
            return false;
        }
        $newsImageUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $news->immagine])->scalar();
        if (!$newsImageUrl) {
            $this->printConsoleMsg('News ID = ' . $news->id . " => Url immagine non trovato");
            return false;
        }
        $filePath = $this->backendDir . $newsImageUrl;
        if (!file_exists($filePath)) {
            $this->printConsoleMsg('News ID = ' . $news->id . " => Immagine '" . $filePath . "' non presente sul file system.");
            return false;
        }
        $ok = $this->migrateFile($news, 'newsImage', $filePath);
        if (!$ok) {
            $this->printConsoleMsg('News ID = ' . $news->id . " => Errore durante la migrazione dell'immagine della news '" . $filePath . "'");
        } else {
            $this->printConsoleMsg('News ID = ' . $news->id . ' => Migrazione immagine della news ok');
        }
        return $ok;
    }

    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param News $news
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

    /**
     * This method migrate all news attachments
     * @param News $news
     */
    private function migrateSingleNewsAttachments($news)
    {
        $newsAttachmentsIds = (new Query())->select(['filemanager_mediafile_id'])->from('news_allegati')->where(['news_id' => $news->id])->column();
        if (!count($newsAttachmentsIds)) {
            $this->printConsoleMsg('News ID = ' . $news->id . ' => Allegati non presenti.');
        }
        foreach ($newsAttachmentsIds as $newsAttachmentId) {
            $newsAttachmentUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $newsAttachmentId])->scalar();
            if (!$newsAttachmentUrl) {
                $this->printConsoleMsg('News ID = ' . $news->id . "; Filemanager media file id = " . $newsAttachmentId . " => Url allegato non trovato");
                continue;
            }
            $filePath = $this->backendDir . $newsAttachmentUrl;
            $ok = $this->migrateFile($news, 'attachments', $filePath);
            if (!$ok) {
                $this->printConsoleMsg('News ID = ' . $news->id . "; Filemanager media file id = " . $newsAttachmentId . " => Errore durante la migrazione dell'allegato '" . $filePath . "'");
            } else {
                $this->printConsoleMsg('News ID = ' . $news->id . "; Filemanager media file id = " . $newsAttachmentId . ' => Migrazione allegato della news ok');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (is_null(Yii::$app->getModule('upload'))) {
            MigrationCommon::printConsoleMessage(AmosNews::t('amosnews', '#migrate_image_not_necessary'));
            return true;
        }
        echo "m170213_105953_migrate_news_images_and_attachments non può essere revertata";
        return true;
    }
}
