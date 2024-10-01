<?php

namespace open20\socialwall\widgets;

class SocialwallLiveSelectorWidget extends \yii\base\Widget
{
    public $form;
    public $model;
    public $inputId;

    public function run()
    {
        return $this->render('socialwall-live-selector', [
            'form' => $this->form,
            'model' => $this->model,
            'inputId' => $this->inputId,
        ]);
    }
}