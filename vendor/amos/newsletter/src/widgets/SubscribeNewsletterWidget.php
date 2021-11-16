<?php


namespace amos\newsletter\widgets;


use amos\newsletter\models\FormSubscribeNewsletter;
use amos\newsletter\models\Newsletter;
use amos\newsletter\utility\NewsletterUtility;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Url;

class SubscribeNewsletterWidget extends Widget
{
    /**
     * @var integer
     */

    /**
     * id_newsletter win over the other configuration (group_id, dynamic fields)
     * @var $id_newsletter
     */
    public $id_newsletter;

// id group HelpSoil: 20
// id group Nitrati: 7
    public $id_service_mail_group = [];

    public $id_newsletter_group = [];

    public $service_mail_dynamic_fields = [ 1 => 'Nome', 2 => 'Cognome'];

    public $enableName = false;
    public $hideNewsletterName = false;
    public $pathPrivacy = '@backend/views/site/privacy';
    public $subscribeBtnAdditionalClasses = '';

    /** @var string url of redirect after the action of subscribtion */
    public $redirect_url = '@vendor/amos/newsletter/src/widgets/views/thankyou_newsletter';
    public $pathFormNewsletter = '@vendor/amos/newsletter/src/widgets/views/subscribe_newsletter';

    public $confirmEmail = false;

    private $newsletter;
    private $mailService;

    /**
     *
     */
    public function init()
    {
        if(empty($this->id_newsletter_group) && empty($this->id_newsletter)){
            throw new InvalidConfigException('The parameter $id_newsletter_group or $id_newsletter is required');
        }
        $this->mailService = NewsletterUtility::getCurrentMailService();

        if(empty($this->mailService)){
            throw new InvalidConfigException('Mail service  is null');
        }

        $newsletter = Newsletter::findOne($this->id_newsletter);
        $dynamicFields = $this->mailService->getDynamicFields();
        $fields = [];
        if(!empty($dynamicFields)){
            $fields = $dynamicFields->Items;
        }

        if(!empty($newsletter)){
            $this->newsletter = $newsletter;
            $this->id_service_mail_group = $newsletter->service_email_group_id;
            $fieldsMm = $newsletter->newsletterServiceMailFieldsMm;
            if(!empty($fields)){
                foreach ($fieldsMm as $field){
                    foreach ($fields as $dynField){
                        if($dynField->Id == $field->service_mail_field_id){
                            $this->service_mail_dynamic_fields [$dynField->Id]= $dynField->Description;
                        }
                    }
                }
            }
        }
        parent::init();
    }

    public function run()
    {
        return $this->renderWidget();
    }

    public function renderWidget(){
		$module = \Yii::$app->getModule('newsletter');
		if(!empty($module) && !empty($module->customModel)){
			$classModel = $module->customModel;
			$model = new $classModel;
		} else {
            $model = new FormSubscribeNewsletter();
            $model->redirect_url = $this->redirect_url;
		}
        return $this->render($this->pathFormNewsletter, ['model' =>$model, 'widget' => $this, 'newsletter' => $this->newsletter]);
    }

}