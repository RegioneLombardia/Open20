<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\attachments\components\FileImport;
use open20\amos\community\models\Community;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m170502_151028_migrate_community_images
 */
class m170502_151028_migrate_community_images extends Migration
{
    /**
     * @var string $backendDir The entire path of the 'backend' directory
     */
    private $backendDir;

    /**
     * @var array $recordImagesAttributes The image attributes of the principal record
     */
    private $recordImagesAttributes = [];

    public function init()
    {
        parent::init();

        $this->recordImagesAttributes = [
            'logo_id' => 'communityLogo',
            'cover_image_id' => 'communityCoverImage'
        ];
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        Yii::getLogger()->flushInterval = 1;
        Yii::$app->log->targets = [];
        $ok = $this->migrateImages();
        return $ok;
    }

    /**
     * This method migrate news picture and all news related attachments
     * @return bool
     */
    private function migrateImages()
    {
        $found = $this->searchBackendDir();

        if ($found) {
            $this->backendDir .= 'backend' . DIRECTORY_SEPARATOR . 'web';
            $query = new Query();
            $query->from(Community::tableName())->orderBy(['id' => SORT_ASC]);
            $result = $query->all();
            foreach ($result as $singleRecord) {
                $newRecord = new Community($singleRecord);
                $newRecord->detachBehaviors();
                MigrationCommon::printConsoleMessage('********************************************************************************************************************************************************************');
                $this->migrateSingleRecord($newRecord);
            }
        } else {
            MigrationCommon::printConsoleMessage('Cartella backend non trovata');
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
     * This method migrate record images.
     * @param \open20\amos\core\record\Record $record
     * @return array|bool
     */
    private function migrateSingleRecord($record)
    {
        $ok = true;
        foreach ($this->recordImagesAttributes as $oldRecordImageAttribute => $newRecordImageAttribute) {
            if (!$record->{$oldRecordImageAttribute}) {
                MigrationCommon::printConsoleMessage("Record ID = " . $record->id . "; Attributo = $oldRecordImageAttribute; => Il record non ha immagine.");
                continue;
            }
            $recordImageUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $record->{$oldRecordImageAttribute}])->scalar();
            if (!$recordImageUrl) {
                MigrationCommon::printConsoleMessage("Record ID = " . $record->id . "; Attributo = $oldRecordImageAttribute; => Url immagine non trovato.");
                $ok = false;
                break;
            }
            $filePath = $this->backendDir . $recordImageUrl;
            if (!file_exists($filePath)) {
                MigrationCommon::printConsoleMessage("Record ID = " . $record->id . "; Attributo = $oldRecordImageAttribute; => Immagine '" . $filePath . "' non presente sul file system.");
                $ok = false;
                break;
            }
            $ok = $this->migrateFile($record, $newRecordImageAttribute, $filePath);
            if (!$ok) {
                MigrationCommon::printConsoleMessage("Record ID = " . $record->id . "; Attributo = $oldRecordImageAttribute; => Errore durante la migrazione dell'immagine del record '" . $filePath . "'");
                break;
            } else {
                MigrationCommon::printConsoleMessage("Record ID = " . $record->id . "; Attributo = $oldRecordImageAttribute; => Migrazione immagine del record ok");
            }
        }
        return $ok;
    }

    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param \open20\amos\core\record\Record $record
     * @param string $attribute
     * @param string $filePath
     * @return array
     */
    private function migrateFile($record, $attribute, $filePath)
    {
        $fileImport = new FileImport();
        $ok = $fileImport->importFileForModel($record, $attribute, $filePath);
        return $ok;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo __CLASS__ . " non puo' essere revertata";
        return true;
    }
}
