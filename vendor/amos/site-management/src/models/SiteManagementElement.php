<?php

namespace amos\sitemanagement\models;

use open20\amos\attachments\models\File;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "site_management_element".
 */
class SiteManagementElement extends \amos\sitemanagement\models\base\SiteManagementElement
{
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'template_id',
                'label' => $labels['template_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'elem_order',
                'label' => $labels['elem_order'],
                'type' => 'string'
            ],
        ];
    }

    /**
     * @param $dynamicForm DynamicModel
     */
    public function saveFieldsFormDynamicModel($dynamicModel, $attributesTypes){
        foreach ($dynamicModel->attributes() as $attribute){
            $modelField = SiteManagementFieldsType::findOne(['name' => $attribute]);
            if($modelField) {
                $val = SiteManagementContainerElemFieldsVal::find()->andWhere(['field_id' => $modelField->id, 'container_elem_id' => $this->id])->one();
                if($attributesTypes[$attribute] != 'file'){
                    if ($val) {
                        $val->value = $dynamicModel->$attribute;
                    } else {
                        $val = new SiteManagementContainerElemFieldsVal();
                        $val->container_elem_id = $this->id;
                        $val->field_id = $modelField->id;
                        $val->value = $dynamicModel->$attribute;
                    }
                    $val->save();
                }
//                if($attributesTypes[$attribute] == 'file'){
//                    $this->saveFileFields($attribute, $modelField);
//                }
            }
        }
    }

    public function saveFileFields($attribute, $modelField){
        $ownerName = 'DynamicModel';
        $moduleAttachments = \Yii::$app->getModule('attachments');
        $fieldValModel = SiteManagementContainerElemFieldsVal::findOne(['field_id' =>$modelField, 'container_elem_id' => $this->id]);
        /**
         * @var UploadedFile[] $files
         */
        $files = UploadedFile::getInstancesByName($attribute) ?: UploadedFile::getInstancesByName("{$ownerName}[{$attribute}]");



        if (!empty($files)) {
            if($fieldValModel) {
                /**
                 * Find for attachment and drop it
                 * @var File $attachment
                 */
                $attachment = $fieldValModel->file;

                //Drop to make space for new image
                $attachment instanceof File && $attachment->delete();

                //Crop data
                $cropData = \Yii::$app->request->post("{$attribute}_data");

                foreach ($files as $file) {
                    if ($cropData) {
                        $this->cropImage($file, $cropData);
                    }

                    if(!empty($moduleAttachments)) {
                        if (!$file->saveAs($moduleAttachments->getUserDirPath($attribute) . $file->name)) {
                            continue;
                        }
                    }
                }
            }
        }

//        if ($fieldValModel->isNewRecord) {
//            return true;
//        }

        $userTempDir = $moduleAttachments->getUserDirPath($attribute);

        //If the firectory doen't exists go out
        if(!is_dir($userTempDir)) {
            return false;
        }

        foreach (FileHelper::findFiles($userTempDir) as $file) {
            if (!$moduleAttachments->attachFile($file, $fieldValModel, $attribute)) {
                $fieldValModel->addError($attribute, 'File upload failed.');
                return true;
            }
        }
    }


    /**
     * @return DynamicModel
     */
    public function loadFieldsToDynamicModel($dynamicModel){
        $fields = $this->siteManagementContainerElemeFieldsVals;
        foreach ($fields as $field){
            $type = $field->siteManagementFieldsType;
            if($type){
                $attribute = $type->name;
                $dynamicModel->$attribute = $field->value;
            }
        }
        return $dynamicModel;
    }


    /**
     * @inheritdoc
     */
    public function getDescription($truncate = false)
    {
        $ret = $this->description;

        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }

    public function isVisibleToLoggedUser(){
        $isVisible = false;
        $user_id = \Yii::$app->user->id;
        if($this->site)
        return $isVisible;
    }


}
