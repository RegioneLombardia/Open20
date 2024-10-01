<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata;

use open20\amos\admin\models\UserProfile;
use open20\amos\community\models\Community;
use open20\amos\core\record\Record;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\User;
use yii\base\Exception;
use yii\helpers\Json;
use yii\rest\Action;

class ActionEditItem extends Action
{
    public function run()
    {
        //Request params
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();

        //Refference namespace
        $namespace = $bodyParams['namespace'];

        /**
         * Class for this fetch, expected Record
         * @var $class Record
         */
        $class = new $namespace();

        //List of edit fields
        $editFields = $class::getEditFields();

        //Record interested
        if(!empty($bodyParams['id'])) {
            $record = $class::findOne(['id' => $bodyParams['id']]);
        } else {
            $record = new $class();
        }

        if(isset($bodyParams['data'])) {
            $dataToLoad = [$record->formName() => $bodyParams['data']];

            if($record->load($dataToLoad) && $record->validate() && $record->save()) {
                return $record->toArray();
            } else {
                //Get all rerrors
                $errors = $record->getErrors();

                //First Error
                $firstError = reset($errors);

                return [
                    'error' => true,
                    'message' => reset($firstError),
                    'more' => $errors
                ];
            }
        } else {
            //Set Value
            foreach ($editFields as $key=>$field) {
                $slug = $field['slug'];
                $editFields[$key]['value'] = $record->$slug;
            }

            //Return fields array
            return $editFields;
        }
    }
}