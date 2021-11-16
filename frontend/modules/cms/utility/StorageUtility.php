<?php

namespace app\modules\cms\utility;

use open20\amos\attachments\models\File;
use open20\amos\core\utilities\StringUtils;
use luya\admin\models\StorageFile;
use Yii;

class StorageUtility
{

    /**
     *
     * @return type
     */
    private function getModuleAttachments()
    {
        return Yii::$app->getModule('attachments');
    }

    /**
     *
     * @param type $url
     */
    public function getFileInfo($idStorageFile)
    {
        $info   = [];
        $module = $this->getModuleAttachments();
        if (!is_null($module)) {
            $xFile = StorageFile::findOne(['id' => $idStorageFile]);
            $hash = StringUtils::replace($xFile->name_new_compound, '.'.$xFile->extension , '') ;
            if (!empty($hash)) {
                $fileRef = File::findOne(['name' => $hash]);
                if ($fileRef && $fileRef->id) {
                    $info['type'] = $fileRef->type;
                    $info['name'] = StringUtils::replace($fileRef->name,'_'. $xFile->hash_name ,'');
                    $info['size'] = round (($fileRef->size/1024), 2) . "Kb";
                    $info['mime'] = $fileRef->mime;
                    $info['url'] = $fileRef->getWebUrl('original');
                }
            }
        }
        return $info;
    }

}