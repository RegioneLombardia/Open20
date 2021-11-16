<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

namespace open20\amos\upload\models;

use pendalf89\filemanager\models\Mediafile;
use Yii;
use yii\db\Exception;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class FilemanagerMediafile extends Mediafile {

    /**
     * Save just uploaded file
     * @param array $routes routes from module settings
     * @param bool $rename
     * @return bool
     */


    public function saveUploadedFile(array $routes, $rename = false)
    {
        
         $year = date('Y', time());
        $month = date('m', time());
        $structure = "$routes[baseUrl]/$routes[uploadPath]/$year/$month";
        $basePath = Yii::getAlias($routes['basePath']);
        $absolutePath = "$basePath/$structure";

        // create actual directory structure "yyyy/mm"
        if (!file_exists($absolutePath)) {
            mkdir($absolutePath, 0777, true);
        }

        // get file instance
        $this->file = UploadedFile::getInstance($this, 'file');
        //if a file with the same name already exist append a number
        $counter = 0;
        do{
            if($counter==0)
                $filename = Inflector::slug($this->file->baseName).'.'. $this->file->extension;
            else{
                //if we don't want to rename we finish the call here
                if($rename == false)
                    return false;
                $filename = Inflector::slug($this->file->baseName). $counter.'.'. $this->file->extension;
            }
            $url = "$structure/$filename";
            $counter++;
        } while(self::findByUrl($url)); // checks for existing url in db
        
        $this->evaluateBlackList(NULL, NULL);
        
        // save original uploaded file
        $this->file->saveAs("$absolutePath/$filename");
        $this->filename = $filename;
        $this->type = $this->file->type;
        $this->size = $this->file->size;
        $this->url = $url;

        $return = $this->save();


        if ($return && $this->isImage()) {
            $this->createThumbs($routes, \Yii::$app->getModule('upload')->thumbs);
        }

        return $return;
    }

    public function saveInstanceFiles(array $routes, $rename = false, $uploadedFile)
    {
        $year = date('Y', time());
        $month = date('m', time());
        $structure = "$routes[baseUrl]/$routes[uploadPath]/$year/$month";
        $basePath = Yii::getAlias($routes['basePath']);
        $absolutePath = "$basePath/$structure";

        // create actual directory structure "yyyy/mm"
        if (!file_exists($absolutePath)) {
            mkdir($absolutePath, 0777, true);
        }

        // get file instance
        $this->file = $uploadedFile;
        //if a file with the same name already exist append a number
        $counter = 0;
        do{
            if($counter==0)
                $filename = Inflector::slug($this->file->baseName).'.'. $this->file->extension;
            else{
                //if we don't want to rename we finish the call here
                if($rename == false)
                    return false;
                $filename = Inflector::slug($this->file->baseName). $counter.'.'. $this->file->extension;
            }
            $url = "$structure/$filename";
            $counter++;
        } while(self::findByUrl($url)); // checks for existing url in db

        // save original uploaded file
        //$this->file->saveAs("$absolutePath/$filename");

        $ret = copy($this->file->tempName, "$absolutePath/$filename");
        if ($ret){
            unlink($this->file->tempName);
        }

        $this->filename = $filename;
        $this->type = $this->file->type;
        $this->size = $this->file->size;
        $this->url = $url;

        return $this->save();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            ['file',  'evaluateBlackList'],
        ]);
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function evaluateBlackList($attribute, $params)
    {

        $upload_white_list_file_exts = isset(Yii::$app->params['upload_white_list_file_exts']) ? Yii::$app->params['upload_white_list_file_exts']: [];
        $upload_black_list_file_mimes  = isset(Yii::$app->params['upload_black_list_file_mimes']) ? Yii::$app->params['upload_black_list_file_mimes'] : [];

        $file_ext = explode('.',$this->filename);
        if(!in_array(end($file_ext),$upload_white_list_file_exts)
            || in_array($this->file->type,$upload_black_list_file_mimes))
        {
            throw  new Exception('Uploading potentially damaging files');
        }

    }
}
