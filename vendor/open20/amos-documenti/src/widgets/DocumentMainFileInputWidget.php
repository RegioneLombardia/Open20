<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 16/12/2019
 * Time: 15:39
 */

namespace open20\amos\documenti\widgets;


use yii\base\Widget;

class DocumentMainFileInputWidget extends Widget
{
    public $model;
    public $form;
    public $isFolder;

    /**
     * @return string
     */
    public function run()
    {
        $moduleDocumenti = \Yii::$app->getModule('documenti');
        return $this->render('@vendor/open20/amos-documenti/src/views/documenti/_input_document_main_file',[
            'model' => $this->model,
            'form' => $this->form,
            'isFolder' => $this->isFolder,
            'module' => $moduleDocumenti
        ]);
    }

}