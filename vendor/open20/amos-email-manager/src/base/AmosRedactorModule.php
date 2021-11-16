<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\base;

use yii\base\Exception;
use yii\redactor\RedactorModule;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\Url;

class AmosRedactorModule extends RedactorModule
{


    public function getOwnerPath()
    {
        return Yii::$app->user->isGuest ? 'guest' : Yii::$app->user->id;
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getSaveDir()
    {
        $path = Yii::getAlias($this->uploadDir);
        if (!file_exists($path)) {
            throw new InvalidConfigException('Invalid config $uploadDir');
        }
        if (FileHelper::createDirectory($path . DIRECTORY_SEPARATOR . $this->getOwnerPath(), 0777)) {
            return $path . DIRECTORY_SEPARATOR . $this->getOwnerPath();
        }
    }

    /**
     * @param $fileName
     * @return string
     * @throws InvalidConfigException
     */
    public function getFilePath($fileName)
    {
        return $this->getSaveDir() . DIRECTORY_SEPARATOR . $fileName;
    }


    /**
     * @param $fileName
     * @return string
     */
    public function getUrl($fileName)
    {
        return \Yii::$app->getUrlManager()->createAbsoluteUrl(Url::to($this->uploadUrl . '/' . $this->getOwnerPath() . '/' . $fileName));
        //return \Yii::$app->getUrlManager()->createAbsoluteUrl(['file/file/download',  'id' => $this->getOwnerPath() . '/' . $fileName, 'hash'=> '']);
    }
}
