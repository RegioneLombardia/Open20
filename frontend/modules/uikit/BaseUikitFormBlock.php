<?php

namespace app\modules\uikit;

use open20\amos\core\record\RecordDynamicModel;
use open20\amos\socialauth\controllers\SocialAuthController;
use open20\amos\socialauth\models\SocialAuthUsers;
use Exception;
use Hybrid_User_Profile;
use luya\cms\base\PhpBlock;
use luya\cms\helpers\BlockHelper;
use luya\cms\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use function GuzzleHttp\json_decode;

abstract class BaseUikitFormBlock extends PhpBlock
{
    const CONFIGS_EXT     = ".json";
    const COMPONENTS_PATH = __DIR__.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR;
    const NO_POST         = 0;
    const ALREADY_PRESENT = -1;
    const SAVED           = 1;
    const SAVED_WAITING   = 2;
    const SAVED_NOACCOUNT = 3;
    const FORM_ID_FILED_NAME = 'id';

    public $filters           = [];
    public $descriptions = [];
    
    protected $request;
    protected $response;
    protected $hasError       = false;
    protected $safeFieldtypes = ['label'];
    protected $items          = [];
    protected $defaults       = [];
    

    /**
     * Initialize
     */
    public function init()
    {
        $this->request  = Yii::$app->request;
        $this->response = Yii::$app->response;
    }

    /**
     * @inheritDoc
     */
    public function config()
    {
        $cfg = $this->getComponentConfigs();
        return $cfg;
    }

    /**
     * 
     * @param type $component
     * @return type
     */
    protected function getComponentConfigs($component = "")
    {
        $component       = $component ?: $this->component;
        $configs         = $this->getJsonContent(self::COMPONENTS_PATH.$component.self::CONFIGS_EXT);
        $configs['vars'] = $this->setConfigFields(Uikit::element("vars",
                $configs, []));
        $configs["cfgs"] = $this->setConfigFields(Uikit::element("cfgs",
                $configs, []));
        return $configs;
    }


    /**
     * @inheritdoc
     */
    public function admin()
    {
        if ($output = $this->frontend()) {
            return $output;
        } else {
            return '<div><span class="block__empty-text">'.Module::t('block.form.no_content').'</span></div>';
        }
    }

    /**
     *
     * @param array $params
     * @return string
     */
    public function frontend(array $params = array())
    {

        if (!$this->request->isPost) {
             $configs = $this->getValues();
             $cfg     = Uikit::configs($configs);
            if (!Uikit::element('data', $params, '')) {
                $configs["id"]     = Uikit::unique($this->component);
                $params['data']    = $cfg;
                $params['debug']   = $this->config();
                $params['request'] = $this->request->post();
                $params['filters'] = $this->filters;
                $model             = $this->buildModel();
                $params['model'] = $model;
            }
            return $this->view->render($this->getViewFileName('php'), $params,
                    $this);
        } else {
            return parent::frontend();
        }
    }

    /**
     * getViewPath
     *
     * @return string
     */
    public function getViewPath()
    {
        return __DIR__.'/views';
    }

    /**
     * Get configs with default values
     *
     * config_key => config_value or defualt_value
     *
     * @return array
     */
    public function getValues()
    {
        $configs = $this->config();
        $vars    = Uikit::element('vars', $configs, []);
        $cfgs    = Uikit::element('cfgs', $configs, []);

        // Set var and cfgs values
        $configs = array_merge($this->setValues($vars),
            $this->setValues($cfgs, 'cfgs'));

        // Check and set placeholder values
        if ($placeholders = $this->getPlaceholders()) {
            foreach ($placeholders as $placeholder) {
                if ($name = Uikit::element('var', $placeholder)) {
                    $configs[$name] = $this->getPlaceholderValue($name);
                }
            }
        }
        // Set items
        if (Uikit::element("items", $configs)) {
            $configs["items"] = $this->getItems();
        }

        return $configs;
    }

    /**
     * Set field values by type
     *
     * @param array $fields
     * @param string $type
     * @return array
     */
    protected function setValues(array $fields = [], $type = '')
    {
        $values = [];
        foreach ($fields as $i => $field) {
            $fieldName = Uikit::element('var', $field, '');
            $fieldType = Uikit::element('type', $field, '');

            if ($fieldName && $fieldType) {
                // Get field value by type and if empty set default value
                switch ($type) {
                    case 'cfgs':
                        $value = $this->getCfgValue($fieldName,
                            Uikit::element($fieldName, $this->defaults, ''));
                        break;
                    case 'extra':
                        $value = $this->getExtraValue($fieldName,
                            Uikit::element($fieldName, $this->defaults, ''));
                        break;
                    case 'placeholder':
                        $value = $this->getPlaceholderValue($fieldName,
                            Uikit::element($fieldName, $this->defaults, ''));
                        break;
                    default:
                        $value = $this->getVarValue($fieldName,
                            Uikit::element($fieldName, $this->defaults, ''));
                        break;
                }
                // Set field value
                switch ($fieldType) {
                    case self::TYPE_IMAGEUPLOAD:
                        $values[$fieldName]     = $this->getImageSource($value);
                        break;
                    case self::TYPE_LINK:
                        $link                   = $this->getLink($value);
                        $values[$fieldName]     = $link['href'];
                        $configs['link_target'] = $link['link_target'];
                        break;
                    default:
                        $values[$fieldName]     = $value;
                        break;
                }
            }
        }

        return $values;
    }

