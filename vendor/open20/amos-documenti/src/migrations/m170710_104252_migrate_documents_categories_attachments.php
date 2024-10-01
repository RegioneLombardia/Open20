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
use open20\amos\documenti\models\DocumentiCategorie;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m170710_104252_migrate_documents_categories_attachments
 */
class m170710_104252_migrate_documents_categories_attachments extends Migration
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
        $ok = $this->migrateDocumentCategories();
        return $ok;
    }
    
    private function migrateDocumentCategories()
    {
        $found = $this->searchBackendDir();
        if ($found) {
            $this->backendDir .= 'backend' . DIRECTORY_SEPARATOR . 'web';
            $query = new Query();
            $query->from(DocumentiCategorie::tableName())->orderBy(['id' => SORT_ASC]);
            $allDocumentCategories = $query->all();
            foreach ($allDocumentCategories as $documentCategory) {
                /** @var DocumentiCategorie $newDocumentCategory */
                $newDocumentCategory = AmosDocumenti::instance()->createModel('DocumentiCategorie', $documentCategory);
                $newDocumentCategory->detachBehaviors();
                $this->printConsoleMsg('********************************************************************************************************************************************************************');
                $this->migrateSingleDocumentCategory($newDocumentCategory);
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
     * @param DocumentiCategorie $documentCategory
     * @return array|bool
     */
    private function migrateSingleDocumentCategory($documentCategory)
    {
        if (!$documentCategory->filemanager_mediafile_id) {
            $this->printConsoleMsg('Document Category ID = ' . $documentCategory->id . " => La categoria di documento non ha allegato principale.");
            return false;
        }
        $documentCategoryImageUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $documentCategory->filemanager_mediafile_id])->scalar();
        if (!$documentCategoryImageUrl) {
            $this->printConsoleMsg('Document Category ID = ' . $documentCategory->id . " => Url categoria di documento principale non trovato");
            return false;
        }
        $filePath = $this->backendDir . $documentCategoryImageUrl;
        if (!file_exists($filePath)) {
            $this->printConsoleMsg('Document Category ID = ' . $documentCategory->id . " => Allegato  '" . $filePath . "' non presente sul file system.");
            return false;
        }
        $ok = $this->migrateFile($documentCategory, 'documentCategoryImage', $filePath);
        if (!$ok) {
            $this->printConsoleMsg('Document Category ID = ' . $documentCategory->id . " => Errore durante la migrazione di categoria di documento principale '" . $filePath . "'");
        } else {
            $this->printConsoleMsg('Document Category ID = ' . $documentCategory->id . ' => Migrazione categoria di documento principale ok');
        }
        return $ok;
    }
    
    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param DocumentiCategorie $documentCategory
     * @param string $attribute
     * @param string $filePath
     * @return array
     */
    private function migrateFile($documentCategory, $attribute, $filePath)
    {
        $fileImport = new FileImport();
        $ok = $fileImport->importFileForModel($documentCategory, $attribute, $filePath);
        return $ok;
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
