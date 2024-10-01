<?php
/**
 */

namespace open20\amos\core\forms\editors\friendlyRecaptcha\validators;

use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Client as HttpClient;
use yii\validators\Validator;

/**
 * ReCaptcha widget validator base class.
 *
 * @package himiklab\yii2\recaptcha
 */
 class FriendlyRecaptchaValidator extends Validator
{

    /** @var boolean Whether to skip this validator if the input is empty. */
    public $skipOnEmpty = false;

    /** @var string The shared key between your site and ReCAPTCHA. */
    public $secret;
    public $siteKey;
    public $message;


    /** @var boolean */
    protected $isValid;




     public function init()
     {
         parent::init();
         if(empty($this->secret)) {
             if(!empty(\Yii::$app->params['friendly-recaptcha']['secret'])) {
                 $this->secret = \Yii::$app->params['friendly-recaptcha']['secret'];
             }else {
                 throw new InvalidConfigException('missing secret key');
             }
         }
         if(empty($this->siteKey)) {
             $this->siteKey = \Yii::$app->params['friendly-recaptcha']['siteKey'];
         }
         if ($this->message === null) {
             $this->message = Yii::t('yii', 'The verification code is incorrect.');
         }
     }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param \yii\web\View $view
     * @return string
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $currentLang = \Yii::$app->language;
        if($currentLang == 'it-IT' || $currentLang == 'it' ){
            $message = "Verifica Anti-Robot' non eseguita, clicca su 'Clicca per iniziare'";
        }elseif($currentLang == 'en-GB' || $currentLang == 'en'){
            $message= "'Anti-Robot verification' not executed, click on 'click to start verification'";
        }

        return <<<JS
if (!value) {
     messages.push("{$message}");
}
JS;
    }


     function validateAttribute($model, $attribute)
     {
         $currentLang = \Yii::$app->language;
         if($currentLang == 'it-IT' || $currentLang == 'it' ){
             $message = "Verifica Anti-Robot' non eseguita, clicca su 'Clicca per iniziare'";
         }elseif($currentLang == 'en-GB' || $currentLang == 'en'){
             $message= "'Anti-Robot verification' not executed, click on 'click to start verification'";
         }
         $value = $model->$attribute;
         $response = $this->getResponse($value);
         if (isset($response['success'])) {
             if(empty($response['success'])) {
                 $this->addError($model, $attribute, $message);
             }
         }
     }



    /**
     * @param string $value
     * @return array
     * @throws Exception
     * @throws \yii\base\InvalidParamException
     */
    protected function getResponse($value)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://api.friendlycaptcha.com/api/v1/siteverify')
            ->setData(['solution' => $value, 'secret' => $this->secret, 'siteKey' => $this->siteKey])
            ->send();
        if (!$response->isOk) {
            throw new Exception('Unable connection to the captcha server. Status code ' . $response->statusCode);
        }

        return $response->data;
    }

}

