<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\attachments\components\FileImport;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m170518_135046_migrate_documents_and_attachments
 */
class m170518_135046_migrate_documents_and_attachments extends Migration
{
    /**
     * @var string $backendDir The entire path of the 'backend' directory
     */
    private $backendDir;
    private static $documentiAllegatiTableName = 'documenti_allegati';
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        Yii::getLogger()->flushInterval = 1;
        Yii::$app->log->targets = [];
        $ok = $this->migrateDocuments();
        return $ok;
    }
    
    private function migrateDocuments()
    {
        $found = $this->searchBackendDir();
        if ($found) {
            $this->backendDir .= 'backend' . DIRECTORY_SEPARATOR . 'web';
            $query = new Query();
            $query->from(Documenti::tableName())->orderBy(['id' => SORT_ASC]);
            $allDocuments = $query->all();
            $docAttachmentsIsToMigrate = true;
            if ($this->db->schema->getTableSchema(self::$documentiAllegatiTableName, true) === null) {
                $this->printConsoleMsg('Tabella degli allegati dei documenti non presente. Nessun allegato da migrare.');
                $docAttachmentsIsToMigrate = false;
            }
            foreach ($allDocuments as $document) {
                /** @var Documenti $newDocument */
                $newDocument = AmosDocumenti::instance()->createModel('Documenti', $document);
                $newDocument->detachBehaviors();
                $this->printConsoleMsg('********************************************************************************************************************************************************************');
                $this->migrateSingleDocument($newDocument);
                if ($docAttachmentsIsToMigrate) {
                    $this->migrateDocumentsAttachments($newDocument);
                }
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
     * This method migrate document image
     * @param Documenti $document
     * @return array|bool
     */
    private function migrateSingleDocument($document)
    {
        if (!$document->filemanager_mediafile_id) {
            $this->printConsoleMsg('Document ID = ' . $document->id . " => Il documento non ha allegato principale.");
            return false;
        }
        $documentImageUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $document->filemanager_mediafile_id])->scalar();
        if (!$documentImageUrl) {
            $this->printConsoleMsg('Document ID = ' . $document->id . " => Url documento principale non trovato");
            return false;
        }
        $filePath = $this->backendDir . $documentImageUrl;
        if (!file_exists($filePath)) {
            $this->printConsoleMsg('Document ID = ' . $document->id . " => Allegato  '" . $filePath . "' non presente sul file system.");
            return false;
        }
        $ok = $this->migrateFile($document, 'documentMainFile', $filePath);
        if (!$ok) {
            $this->printConsoleMsg('Document ID = ' . $document->id . " => Errore durante la migrazione del documento principale '" . $filePath . "'");
        } else {
            $this->printConsoleMsg('Document  ID = ' . $document->id . ' => Migrazione documento principale ok');
        }
        return $ok;
    }
    
    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param Documenti $document
     * @param string $attribute
     * @param string $filePath
     * @return array
     */
    private function migrateFile($document, $attribute, $filePath)
    {
        $fileImport = new FileImport();
        $ok = $fileImport->importFileForModel($document, $attribute, $filePath);
        return $ok;
    }
    
    /**
     * This method migrate all discussions attachments
     * @param Documenti $document
     */
    private function migrateDocumentsAttachments($document)
    {
        $documentAttachmentsIds = (new Query())->select(['filemanager_mediafile_id'])->from(self::$documentiAllegatiTableName)->where(['documenti_id' => $document->id])->column();
        if (!count($documentAttachmentsIds)) {
            $this->printConsoleMsg('Documenti ID = ' . $document->id . ' => Allegati non presenti.');
        }
        foreach ($documentAttachmentsIds as $documentAttachmentId) {
            $documentAttachmentUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $documentAttachmentId])->scalar();
            if (!$documentAttachmentUrl) {
                $this->printConsoleMsg('Documenti ID = ' . $document->id . "; Filemanager media file id = " . $documentAttachmentId . " => Url allegato non trovato");
                continue;
            }
            $filePath = $this->backendDir . $documentAttachmentUrl;
            $ok = $this->migrateFile($document, 'documentAttachments', $filePath);
            if (!$ok) {
                $this->printConsoleMsg('Documenti ID = ' . $document->id . "; Filemanager media file id = " . $documentAttachmentId . " => Errore durante la migrazione dell'allegato '" . $filePath . "'");
            } else {
                $this->printConsoleMsg('Documenti ID = ' . $document->id . "; Filemanager media file id = " . $documentAttachmentId . ' => Migrazione allegato del documento ok');
            }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m170518_135046_migrate_documents_and_attachments cannot be reverted.\n";
        return true;
    }
}
