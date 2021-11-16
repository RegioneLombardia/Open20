<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base\builder
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base\builder;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\record\NotifyRecord;
use open20\amos\notificationmanager\utility\NotifyUtility;
use Yii;
use yii\helpers\Console;

/**
 * Class ContentMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class ContentMailBuilder extends AMailBuilder
{
    /**
     * @return string
     */
    public function getSubject(array $resultset)
    {
        return Yii::t('amosnotify', "#Content_Change_Subject_Notify");
    }
    
    /**
     * @inheritdoc
     */
    public function renderEmail(array $resultset, User $user)
    {
        $mail = '';
        $class_content = '';
        try {
            $mail .= $this->renderContentHeader($resultset);
            foreach ($resultset as $notify) {
                /** @var Notification $notify */
                $cls_name = $notify->class_name;
                /** @var NotifyRecord|ModelLabelsInterface $model */
                $model = $cls_name::find()->andWhere(['id' => $notify->content_id])->one();
                if (!is_null($model) && $model->sendCommunication()) {
                    if (strcmp($class_content, $notify->class_name)) {
                        $mail .= $this->renderContentTitle($model);
                        $class_content = $notify->class_name;
                    }
                    // render list of content of default
                    $mail .= $this->renderContent($model, $user);
                }
            }
            $mail .= $this->renderContentFooter($resultset, $user);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
        
        return $mail;
    }
    
    public function renderEmailLegacy(array $resultset, User $user)
    {
        $mail = '';
        $class_content = '';
        try {
            $mail .= $this->renderContentHeaderLegacy($resultset);
            foreach ($resultset as $notify) {
                /** @var Notification $notify */
                $cls_name = $notify->class_name;
                $modelClassname = ModelsClassname::find()->andWhere(['classname' => $cls_name])->one();
                
                /** @var NotifyRecord|ModelLabelsInterface $model */
                $model = $cls_name::find()->andWhere(['id' => $notify->content_id])->one();
                if (!is_null($model) && $model->sendCommunication()) {
//                    if (strcmp($class_content, $cls_name)) {
//                        $mail .= $this->renderContentTitleLegacy($model);
//                        $class_content = $notify->class_name;
//
//                        if($modelClassname) {
//                            $module = \Yii::$app->getModule($modelClassname->module);
//                            if (!empty($module->viewPathEmailContentSubtitle[$cls_name])) {
//                                $mail .= $this->renderPersonalizedContentSubtitleLegacy($model, $user, $module->viewPathEmailContentSubtitle[$cls_name]);
//                            }
//                        }
//                    }
                    // render list of content of default
                    $mail .= $this->renderContentLegacy($model, $user);
                }
            }
            $mail .= $this->renderContentFooterLegacy($resultset, $user);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
        
        return $mail;
    }
    
    /**
     * @param Record $model
     * @param User $user
     * @return string
     */
    protected function renderContent(Record $model, $user)
    {
        $controller = \Yii::$app->controller;
        $view = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content", [
            'model' => $model,
            'profile' => $user->userProfile
        ]);
        
        $ris = $this->renderView(get_class($model), "content_body", [
            'model' => $model,
            'profile' => $user->userProfile,
            'original' => $view
        ]);
        
        return $ris;
    }
    
    /**
     * @param Record $model
     * @param User $user
     * @return string
     */
    protected function renderContentLegacy(Record $model, $user)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/legacy_content", [
            'model' => $model,
            'profile' => $user->userProfile
        ]);
        return $ris;
    }
    
    
    /**
     * @param ModelLabelsInterface $model
     * @return string
     */
    protected function renderContentTitle(ModelLabelsInterface $model)
    {
        $icon = NotifyUtility::getIconPlugins(get_class($model), 'white');
        
        $controller = \Yii::$app->controller;
      
        $view = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_title", [
            'title' => $model->getGrammar()->getModelLabel(),
            'icon' => $icon
        ]);
        
        $ris = $this->renderView(get_class($model), "content_title", [
            'title' => $model->getGrammar()->getModelLabel(),
            'icon' => $icon,
            'original' => $view
        ]);
        return $ris;
    }
    
    /**
     * @param ModelLabelsInterface $model
     * @return string
     */
    protected function renderContentTitleLegacy(ModelLabelsInterface $model)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_title", [
            'title' => $model->getGrammar()->getModelLabel(),
        ]);
        return $ris;
    }
    
    /**
     * @param array $resultset
     * @return string
     */
    protected function renderContentHeader(array $resultset)
    {
        $controller = \Yii::$app->controller;
        $contents_number = count($resultset);
        $view = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_header", [
            'contents_number' => $contents_number
        ]);
        
        $ris = $this->renderView(AmosNotify::getModuleName(), "content_header", [
            'contents_number' => $contents_number,
            'original' => $view
        ]);
        
        return $ris;
    }
    
    /**
     * @param array $resultset
     * @return string
     */
    protected function renderContentHeaderLegacy(array $resultset)
    {
        $controller = \Yii::$app->controller;
        $contents_number = count($resultset);
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_header", [
            'contents_number' => $contents_number
        ]);
        return $ris;
    }
    
    /**
     * @param array $resultset
     * @param User $user
     * @return string
     */
    protected function renderContentFooter(array $resultset, User $user)
    {
        $controller = \Yii::$app->controller;
        $view = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_footer", ['user' => $user]);
        
        $ris = $this->renderView(AmosNotify::getModuleName(), "content_footer", [
            'user' => $user,
            'original' => $view
        ]);
        
        return $ris;
    }
    
    /**
     * @param array $resultset
     * @return string
     */
    protected function renderContentFooterLegacy(array $resultset, User $user)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_footer", ['user' => $user]);
        return $ris;
    }
    
    
    /**
     * @param $resultSetNormal
     * @param $resultSetNetwork
     * @param $resultSetComments
     * @param User $user
     * @return string
     */
    protected function renderEmailMultipleSections($resultSetNormal, $resultSetNetwork, $resultSetComments, $user)
    {
        $mail = '';
        
        try {
            
            // ------------ NOTIFICATION SECTION  WITHOUT NETWORK SCOPE -------------
            $mail .= $this->renderSectionWitoutScope($resultSetNormal, $user);
            
            // ------------ NOTIFICATION SECTION WITH NETWORK SCOPE  -------------
            $mail .= $this->renderSectionWithScope($resultSetNetwork, $resultSetComments, $user);
            
            $mail .= $this->renderContentFooter($resultSetNormal, $user);
        } catch (\Exception $ex) {print_r($ex->getMessage());print_r('#######');print_r($ex->getTraceAsString());die('sdfaòaa');
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
        
        return $mail;
    }
    
    /**
     * @param array $resultSetNormal
     * @param User $user
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function renderSectionWitoutScope($resultSetNormal, $user)
    {
        $mail = '';
        $class_content = '';
        $orderModels = [];
        $arrayModelsToNotifiy = [];
        foreach ($resultSetNormal as $notify) {
            /** @var Notification $notify */
            $cls_name = $notify->class_name;
            /** @var NotifyRecord|ModelLabelsInterface $model */
            $model = $cls_name::find()->andWhere(['id' => $notify->content_id])->one();
            
            if (!is_null($model) && $model->sendCommunication()) {
                $arrayModelsToNotifiy[$notify->class_name][] = $model;
                
                if (strcmp($class_content, $notify->class_name)) {
                    if (!in_array($notify->class_name, $orderModels)) {
                        $orderModels [] = $notify->class_name;
                    }
                    $class_content = $notify->class_name;
                }
            }
        }
        
        // render the contents using the order of the models defined in configuration
        foreach ($orderModels as $classname) {
            $model = new $classname();
            
            $isViewPersonalized = false;
            if (!empty($arrayModelsToNotifiy[$classname])) {
                $modelClassname = ModelsClassname::find()->andWhere(['classname' => $classname])->one();
                if ($modelClassname) {
                    $module = \Yii::$app->getModule($modelClassname->module);
                    // -------- TITLE ------
                    //render personalized title
                    if (!empty($module->viewPathEmailSummaryTitle[$classname])) {
                        $mail .= $this->renderContentTitlePersonalized($module->viewPathEmailSummaryTitle[$classname], $model);
                    } //render default title
                    else {
                        $mail .= $this->renderContentTitle($model);
                    }
                    // -------- CONTENT ------
                    // render list of content personalized
                    if (!empty($module->viewPathEmailSummary[$classname])) {
                        $mail .= $this->renderPersonalizedContentList($arrayModelsToNotifiy[$classname], $user, $module->viewPathEmailSummary[$classname]);
                        $isViewPersonalized = true;
                    }
                }
                // render list of content of default
                if (!$isViewPersonalized) {
                    $mail .= $this->renderContentList($arrayModelsToNotifiy[$classname], $user);
                }
            }
        }

