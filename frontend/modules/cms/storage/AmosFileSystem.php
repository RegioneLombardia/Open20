<?php

namespace app\modules\cms\storage;

use Yii;
use yii\base\Exception;
use open20\amos\attachments\models\File;


class AmosFileSystem extends \luya\admin\storage\BaseFileSystemStorage
{
    
    
    /**
     * 
     * @return type
     */
    public function getModuleAttachments()
    {
        return \Yii::$app->getModule('attachments');
    }
    
    /**
     * 
     * @param type $fileName
     * @return type
     * @throws Exception
     */
    public function fileAbsoluteHttpPath($fileName) 
    {
        $url = '';
        
        $module = $this->getModuleAttachments();
        if(!is_null($module))
        {
            $fileName = pathinfo($fileName, PATHINFO_FILENAME);
            $model = new \open20\amos\attachments\models\EmptyContentModel();
            $file = File::findOne([
                'name' => $fileName,
                'itemId' => $model->id,
                'attribute' => 'file',
                'model' => $model->className()
            ]);
            if(!is_null($file))
            {
                $url = $file->getWebUrl('original',false, true);
            }
        }
        else
        {
            throw new Exception('no attachment module is present.');
        }
        return $url;
    }

    /**
     * 
     * @param type $fileName
     * @return type
     * @throws Exception
     */
    public function fileHttpPath($fileName) 
    {
        $url = '';
        
        $module = $this->getModuleAttachments();
        if(!is_null($module))
        {
            $fileName = pathinfo($fileName, PATHINFO_FILENAME);
            $model = new \open20\amos\attachments\models\EmptyContentModel();
            $file = File::findOne([
                'name' => $fileName,
                'itemId' => $model->id,
                'attribute' => 'file',
                'model' => $model->className()
            ]);
            if(!is_null($file))
            {
                $url = $file->getWebUrl('original',false, true);
            }
        }
        else
        {
            throw new Exception('no attachment module is present.');
        }
        return $url;
        
    }

    /**
     * 
     * @param type $fileName
     * @return type
     */
    public function fileServerPath($fileName) 
    {
        $path = '';
        
        if($this->fileSystemExists($fileName))
        {   
            $model = new \open20\amos\attachments\models\EmptyContentModel();
            $fileFind = pathinfo($fileName, PATHINFO_FILENAME);
            $file = File::findOne([
                'name' => $fileFind,
                'itemId' => $model->id,
                'attribute' => 'file',
                'model' => $model->className()
            ]);
            if(!is_null($file))
            {
                $path = $file->getPath();
            }
        }
        
        return $path;
    }

    /**
     * 
     * @param type $fileName
     */
    public function fileSystemContent($fileName) 
    {
        
        if($this->fileSystemExists($fileName))
        {   
            $model = new \open20\amos\attachments\models\EmptyContentModel();
            $fileFind = pathinfo($fileName, PATHINFO_FILENAME);
            $file = File::findOne([
                'name' => $fileFind,
                'itemId' => $model->id,
                'attribute' => 'file',
                'model' => $model->className()
            ]);
            if(!is_null($file))
            {
                return file_get_contents($file->getPath());
            }
        }
        return null;
    }

    /**
     * 
     * @param type $fileName
     * @throws Exception
     */
    public function fileSystemDeleteFile($fileName) 
    {
        $module = $this->getModuleAttachments();
        
        if(!is_null($module))
        {
            $fileName = pathinfo($fileName, PATHINFO_FILENAME);
            $model = new \open20\amos\attachments\models\EmptyContentModel();
            $file = File::findOne([
                'name' => $fileName,
                'itemId' => $model->id,
                'attribute' => 'file',
                'model' => $model->className()
            ]);
            if(!is_null($file))
            {
                $module->detachFile($file->id);
            }
        }
        else
        {
            throw new Exception('no attachment module is present.');
        }
        return true;
    }

    /**
     * 
     * @param type $fileName
     * @return type
     */
    public function fileSystemExists($fileName) 
    {
        $model = new \open20\amos\attachments\models\EmptyContentModel();
        $fileName = pathinfo($fileName, PATHINFO_FILENAME);
        $exists = File::findOne([
            'name' => $fileName,
            'itemId' => $model->id,
            'attribute' => 'file',
            'model' => $model->className()
        ]);
        
        return !is_null($exists);
    }

    /**
     * 
     * @param type $fileName
     * @param type $newSource
     * @throws Exception
     */
    public function fileSystemReplaceFile($fileName, $newSource) 
    {
        $ret = false;
        $module = $this->getModuleAttachments();
        if(!is_null($module))
        {
            $filefind = pathinfo($fileName, PATHINFO_FILENAME);
            $model = new \open20\amos\attachments\models\EmptyContentModel();
            $file = File::findOne([
                'name' => $filefind,
                'itemId' => $model->id,
                'attribute' => 'file',
                'model' => $model->className()
            ]);
            if(!is_null($file))
            {
                $module->detachFile($file->id);
            }
            $filetowrite = $module->getUserDirPath() . $fileName;
            if(is_uploaded_file ($source))
            {
                if(!move_uploaded_file($newSource, $filetowrite))
                {
                    throw new Exception("error while moving uploaded file from $source to $savePath");
                }
            }
            else
            {
                if(!copy($newSource, $filetowrite))
                {
                    throw new $source("error while copy file from $source to $savePath.");
                }
            }
            $file = $module->attachFile($filetowrite,$model);
            if(!is_null($file))
            {
                $ret = true;
            }
        }
        else
        {
            throw new Exception('no attachment module is present.');
        }
        return $ret;
    }

    /**
     * 
     * @param type $source
     * @param type $fileName
     * @throws Exception
     */
    public function fileSystemSaveFile($source, $fileName) 
    {
        
        $ret = false;
        $module = $this->getModuleAttachments();
        if(!is_null($module))
        {
            $filetowrite = $module->getUserDirPath() . $fileName;
            if(is_uploaded_file ($source))
            {
                if(!move_uploaded_file($source, $filetowrite))
                {
                    throw new Exception("error while moving uploaded file from $source to $savePath");
                }
            }
            else
            {
                if(!copy($source, $filetowrite))
                {
                    throw new $source("error while copy file from $source to $savePath.");
                }
            }
            $model = new \open20\amos\attachments\models\EmptyContentModel();
            $file = $module->attachFile($filetowrite,$model);
            if(!is_null($file))
            {
                $ret = true;
            }
        }
        else
        {
            throw new Exception('no attachment module is present.');
        }
        return $ret;
    }

}
