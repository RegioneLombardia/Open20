<?php

namespace app\modules\cmsapi\frontend\utility\cmspageblock;

use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\utility\CmsBlocksBuilder;
use open20\amos\events\utility\EventsUtility;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class CmsLandingFormPageBlock extends CmsPageBlock
{
    private $name_surname_email = [];
    private $extra_fields       = [];

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->initialize();
    }

    protected function initialize()
    {
        $this->name_surname_email['name']    = new CmsLandingFormField(['required' => 1,
            'type' => 'string',
            'field' => 'name',
            'label' => $this->buildHtmlLabel("Nome")]);
        $this->name_surname_email['surname'] = new CmsLandingFormField(['required' => 1,
            'type' => 'string',
            'field' => 'surname',
            'label' => $this->buildHtmlLabel("Cognome")]);
        $this->name_surname_email['email']   = new CmsLandingFormField(['required' => 1,
            'type' => 'email',
            'field' => 'email',
            'label' => $this->buildHtmlLabel("Email")]);

        $this->extra_fields ['sex'] = new CmsLandingFormField(['required' => 0, 'type' => 'select',
            'field' => 'sex',
            'label' => $this->buildHtmlLabel("Sesso"),
            'subvalue' => [
                ["value" => "N", "label" => "Non Dichiarato"],
                ["value" => "M", "label" => "Maschio"],
                ["value" => "F", "label" => "Femmina"],
        ]]);

        $this->extra_fields ['age']         = new CmsLandingFormField(['required' => 0,
            'type' => 'select',
            'field' => 'age',
            'label' => $this->buildHtmlLabel("Et&agrave;"),
            'subvalue' => [
                ["value" => "1", "label" => "18-25"],
                ["value" => "2", "label" => "26-35"],
                ["value" => "3", "label" => "36-45"],
                ["value" => "4", "label" => "46-55"],
                ["value" => "5", "label" => "56-65"],
                ["value" => "6", "label" => ">65"],
        ]]);
        $this->extra_fields ['county']      = new CmsLandingFormField(['required' => 0,
            'type' => 'selectdb',
            'field' => 'country',
            'subvalue' => [
                ["value" => "app\controllers\FrontendUtility::getIstatProvince", "label" => ""],
            ],
            'label' => $this->buildHtmlLabel("Provincia")]);
        $this->extra_fields ['city']        = new CmsLandingFormField(['required' => 0,
            'type' => 'selectrel',
            'field' => 'city',
            'subvalue' => [
                ["value" => "country-id", "label" => "/comuni/default/comuni-by-provincia"],
            ],
            'label' => $this->buildHtmlLabel("Citt&agrave;")]);
        $this->extra_fields ['telefon']     = new CmsLandingFormField(['required' => 0,
            'type' => 'string',
            'field' => 'telefon',
            'label' => $this->buildHtmlLabel("Telefono")]);
        $this->extra_fields ['fiscal_code'] = new CmsLandingFormField(['required' => 0,
            'type' => 'string',
            'field' => 'fiscal_code',
            'label' => $this->buildHtmlLabel("Codice Fiscale")]);
        $this->extra_fields ['company']     = new CmsLandingFormField(['required' => 0,
            'type' => 'string',
            'field' => 'company',
            'label' => $this->buildHtmlLabel("Azienda")]);

        $this->extra_fields ['tags']   = new CmsLandingFormField(['required' => 0,
            'type' => 'checkboxList',
            'field' => 'tags',
            'subvalue' => $this->getTags(),
            'label' => $this->buildHtmlLabel("Interessi")]);
        $this->extra_fields['privacy'] = new CmsLandingFormField(['required' => 1,
            'type' => 'radioList',
            'field' => 'privacy',
            'subvalue' => [
                ["value" => "0", "label" => "Non acconsento"],
                ["value" => "1", "label" => "Acconsento"],
            ],
            'label' => $this->buildHtmlLabel("<!DOCTYPE html><html><head></head><body><div style=\"font-size: medium;\"><span style=\"color: #ffffff;\"><a style=\"color: #ffffff;\" href=\"https:\\\\backend.openinnovationlombardia.it\\site\\privacy\" target=\"_blank\" rel=\"noopener\">Prendi visione del trattamento dei dati personali e dell'informativa sull'uso dei cookie</a></span></div><div style=\"font-size: medium;\"><span style=\"font-size: medium;\">Presa visione delle informazioni fornite ai sensi dell&rsquo;articolo 13 del D.Lgs. 196\\2003 aggiornato al D.Lgs. 101\\2018, esprimo il consenso al trattamento dei dati personali per i fini indicati nella sopraindicata informativa.</span></div></body></html>")]);
    }

    /**
     *
     * @param PostCmsCreatePage $postCmsPage
     */
    public function buildValues(PostCmsCreatePage $postCmsPage)
    {
        /* var $formLanding PostCmsFormLanding */
        $formLanding     = $postCmsPage->form_landing;
        $values          = Json::decode($this->json_config_values);
        $valuesCfg       = Json::decode($this->json_config_cfg_values);
        $values["table"] = "event_form_".$postCmsPage->event_data->event_id;
        
        $values['items'] = [];
        

        if ($formLanding->user_name_reg) {
            $this->buildFormNameSurname($values['items']);
            $this->buildFormNameSurnameCfg($valuesCfg);
        } else {
            $this->removeFormNameSurname($values['items']);
            $this->removeFormNameSurnameCfg($valuesCfg);
        }

        $valuesCfg['register_with_social'] = $formLanding->social_reg ? 1 : 0;

        if($formLanding->social_reg || $formLanding->user_name_reg){
            $this->is_hidden = 0;
        }
        else {
            $this->is_hidden = 1;
        }

        if ($formLanding->ask_sex) {
            $this->buildElement($values['items'], $this->extra_fields['sex']);
        } else {
            $this->removeElement($values['items'], $this->extra_fields['sex']);
        }
        if ($formLanding->ask_age) {
            $this->buildElement($values['items'], $this->extra_fields['age']);
        } else {
            $this->removeElement($values['items'], $this->extra_fields['age']);
        }
        if ($formLanding->ask_county) {
            $this->buildElement($values['items'], $this->extra_fields['county']);
        } else {
            $this->removeElement($values['items'], $this->extra_fields['county']);
        }
        if ($formLanding->ask_city) {
            $this->buildElement($values['items'], $this->extra_fields['city']);
        } else {
            $this->removeElement($values['items'], $this->extra_fields['city']);
        }
        if ($formLanding->ask_telefon) {
            $this->buildElement($values['items'], $this->extra_fields['telefon']);
        } else {
            $this->removeElement($values['items'],
                $this->extra_fields['telefon']);
        }
        if ($formLanding->ask_fiscal_code) {
            $this->buildElement($values['items'],
                $this->extra_fields['fiscal_code']);
        } else {
            $this->removeElement($values['items'],
                $this->extra_fields['fiscal_code']);
        }
        if ($formLanding->ask_company) {
            $this->buildElement($values['items'], $this->extra_fields['company']);
        } else {
            $this->removeElement($values['items'],
                $this->extra_fields['company']);
        }
        if ($formLanding->ask_tags) {
            $this->buildElement($values['items'], $this->extra_fields['tags']);
        } else {
            $this->removeElement($values['items'], $this->extra_fields['tags']);
        }

        $this->buildElement($values['items'], $this->extra_fields['privacy']);
        $valuesCfg['privacy_form_field'] = $this->extra_fields['privacy']->field;

        $valuesCfg['send_mail']            = 1;
        $valuesCfg['register_on_platform'] = 1;
        $valuesCfg['from_email'] = Yii::$app->params['supportEmail'];
        
        if(!empty($postCmsPage->form_landing->confirm_mail_subject)){
            $valuesCfg['email_subject'] = $postCmsPage->form_landing->confirm_mail_subject;
        }

        if(!empty($postCmsPage->form_landing->confirm_mail_text)){
            $valuesCfg['email_text'] = $postCmsPage->form_landing->confirm_mail_text;
        }

        if(!empty($postCmsPage->form_landing->waiting_mail_text)){
            $valuesCfg['email_waiting_list_text'] = $postCmsPage->form_landing->waiting_mail_text;
        }

        if (!empty($postCmsPage->form_landing->seats_available)) {
            $valuesCfg['seats_available'] = $postCmsPage->form_landing->seats_available;
        }
        if (!empty($postCmsPage->form_landing->community_id)) {
            $valuesCfg['community_id'] = $postCmsPage->form_landing->community_id;
        }

        $this->emailsElements($valuesCfg, $postCmsPage);
        $this->relatedPages($valuesCfg, $postCmsPage);

        $this->json_config_values     = Json::encode($values);
        $this->json_config_cfg_values = Json::encode($valuesCfg);
    }

    /**
     *
     * @param type $nav_item_page_id
     * @return type
     */
    public static function findBlocks($nav_item_page_id)
    {
        $id_block = static::findBlock(CmsBlocksBuilder::LANDINGFORM);
        $blocks   = static::find()->
            andWhere(['nav_item_page_id' => $nav_item_page_id])->
            andWhere(['block_id' => $id_block->id])
            ->all();
        return $blocks;
    }

    /**
     *
     * @param array $items
     */
    protected function buildFormNameSurname(array &$items)
    {
        foreach ($this->name_surname_email as $key) {
            $this->buildElement($items, $key);
        }
    }

    /**
     *
     * @param array $items
     */
    protected function removeFormNameSurname(array &$items)
    {
        foreach ($this->name_surname_email as $key) {
            $this->removeElement($items, $key);
        }
    }

    /**
     *
     * @param array $cfg
     */
    protected function buildFormNameSurnameCfg(array &$cfg)
    {
        $cfg['user_name_form_field']    = $this->name_surname_email['name']->field;
        $cfg['user_surname_form_field'] = $this->name_surname_email['surname']->field;
        $cfg['to_form_field']           = $this->name_surname_email['email']->field;
        $cfg['already_present_field']   = $this->name_surname_email['email']->field;
    }

    /**
     *
     * @param array $items
     */
    protected function removeFormNameSurnameCfg(array &$items)
    {
        $cfg['user_name_form_field']    = '';
        $cfg['user_surname_form_field'] = '';
        $cfg['to_form_field']           = '';
    }

    /**
     *
     * @param array $items
     * @param type $key
     */
    protected function buildElement(array &$items, $key)
    {
        if (!$this->keyPresente($items, $key)) {
            $items[] = $key->toArray();
        }
    }

    /**
     *
     * @param array $items
     * @param type $key
     */
    protected function removeElement(array &$items, $key)
    {
        $item = $this->keyPresente($items, $key);
        if (!is_null($item)) {
            ArrayHelper::removeValue($items, $item);
        }
    }

    /**
     *
     * @param array $items
     * @param type $key
     */
    protected function keyPresente(array $items, CmsLandingFormField $key)
    {

        foreach ($items as $item) {
            if (!strcmp($item['field'], $key->field)) {
                return $item;
            }
        }
        return null;
    }

    /**
     *
     * @return array
     */
    private function getTags()
    {
        $tags    = EventsUtility::getPreferenceCenterTags();
        $tagList = [];
        foreach ($tags as $tag) {
            $tagList[] = ['value' => $tag->id, 'label' => $tag->nome];
        }
        return $tagList;
    }

    /**
     *
     * @param string $label
     * @return string
     */
    protected function buildHtmlLabel(string $label)
    {
        return "<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n$label\n</body>\n</html>";
    }

    protected function buildHtmlPrivacyLable()
    {
        $text = "<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<div style=\"font-size: medium;\"><span style=\"color: #ffffff;\"><a style=\"color: #ffffff;\" href=\"https://backend.openinnovationlombardia.it/site/privacy\" target=\"_blank\" rel=\"noopener\">Prendi visione del trattamento dei dati personali e dell'informativa sull'uso dei cookie</a></span></div>\n<div style=\"font-size: medium;\"><span style=\"font-size: medium;\">Presa visione delle informazioni fornite ai sensi dell&rsquo;articolo 13 del D.Lgs. 196/2003 aggiornato al D.Lgs. 101/2018, esprimo il consenso al trattamento dei dati personali per i fini indicati nella sopraindicata informativa.</span></div>\n</body>\n</html>";
        return $text;
    }

    /**
     *
     * @param array $values
     * @param PostCmsCreatePage $postCmsPage
     */
    protected function relatedPages(array &$values,
                                    PostCmsCreatePage $postCmsPage)
    {
        if (!empty($postCmsPage->form_landing->nav_id_tks_page)) {
            $values["tks_page"] = ["type" => 1, "value" => $postCmsPage->form_landing->nav_id_tks_page];
        }
        if (!empty($postCmsPage->form_landing->nav_id_wating_page)) {
            $values["tks_waiting_page"] = ["type" => 1, "value" => $postCmsPage->form_landing->nav_id_wating_page];
        }
        if (!empty($postCmsPage->form_landing->nav_id_already_present_page)) {
            $values["already_present_page"] = ["type" => 1, "value" => $postCmsPage->form_landing->nav_id_already_present_page];
        }
    }

    /**
     *
     * @param array $values
     * @param PostCmsCreatePage $postCmsPage
     */
    protected function emailsElements(array &$values,
                                      PostCmsCreatePage $postCmsPage)
    {
        if (!empty($postCmsPage->form_landing->confirm_mail_subject)) {
            $values['email_subject'] = $postCmsPage->form_landing->confirm_mail_subject;
        }
        if (!empty($postCmsPage->form_landing->confirm_mail_text)) {
            $values['email_text'] = $postCmsPage->form_landing->confirm_mail_text;
        }
        if (!empty($postCmsPage->form_landing->waiting_mail_text)) {
            $values['email_waiting_list_text'] = $postCmsPage->form_landing->waiting_mail_text;
        }
    }
}