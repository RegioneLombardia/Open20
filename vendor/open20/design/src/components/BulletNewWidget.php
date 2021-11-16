<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 18/02/2021
 * Time: 15:28
 */

namespace open20\design\components;

use open20\amos\notificationmanager\record\NotifyRecord;
use yii\base\Widget;
use yii\bootstrap\Html;


class BulletNewWidget extends Widget
{
    public $model;
    public $class_div_container = 'badge-new-pubblication d-flex align-items-center';
    public $class_span = 'badge badge-pill badge-danger text-uppercase';

    public $basePath = '@vendor/open20/design/src/components/bootstrapitalia/views';

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->model instanceof NotifyRecord) {
            if ($this->model->isNews()) {
                return $this->render($this->basePath.'/bi-badge-new-pubblication',[
                    'class_div_container' => $this->class_span,
                    'class_span' => $this->class_span
                ]);
            }
        }
        return '';
    }

}
