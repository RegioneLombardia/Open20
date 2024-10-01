<?php

namespace open20\amos\admin\validators;

class ReCaptchaValidator extends \himiklab\yii2\recaptcha\ReCaptchaValidator
{
   protected function validateValue($value)
    {
        if ($this->isValid === null) {
            if (!$value) {
                $this->isValid = false;
            } else {
                $response = $this->getResponse($value);
                //pr($response);die;
                if (!isset($response['success'], $response['hostname']) ||
                    ($this->checkHostName && $response['hostname'] !== $this->getHostName())
                ) {
                  $this->message = 'Invalid recaptcha verify response.';
                }

                $this->isValid = $response['success'] === true;
            }
        }

        return $this->isValid ? null : [$this->message, []];
    }
}
