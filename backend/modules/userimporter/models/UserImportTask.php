<?php

namespace backend\modules\userimporter\models;

use open20\amos\attachments\behaviors\FileBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_import_task".
 */
class UserImportTask extends \backend\modules\userimporter\models\base\UserImportTask
{
    private $file_input;
    private $file_success_input;
    private $file_errors_input;

    public function getFile_input()
    {
        if (empty($this->file_input)) {
            $this->file_input = $this->hasOneFile('file_input')->one();
        }

        return $this->file_input;
    }

    public function getFile_success_input()
    {
        if (empty($this->file_success_input)) {
            $this->file_success_input = $this->hasOneFile('file_success_input')->one();
        }

        return $this->file_success_input;
    }

    public function getFile_errors_input()
    {
        if (empty($this->file_errors_input)) {
            $this->file_errors_input = $this->hasOneFile('file_errors_input')->one();
        }

        return $this->file_errors_input;
    }

    public function setFile_input($file_input)
    {
        $this->file_input = $file_input;
    }

    public function setFile_success_input($file_success_input)
    {
        $this->file_success_input = $file_success_input;
    }

    public function setFile_errors_input($file_errors_input)
    {
        $this->file_errors_input = $file_errors_input;
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
                [
                    'fileBehavior' => [
                        'class' => FileBehavior::className()
                    ],
        ]);
    }

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
        return ArrayHelper::merge(parent::rules(),
                [
                    [['file_input'],'required'],
                    [['file_input'],
                        'file',
                        'skipOnEmpty' => true,
                        'extensions' => (!empty($this->module)) ? $this->module->whiteListFilesExtensions
                            : '',
                        'checkExtensionByMimeType' => false,
                        'maxFiles' => 1,
                    ],
                    [['file_success_input'],
                        'file',
                        'skipOnEmpty' => true,
                        'extensions' => (!empty($this->module)) ? $this->module->whiteListFilesExtensions
                            : '',
                        'checkExtensionByMimeType' => false,
                        'maxFiles' => 1,
                    ],
                    [['file_errors_input'],
                        'file',
                        'skipOnEmpty' => true,
                        'extensions' => (!empty($this->module)) ? $this->module->whiteListFilesExtensions
                            : '',
                        'checkExtensionByMimeType' => false,
                        'maxFiles' => 1,
                    ],
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(), [
        ]);
    }
}