//        $this->logOn( '----------- ');
        return $mail;
    }
    
    /**
     * @param $resultSetNetwork
     * @param $resultSetComments
     * @param User $user
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function renderSectionWithScope($resultSetNetwork, $resultSetComments, $user)
    {
        $mail = '';
        $class_content = '';
        $arrayModelsToNotifiyNetwork = [];
        $orderModels = [];
        $orderNetworks = [];
        
        if (isset($resultSetNetwork) and count($resultSetNetwork)) {
            // prepare the array of contents to render for the view
            foreach ($resultSetNetwork as $notify) {
                /** @var Notification $notify */
                $cls_name = $notify->class_name;
                /** @var NotifyRecord|ModelLabelsInterface $model */
                $model = $cls_name::find()->andWhere(['id' => $notify->content_id])->one();
                if (!is_null($model) && $model->sendCommunication()) {
                    $arrayModelsToNotifiyNetwork[$notify->models_classname_id][$notify->record_id][$notify->class_name][] = $model;
                    if (strcmp($class_content, $notify->class_name)) {
                        if (!in_array($notify->class_name, $orderModels)) {
                            $orderModels [] = $notify->class_name;
                        }
                        $class_content = $notify->class_name;
                    }
                    
                    // order of community (communities closed first)
                    $networkClassname = ModelsClassname::findOne($notify->models_classname_id);
                    if ($networkClassname->classname == 'open20\amos\community\models\Community') {
                        $classnameNetwork = $networkClassname->classname;
                        $community = $classnameNetwork::findOne($notify->record_id);
                        if ($community->community_type_id == 3 && !in_array($community->id, $orderNetworks)) {
                            array_unshift($orderNetworks, $community->id);
                        } else {
                            if (!in_array($community->id, $orderNetworks)) {
                                $orderNetworks [] = $community->id;
                            }
                        }
                    }
                }
            } // foreach
        } // if $resultSetNetwork
        
        if (isset($resultSetComments) and count($resultSetComments)) {
            // Prepare the array with the comments
            $arrayModelsComments = [];
            foreach ($resultSetComments as $notify) {
                /** @var Notification $notify */
                $cls_name = $notify->class_name;
                /** @var NotifyRecord|ModelLabelsInterface $model */
                $model = $cls_name::find()->andWhere(['id' => $notify->content_id])->one();
                if (!is_null($model) && $model->sendCommunication() && !empty($notify->models_classname_id)) {
                    $arrayModelsComments[$notify->models_classname_id][$notify->record_id][$model->context][$model->context_id][] = $model;
                }
            }
        } // if $resultSetNetwork
        
        if (!empty($arrayModelsToNotifiyNetwork)) {
            $mail .= "<tr>
                  <td colspan='2' style='color:#4B4B4B; font-size:22px; font-weight:bold; font-family:sans-serif; padding:20px 0 0 0;'>" . AmosNotify::t('amosnotify', 'Dalle tue community') . "</td></tr> ";
        }
        // render the communities
        foreach ($arrayModelsToNotifiyNetwork as $networkClassnameId => $networkRecods) {
            $modelClassnameNetwork = ModelsClassname::find()->andWhere(['id' => $networkClassnameId])->one();
            $NetworkClassname = $modelClassnameNetwork->classname;
            $i = 1;
            
            // use the order of networks ( first secret communities, and after the rest of communities)
            foreach ($orderNetworks as $networkId) {
                $modelNetwork = $NetworkClassname::find()->andWhere(['id' => $networkId])->one();
                $color = NotifyUtility::getTypeOfCommunitycolor($modelNetwork, $i);
                
                // render the colored box with the name of the community
                $mail .= $this->renderNetworkTitle($model, $modelNetwork, $color);
                foreach ($orderModels as $classname) {
                    $model = new $classname();
                    $isViewPersonalized = false;
                    // Check if the plugin as a personalized view for the summary
                    if (!empty($arrayModelsToNotifiyNetwork[$networkClassnameId][$networkId][$classname])) {
                        // render name of the type of content
                        $mail .= $this->renderContentTitleNetwork($model, $color);
                        
                        $modelClassname = ModelsClassname::find()->andWhere(['classname' => $classname])->one();
                        // render the content list with the personalized view that is inside the content module
                        if ($modelClassname) {
                            $module = \Yii::$app->getModule($modelClassname->module);
                            if (!empty($module->viewPathEmailSummaryNetwork[$classname])) {
                                $mail .= $this->renderPersonalizedContentListNetwork(
                                    $arrayModelsToNotifiyNetwork[$networkClassnameId][$networkId][$classname],
                                    !empty($arrayModelsComments[$networkClassnameId][$networkId][$classname]) ? $arrayModelsComments[$networkClassnameId][$networkId][$classname] : [],
                                    $user,
                                    $module->viewPathEmailSummaryNetwork[$classname],
                                    $modelNetwork,
                                    $color);
                                $isViewPersonalized = true;
                            }
                        }
                        
                        // default view for the summary
                        // render the content list of default
                        if (!$isViewPersonalized) {
                            $mail .= $this->renderContentListNetwork(
                                $arrayModelsToNotifiyNetwork[$networkClassnameId][$networkId][$classname],
                                !empty($arrayModelsComments[$networkClassnameId][$networkId][$classname]) ? $arrayModelsComments[$networkClassnameId][$networkId][$classname] : [],
                                $user,
                                $modelNetwork,
                                $color);
                        }
                    }
                }
                $mail .= "</table></td></tr></table></td></tr>";
                $i++;
            }
        }
        
        return $mail;
    }
    
    /**
     * @param $resultSetNormal
     * @param User $user
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function renderEmailUserNotify($resultSetNormal, $user)
    {
        $mail = '';
        $class_content = '';

//        try {
        
        // ------------ NOTIFICATION SECTION  WITHOUT NETWORK SCOPE -------------
        $mail .= $this->renderSectionWithClasses($resultSetNormal, $user);
        
        $mail .= $this->renderContentFooter($resultSetNormal, $user);

//        } catch (\Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
//        }
        
        return $mail;
    }
    
    /**
     * @param $resultSetNormal
     * @param User $user
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function renderSectionWithClasses($resultSetNormal, $user)
    {
        $mail = '';
        
        $class_content = '';
        $orderModels = [];
        $arrayModelsToNotifiy = $resultSetNormal;
        //$this->logOn('renderSectionWithClasses - inizio '); //return;
        
        // render the contents using the order of the models defined in confguration
        //$mail .= '<p>renderSectionWithClasses</p>';
        foreach ($resultSetNormal as $classname => $r) {
            //$this->logOn('renderSectionWithClasses - ' . $classname); //return;
            
            $model = new $classname();
            
            $isViewPersonalized = false;
            //$mail .= '<p>classname ' . $classname . '</p>';
            if (!empty($arrayModelsToNotifiy[$classname])) {
                $modelClassname = ModelsClassname::find()->andWhere(['classname' => $classname])->one();
                if ($modelClassname) {
                    $module = \Yii::$app->getModule($modelClassname->module);
                    //$mail .= '<p>Prima di title viewPathEmailSummaryTitle</p>';
                    // -------- TITLE ------
                    //render personalized title
                    if (!empty($module->viewPathEmailSummaryTitle[$classname])) {
                        $mail .= $this->renderContentTitlePersonalized($module->viewPathEmailSummaryTitle[$classname], $model);
                    } //render default title
                    else {
                        $mail .= $this->renderContentTitle($model);
                    }
                    
                    if (method_exists($this, 'renderTextBeforeContent')) {
                        $mail .= $this->renderTextBeforeContent($classname);
                    }
                    
                    // -------- CONTENT ------
                    // render list of content personalized
                    if (!empty($module->viewPathEmailSummary[$classname])) {
                        $mail .= $this->renderPersonalizedContentList($arrayModelsToNotifiy[$classname], $user, $module->viewPathEmailSummary[$classname]);
                        $isViewPersonalized = true;
                    }
                }
                // render list of content of default
                if (!$isViewPersonalized) {
                    /** @var AmosAdmin $adminModule */
                    $adminModule = AmosAdmin::instance();
                    if ($classname == $adminModule->model('UserProfile')) {
                        $mail .= $this->renderUserProfileList($arrayModelsToNotifiy[$classname], $user);
                    } else {
                        $mail .= $this->renderContentList($arrayModelsToNotifiy[$classname], $user);
                    }
                }
            }
        }
        //$this->logOn('renderSectionWithClasses - fine ' . $mail);
        //$this->logOn( '----------- ');
        return $mail;
    }
    
    /**
     * @param ModelLabelsInterface $model
     * @return string
     */
    protected function renderContentTitlePersonalized($viewPath, ModelLabelsInterface $model)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial($viewPath, [
            'title' => $model->getGrammar()->getModelLabel(),
        ]);
        return $ris;
    }
    
    /**
     * @param Record[] $model
     * @param User $user
     * @return string
     */
    protected function renderContentList($arrayModels, $user)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_list", [
            'arrayModels' => $arrayModels,
            'profile' => $user->userProfile
        ]);
        return $ris;
    }
    
    /**
     * @param string $section_title
     * @param string $section_description
     * @return string
     */
    protected function renderSectionTitle($section_title, $section_description = '')
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_section_title", [
            'section_title' => $section_title,
            'section_description' => $section_description
        ]);
        return $ris;
    }
    
    /**
     * @param Record[] $model
     * @param User $user
     * @return string
     */
    protected function renderUserProfileList($arrayModels, $user)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/userprofile_list", [
            'arrayModels' => $arrayModels,
            'profile' => $user->userProfile
        ]);
        return $ris;
    }
    
    /**
     * @param $arrayModels
     * @param array $arrayModelsComments
     * @param User $user
     * @param $modelNetwork
     * @param string $color
     * @return string
     */
    protected function renderContentListNetwork($arrayModels, $arrayModelsComments, $user, $modelNetwork, $color)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_list_network", [
            'arrayModels' => $arrayModels,
            'arrayModelsComments' => $arrayModelsComments,
            'profile' => $user->userProfile,
            'modelNetwork' => $modelNetwork,
            'color' => $color
        ]);
        return $ris;
    }
    
    /**
     * @param Record[] $model
     * @param User $user
     * @return string
     */
    protected function renderPersonalizedContentList($arrayModels, $user, $viewPath)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial($viewPath, [
            'arrayModels' => $arrayModels,
            'profile' => $user->userProfile,
        ]);
        return $ris;
    }
    
    /**
     * @param $arrayModels
     * @param array $arrayModelsComments
     * @param User $user
     * @param $viewPath
     * @param $modelNetwork
     * @param string $color
     * @return string
     */
    protected function renderPersonalizedContentListNetwork($arrayModels, $arrayModelsComments, $user, $viewPath, $modelNetwork, $color)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial($viewPath, [
            'arrayModels' => $arrayModels,
            'arrayModelsComments' => $arrayModelsComments,
            'profile' => $user->userProfile,
            'modelNetwork' => $modelNetwork,
            'color' => $color
        ]);
        return $ris;
    }
    
    /**
     * @param ModelLabelsInterface $model
     * @param string $color
     * @return string
     */
    protected function renderContentTitleNetwork(ModelLabelsInterface $model, $color)
    {
        $icon = \open20\amos\notificationmanager\utility\NotifyUtility::getIconPlugins(get_class($model), $color);
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_title_network", [
            'title' => $model->getGrammar()->getModelLabel(),
            'icon' => $icon,
            'color' => $color,
        ]);
        return $ris;
    }
    
    /**
     * @param Record $model
     * @param $modelNetwork
     * @param string $color
     * @return string
     */
    protected function renderNetworkTitle(Record $model, $modelNetwork, $color)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/network_title", [
            'model' => $model,
            'modelNetwork' => $modelNetwork,
            'color' => $color
        ]);
        return $ris;
    }
    
    /**
     * @param $model
     * @param User $user
     * @param $viewPath
     * @return string
     */
    protected function renderPersonalizedContentSubtitle($model, $user, $viewPath)
    {
        Console::stdout($user->userProfile->id . PHP_EOL);
        
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial($viewPath, [
            'model' => $model,
            'profile' => $user->userProfile,
        ]);
        return $ris;
    }
}