    /**
     * 
     * @return array
     */
    protected function getPlaceholders()
    {
        return [];
    }

    /**
     * Get items
     *
     * @return array items
     */
    public function getItems()
    {
        // Get item options
        $options = [];
        foreach (Uikit::element("options", $this->items, []) as $i => $option) {
            $options[$option["var"]] = $option["type"];
        }
        // Set items
        $items = [];
        foreach ($this->getVarValue('items', []) as $item) {
            $values = [];
            foreach ($options as $name => $type) {
                if (isset($item[$name])) {
                    if ($type == self::TYPE_IMAGEUPLOAD) {
                        $filter        = Uikit::element($name, $this->filters,
                                []);
                        $defaultFilter = Uikit::element('default', $filter, '');
                        $values[$name] = $this->getImageSource($item[$name],
                            $defaultFilter);
                    } else if ($type == self::TYPE_LINK) {
                        $link                  = $this->getLink($item[$name]);
                        $values[$name]         = $link['href'];
                        $values["link_target"] = Uikit::element('link_target',
                                $item, $link['link_target']);
                    } else {
                        $values[$name] = $item[$name];
                    }
                } else {
                    if ($name == "thumbnail") {
                        $filter        = Uikit::element($name, $this->filters,
                                []);
                        $defaultFilter = Uikit::element('default', $filter, '');
                        $values[$name] = $this->getImageSource($item['image'],
                            $defaultFilter);
                    } else {
                        $values[$name] = "";
                    }
                    if ($type == "zaa-link") {
                        $values["link_target"] = "";
                    }
                }
            }
            $items[] = $values;
        }

        return $items;
    }

