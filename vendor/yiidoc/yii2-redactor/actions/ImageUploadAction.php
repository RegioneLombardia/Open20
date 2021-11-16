<?php

/**
 */

namespace yii\redactor\actions;

use Yii;
use yii\redactor\models\ImageUploadModel;

/**
 * @since 2.0
 */
class ImageUploadAction extends \yii\base\Action
{
    function run()
    {
        if (isset($_FILES)) {
            $model = new ImageUploadModel();
            if ($model->upload()) {
                return $model->getResponse();
            } else {
                return ['error' => 'Unable to save image file'];
            }
        }
    }

}
