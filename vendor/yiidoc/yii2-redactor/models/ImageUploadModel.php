<?php

/**
 */

namespace yii\redactor\models;

use Yii;

/**
 * @since 2.0
 */
class ImageUploadModel extends FileUploadModel
{
    public function rules()
    {
        return [
            ['file', 'file', 'extensions' => Yii::$app->controller->module->imageAllowExtensions]
        ];
    }

}