    /**
     * Get json content as array for given json file path
     *
     * @param string $path
     * @return array|mixed
     */
    protected function getJsonContent($path = "")
    {
        $data = [];
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $data = json_decode($json, true);
        }
        return $data;
    }

    /**
     * Set config fields
     *
     * @param array $data
     * @return array
     */
    protected function setConfigFields(array $data = [], $type = "vars")
    {
        
        if (count($data)) {
            foreach ($data as $i => $var) {
                // Set items
                if ($var["var"] == "items") {
                    $this->items = $var;
                }
                // Set default value
                $initValue = Uikit::element("initValue", $var, "");
                if(is_bool($initValue)) {
                    $initValue = $initValue == true ? 1 : 0;
                }
               $this->defaults[$var['var']]  = $initValue;
                
                if ($filter = Uikit::element("filter", $var)) {
                    $this->filters[$var['var']] = $filter;
                }
                // Set label
                if ($label = Uikit::element("label", $var, "")) {
                    $var['label'] = Module::t($label);
                }
                // Set placeholder translation
                if ($placeholder = Uikit::element('placeholder', $var, '')) {
                    $var['placeholder'] = Module::t($placeholder);
                }
                // Set description translation
                if ($description = Uikit::element('description', $var, '')) {
                    $this->descriptions[$var["var"]] = Module::t($description);
                }
                // Check options
                $options = Uikit::element('options', $var);
                if (is_array($options) && count($options)) {
                    if ($var["type"] == "zaa-multiple-inputs") {
                        $var["options"] = $this->setConfigFields($options);
                    } else {
                        foreach ($options as $key => $option) {
                            // Set option label translation
                            if (is_array($option) && Uikit::element('label',
                                    $option, '')) {
                                $var['options'][$key]['label'] = Module::t(Uikit::element('label',
                                            $option, ''));
                            }
                        }
                    }
                }
                $data[$i] = $var;
            }
        }
        if($type == 'vars' && !count($this->getVarValues())) {
            $this->setVarValues($this->defaults);
        }
        if($type == 'cfgs' && !count($this->getCfgValues())) {
            $this->setCfgValues($this->defaults);
        }
        return $data;
    }


    /**
     * 
     * @return RecordDynamicModel
     */
    protected function buildModel()
    {
        $model = null;

        try {
            $configs = $this->getValues();
            $data    = Uikit::configs($configs);
            $model   = new RecordDynamicModel();
            $model->setTableName($data['table']);

            if ($data['register_with_social']) {
                $this->addModelSocialFormFields($model);
            }
            if(is_array($data['items'])){
                foreach ($data['items'] as $item) {

                    $model->defineAttribute($item['field']);
                    $model->defineLabel($item['field'], $item['label']);
                    $model->defineType($item['field'], $item['type']);
                    $model->defineSubvalue($item['field'],
                        ArrayHelper::map($item['subvalue'], 'value', 'label'));
                    if (!empty($item['hint'])) {
                        $model->defineHint($item['field'], $item['hint']);
                    }
                    if (!empty($item['field_css'])) {
                        $model->defineHtmlCss($item['field'], $item['field_css']);
                    }
                    $model->addOption($item['field'], 'errorOptions',
                        ['encode' => false]);
                    $model->addRule($item['field'],
                        $this->getTypeRule($item['type']),
                        $this->getTypeRuleOptions($item['type']));
                    if ($item['required']) {
                        $model->addRule($item['field'], 'required');
                    }
                    if (in_array($item['type'], $this->safeFieldtypes)) {
                        $model->addRule($item['field'], 'safe');
                    }
                }
                $model->defineAttribute('user_id');
                $model->defineLabel('user_id', 'User');
                $model->defineType('user_id', 'hidden');
                //$model->addRule('user_id', 'string');
            }
        } catch (Exception $ex) {
            Uikit::trace($ex->getMessage());
            die();
        }
        return $model;
    }

    /**
     *
     * @param RecordDynamicModel $modal
     */
    private function addModelSocialFormFields($model)
    {
        $socialFields = ['user-social' => 'userSocial', 'form-recuperato-da-social' => 'datiRecuperatiDaSocial',
            'social-provider' => 'socialScelto',
            'associate-new-social' => 'associaNuovoAccountSocial'];

        foreach ($socialFields as $key => $field) {
            $model->defineAttribute($field);
            $model->defineType($field, 'hidden');
            $model->addOption($field, 'id', $key);
            $model->addRule($field, 'safe');
        }
    }

    /**
     * @param string $provider
     * @return User|Response
     */
    public function getUserSocial($provider = 'facebook')
    {
        /**
         * @var $adapter \Hybrid_Provider_Adapter
         */
        $module               = Yii::$app->getModule('socialauth');
        $socialAuthController = new SocialAuthController('social-auth', $module);

        $adapter = $socialAuthController->authProcedure($provider);
//        pr("aaa"); die;

        /**
         * If the adapter is not set go back to home
         */
        if (!$adapter) {
//            return $this->goHome();
            return $this->goBack();
        }

        /**
         * @var $userProfile \Hybrid_User_Profile
         */
        $userProfile = $adapter->getUserProfile();

        /**
         * Kick off social user
         */
        $adapter->logout();

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = SocialAuthUsers::findOne(['identifier' => $userProfile->identifier,
                'provider' => $provider]);

        /**
         * If the social user exists
         */
        if ($socialUser) {
            $user            = new Hybrid_User_Profile();
            $profile         = $socialUser->user->userProfile;
            $user->firstName = $profile->nome;
            $user->lastName  = $profile->cognome;
            $user->email     = $socialUser->user->email;
            return $user;
        } else {
            return $userProfile;
        }
    }

    /**
     * 
     * @param string $formtype
     * @return string
     */
    private function getTypeRule($formtype)
    {
        $type = 'string';

        switch ($formtype) {
            case 'checkList':
               $type = 'checklist';
                break;
            case 'hidden':
            case 'label':
            case 'string':
            case 'textarea':
            case 'select':
            case 'password':
                break;
            case 'date':
            case 'email':
                $type = $formtype;
                break;
            case 'attachmentsInput':
                $type = 'file';
                break;
        }
        return $type;
    }

    /**
     * 
     * @param string $formtype
     * @return string
     */
    private function getTypeRuleOptions($formtype)
    {
        $type = '';

        switch ($formtype) {
            case 'hidden':
            case 'label':
            case 'string':
            case 'textarea':
            case 'select':
            case 'password':
            case 'email':
                break;
            case 'date':
                $type = ['format' => 'php:Y-m-d'];
                break;
            case 'attachmentsInput':
                $type = [
                    'skipOnEmpty' => true,
                    'extensions' => '',
                    'checkExtensionByMimeType' => false,
                    'maxFiles' => 1,
                ];
                break;
        }
        return $type;
    }

    /**
     * Get link & link_target
     *
     * @param string $value
     * @return array
     */
    protected function getLink($value = "")
    {
        $value = BlockHelper::linkObject($value);
        return [
            'href' => $value && $value->getHref() ? $value->getHref() : '',
            'link_target' => $value && $value->getTarget() ? $value->getTarget()
                : ""
        ];
    }


    /**
     *
     * @param string $defaultUrl
     * @return mixed
     */
    public function goBack($defaultUrl = null)
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getUser()->getReturnUrl($defaultUrl));
    }

    /**
     *
     * @param string $defaultUrl
     * @return mixed
     */
    public function goTo($defaultUrl)
    {
        Yii::$app->controller->redirect(Url::to(Url::base(true).$defaultUrl));
        return Yii::$app->end();
    }

     /**
     * @inheritdoc
     */
    public function getFieldHelp()
    {
        return $this->descriptions;
    }
}