<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base\builder
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base\builder;

use open20\amos\admin\AmosAdmin;
use open20\amos\comments\models\Comment;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\record\NotifyRecord;
use open20\amos\notificationmanager\utility\NotifyUtility;
use Yii;
use yii\helpers\Console;
use yii\helpers\VarDumper;

/**
 * Class ContentMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class CachedContentMailBuilder extends AMailBuilder
{
    public static $cacheRenderedContents = [];
    public static $cacheRenderedTitles = [];
    public static $cacheCommunities = [];
    public static $cacheModuleConfigs = [];
    public static $cacheComments = [];

    public $simulateEmailReceiver = '';
    public $outputEmailText = '';

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
        NotifyUtility::printConsole('user profile' . PHP_EOL);

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
        NotifyUtility::printConsole('CONTENT LIST NETWORK' . PHP_EOL);

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
        NotifyUtility::printConsole('CONTENT LIST PERSONLIZED' . PHP_EOL);

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
        NotifyUtility::printConsole('CONTENT LIST NETWORK PERSONALIZED' . PHP_EOL);

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
        NotifyUtility::printConsole($user->userProfile->id . PHP_EOL);

        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial($viewPath, [
            'model' => $model,
            'profile' => $user->userProfile,
        ]);
        return $ris;
    }


    /**
     * @param $model
     * @param $notifyModule
     * @return mixed
     */
    public static function renderContentGeneralItemMemoized($view, $model, $notifyModule)
    {
        $lang = \Yii::$app->language;

        $classname = get_class($model);
        $idContent = $model->id;
        if (!empty(self::$cacheRenderedContents[$lang]['general'][$classname][$idContent])) {
            NotifyUtility::printConsole("***CACHED - $lang" . " ID: " . $model->id . ' ' . $classname . PHP_EOL);
            return self::$cacheRenderedContents[$lang]['general'][$classname][$idContent];
        } else {
            NotifyUtility::printConsole("NOT CACHED - $lang" . " ID: " . $model->id . ' ' . $classname . PHP_EOL);
            $html = \Yii::$app->controller->renderPartial($view, ['notifyModule' => $notifyModule, 'model' => $model]);
            self::$cacheRenderedContents[$lang]['general'][$classname][$idContent] = $html;
            return $html;
        }

    }

    /**
     * @param $model
     * @param $modelNetwork
     * @param $arrayModelsComments
     * @param $color
     * @param $notifyModule
     * @return mixed|string
     */
    public static function renderContentNetworkItemMemoized($view, $model, $modelNetwork, $notifyModule, $renderedComments = [])
    {
        $lang = \Yii::$app->language;

        $classname = get_class($model);
        $idContent = $model->id;
//        NotifyUtility::printConsole($model->id.' '.$classname.PHP_EOL);
        if ($modelNetwork) {
            $idCommunity = $modelNetwork->id;
            if (!empty(self::$cacheRenderedContents[$lang]['community'][$idCommunity][$classname][$idContent])) {
                NotifyUtility::printConsole("***CACHED - " . " ID: " . $model . ' ' . $classname . PHP_EOL);
                return self::$cacheRenderedContents[$lang]['community'][$idCommunity][$classname][$idContent];
            } else {
                NotifyUtility::printConsole("NOT CACHED - " . " ID: " . $model->id . ' ' . $classname . PHP_EOL);
                $html = \Yii::$app->controller->renderPartial($view, ['notifyModule' => $notifyModule, 'model' => $model, 'renderedComments' => $renderedComments]);
                self::$cacheRenderedContents[$lang]['community'][$idCommunity][$classname][$idContent] = $html;
                return $html;
            }
        }
        return '';
    }

    /**
     * @param $viewPath
     * @param $notification
     * @param $model
     * @param $modelNetwork
     * @param $notifyModule
     * @param $renderedComments
     * @param $modelClassnameCommunityId
     * @return void
     */
    public static function renderContentItemsMemoized($viewPath, $notification, $model, $modelNetwork, $notifyModule, $renderedComments = [], $modelClassnameCommunityId = null)
    {
        if (!empty($notification['models_classname_id']) && $modelClassnameCommunityId == $notification['models_classname_id']) {
//            if($notification['record_id']==6288){
//                NotifyUtility::printConsole('---------------------NETWORK'.PHP_EOL);
//            }
            self::renderContentNetworkItemMemoized($viewPath, $model, $modelNetwork, $notifyModule, $renderedComments);
        } else {
//            if($notification['record_id']==6288){
//                NotifyUtility::printConsole('---------------------GENERALE'.PHP_EOL);
//            }
            self::renderContentGeneralItemMemoized($viewPath, $model, $notifyModule);
        }
    }

    /**
     * @param $viewPath
     * @param $model
     * @return mixed|string
     */
    public static function renderContentItemExtra($viewPath, $classname)
    {
        $lang = \Yii::$app->language;

        if (!empty(self::$cacheRenderedContents[$lang]['extra'][$classname])) {
            return self::$cacheRenderedContents[$lang]['extra'][$classname];
        } else {
            NotifyUtility::printConsole("NOT CACHED - " . ' extra ' . $classname . PHP_EOL);
            $html = \Yii::$app->controller->renderPartial($viewPath);
            self::$cacheRenderedContents[$lang]['extra'][$classname] = $html;
            return $html;
        }
    }

    /**
     * @param $viewPath
     * @param $classname
     * @param null $modelNetwork
     */
    public static function renderContentTitleMemoized($viewPath, $classname, $modelNetwork = null)
    {
        if (is_null($modelNetwork)) {
            return self::renderContentTitleGeneralMemoized($viewPath, $classname);
        } else {
            return self::renderContentTitleNetworkMemoized($viewPath, $classname, $modelNetwork);
        }
    }

    /**
     * @param $viewPath
     * @param $classname
     * @param $modelNetwork
     * @return mixed|void
     */
    public static function renderContentTitleNetworkMemoized($viewPath, $classname, $modelNetwork)
    {
        $lang = \Yii::$app->language;

        $color = NotifyUtility::getTypeOfCommunitycolor($modelNetwork, 1);
        if (!empty(self::$cacheRenderedTitles[$lang]['community'][$modelNetwork->id][$classname])) {
            return self::$cacheRenderedTitles[$lang]['community'][$modelNetwork->id][$classname];
            // NOT CACHED
        } else {
            $view = '';
            $icon = \open20\amos\notificationmanager\utility\NotifyUtility::getIconPlugins($classname, $color);
            if (class_exists($classname)) {
                $model = new $classname();
                $controller = \Yii::$app->controller;
                $view = $controller->renderPartial($viewPath, [
                    'title' => $model->getGrammar()->getModelLabel(),
                    'icon' => $icon,
                    'color' => $color,
                ]);
                self::$cacheRenderedTitles[$lang]['community'][$modelNetwork->id][$classname] = $view;
            }
            return $view;
        }
    }

    public static function renderContentTitleGeneralMemoized($viewPath, $classname)
    {
        $lang = \Yii::$app->language;

        // IN CACHE
        if (!empty(self::$cacheRenderedTitles[$lang]['general'][$classname])) {
            return self::$cacheRenderedTitles[$lang]['general'][$classname];
            // NOT CACHED
        } else {
            $view = '';
            $icon = NotifyUtility::getIconPlugins($classname, 'white');
            if (class_exists($classname)) {
                $model = new $classname();
                $controller = \Yii::$app->controller;

                $view = $controller->renderPartial($viewPath, [
                    'title' => $model->getGrammar()->getModelLabel(),
                    'icon' => $icon
                ]);
                self::$cacheRenderedTitles[$lang]['general'][$classname] = $view;
            }
            return $view;
        }
    }

    /**
     * @param $viewPath
     * @param $modelNetwork
     * @return mixed|string
     */
    public static function renderTitleNetworkMemoized($viewPath, $modelNetwork, $color = 1)
    {
        $lang = \Yii::$app->language;

        if (!empty(self::$cacheRenderedTitles[$lang]['community_titles'][$modelNetwork->id])) {
            $html = self::$cacheRenderedTitles[$lang]['community_titles'][$modelNetwork->id];
            return self::replace_colors($color, $html);
            // NOT CACHED
        } else {
            $color = NotifyUtility::getTypeOfCommunitycolor($modelNetwork, $color);
            $controller = \Yii::$app->controller;
            $view = $controller->renderPartial($viewPath, [
                'modelNetwork' => $modelNetwork,
                'color' => $color
            ]);
            self::$cacheRenderedTitles[$lang]['community_titles'][$modelNetwork->id] = $view;
            return $view;
        }
    }

    /**
     * @param $color [blue, green, red]
     */
    public static function replace_colors($color, $html)
    {
        $colors = \open20\amos\notificationmanager\utility\NotifyUtility::getColorNetwork($color);
        $colorsArray = [0, 1, 2];
        foreach ($colorsArray as $i) {
            if (!empty($colors[$i])) {
                $html = str_replace("___network_color" . $i . "___", $colors[$i], $html);
            }
        }
        return $html;
    }


    /**
     * @param $userId
     * @param $resultset
     */
    public function sendEmailMultipleSectionsCached($userId, $resultset)
    {
        $moduleNotify = AmosNotify::instance();
        $orderModels = $moduleNotify->orderEmailSummary;

        $modelClassnameCommunityId = null;
        $modelClassnameCommunity = ModelsClassname::find()->andWhere(['table' => 'community'])->one();
        if ($modelClassnameCommunity) {
//            NotifyUtility::printConsole($modelClassnameCommunity->id. ' '.$modelClassnameCommunity->table.PHP_EOL);
            $modelClassnameCommunityId = $modelClassnameCommunity->id;
        }
        $user = User::findOne($userId);
        $lang = $this->setUserLanguage($userId);

        $arrayModelsToNotifyGeneral = [];
        $arrayModelsToNotifyNetwork = [];
        $arrayModelsToNotifyNetworkPrivate = [];
        $alreadyAdded = [];
        $class_content = '';

        $tot = 0;
        //CICLO I RECORD DA NOTIFICARE
        foreach ($resultset as $notify) {
            // divido i record in 2 array,  uno generale ed il secondo relativo alle community
            /** @var Notification $notify */
            if (!in_array($notify->class_name . '-' . $notify->content_id, $alreadyAdded)) {
                $tot++;
                $alreadyAdded[] = $notify->class_name . '-' . $notify->content_id;
                // aggiungo i record di notifica dellecommunity
                if (!empty($notify->models_classname_id) && $notify->models_classname_id == $modelClassnameCommunityId) {
                    if(!empty( CachedContentMailBuilder::$cacheCommunities[$notify->record_id])) {
                        $network = CachedContentMailBuilder::$cacheCommunities[$notify->record_id];
                        if ($network) {
                            if (in_array($network->community_type_id, [2, 3])) {
                                $arrayModelsToNotifyNetworkPrivate['community'][$notify->record_id][$notify->class_name][] = $notify->content_id;
                            } else {
                                $arrayModelsToNotifyNetwork['community'][$notify->record_id][$notify->class_name][] = $notify->content_id;
                            }
                        }
                    }
//                    NotifyUtility::printConsole("notifyid-commid:".$notify->id.'-'.$notify->record_id.' -> '.$notify->class_name ." ".$notify->content_id. "\n");

                } else {
                    // aggiungo i record di notifica di carattere generale
                    $arrayModelsToNotifyGeneral[$notify->class_name][] = $notify->content_id;
                }

                // Aggiunge i contenuti non  configurati in notify
                if (strcmp($class_content, $notify->class_name)) {
                    if (!in_array($notify->class_name, $orderModels)) {
                        $orderModels [] = $notify->class_name;
                    }
                    $class_content = $notify->class_name;
                }
            }
        }

//        unset($alreadyAdded);
//                NotifyUtility::printConsole(VarDumper::dumpAsString($arrayModelsToNotifyGeneral, 5, false) . "\n");
//                NotifyUtility::printConsole(VarDumper::dumpAsString($arrayModelsToNotifyNetworkPrivate, 5, false) . "\n");
//                NotifyUtility::printConsole(VarDumper::dumpAsString($arrayModelsToNotifyNetwork, 5, false) . "\n");



        // CONTENUTI GENERALI recupero l'html dei contenuti generali
        $totContentsGeneral = 0;
        $emailContentList = '';

        $emailLayoutGeneral = \Yii::$app->controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/layout_content_list");

        // ciclo le tipologie di contenuti
        foreach ($orderModels as $classname) {
            //render titolo sezione contenuti
            $titleViewPath = CachedContentMailBuilder::getViewPathContentTitle($classname);
            $emailContentTitle = self::renderContentTitleMemoized($titleViewPath, $classname);
            //itemextra
            $contentItemExtra = '';
            $viewPath = CachedContentMailBuilder::getViewPathContentItemExtra($classname);
            if (!empty($viewPath)) {
                $contentItemExtra = CachedContentMailBuilder::renderContentItemExtra($viewPath, $classname);
            }

            $emailContents = '';
            $i = 0;
            // ciclo i contentuti
            if (!empty($arrayModelsToNotifyGeneral[$classname])) {
                foreach ($arrayModelsToNotifyGeneral[$classname] as $content_id) {
//                    NotifyUtility::printConsole( $content_id."\n");
                    if (!empty(self::$cacheRenderedContents[$lang]['general'][$classname][$content_id])) {
                        $i++;
                        $totContentsGeneral++;
                        $emailContents .= self::$cacheRenderedContents[$lang]['general'][$classname][$content_id];
                    }
                }
            }
            // Se non ci sono contenuti di una certa tipologia non stamo il titolo della sezione
            if ($i > 0) {
                $emailContentList .= $emailContentTitle . $contentItemExtra;
                $emailContentList .= $emailContents;
            }
        }

        // CONTENUTI NETWORK recupero l'html dei contenuti network
        $emailLayoutNetwork = \Yii::$app->controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/layout_content_list_network");
        $emailContentListNetwork = '';
        $totContentsNetwork = 0;

        $j = 0;
        // 1 private -2 altre
        $typeNetwork = 0;
        // ciclo le community -metto prima le community private
        $arrayTypeNetworks = [$arrayModelsToNotifyNetworkPrivate, $arrayModelsToNotifyNetwork];
        foreach ($arrayTypeNetworks as $arrayNetwork) {
            $typeNetwork++;
            if (!empty($arrayNetwork['community'])) {
                foreach ($arrayNetwork['community'] as $networkId => $contentTypes) {
                    if ($typeNetwork == 2) {
                        //serve per colore alternato
                        $j++;
                    }
                    // render titolo community
                    if (!empty(CachedContentMailBuilder::$cacheCommunities[$networkId])) {
                        $modelNetwork = CachedContentMailBuilder::$cacheCommunities[$networkId];
                        $color = NotifyUtility::getTypeOfCommunitycolor($modelNetwork, $j);

                        $titleNetworkViewPath = CachedContentMailBuilder::getViewPathTitleNetwork();
                        $emailContentListNetwork .= self::renderTitleNetworkMemoized($titleNetworkViewPath, $modelNetwork, $color);
                    }
                    $k = 0;

                    //ciclo le tipologie di contenuti di ogni community
                    foreach ($orderModels as $classname) {
                        $emailContents = '';
                        if (!empty($contentTypes[$classname])) {
                            $contents = $contentTypes[$classname];
                            $titleNetworkViewPath = CachedContentMailBuilder::getViewPathContentTitleNetwork();
                            $emailContentTitle = self::renderContentTitleMemoized($titleNetworkViewPath, $classname, $modelNetwork, $color);

                            // ciclo tutti i contunuti di una certa tipologia
                            foreach ($contents as $content_id) {
                                if (!empty(self::$cacheRenderedContents[$lang]['community'][$networkId][$classname][$content_id])) {
                                    $totContentsNetwork++;
                                    $k++;
                                    $emailContents .= self::replace_colors($color, self::$cacheRenderedContents[$lang]['community'][$networkId][$classname][$content_id]);
                                }
                            }
                            // Se non ci sono contenuti di una certa tipologia non stamo il titolo della sezione
                            if ($k > 0) {
                                $emailContentListNetwork .= $emailContentTitle;
                                $emailContentListNetwork .= $emailContents;
                            }
                        }
                    }
                    $emailContentListNetwork .= "</table></td></tr></table></td></tr>";
                }

            }
        }

        //sostituisco nel template il placeholder con la lista dei contenuti
        $emailLayoutGeneral = str_replace('{{content_list}}', $emailContentList, $emailLayoutGeneral);
        $emailLayoutNetwork = str_replace('{{content_list}}', $emailContentListNetwork, $emailLayoutNetwork);
        $emailFooter = $this->renderContentFooter([], $user);

        $text = $emailLayoutGeneral;
        if ($totContentsNetwork > 0) {
            $text .= $emailLayoutNetwork;
        }
        $text .= $emailFooter;

        unset($arrayModelsToNotifyNetwork);
        unset($arrayModelsToNotifyGeneral);

        $mailModule = Yii::$app->getModule("email");
        $mailModule->defaultLayout = 'layout_summary_notify';

        //SE NON HO CONTENUTI ESCO
        if ($totContentsGeneral + $totContentsNetwork == 0) {
            NotifyUtility::printConsole('nessun contenuto'.PHP_EOL);
            return false;
        }
        NotifyUtility::printConsole('contenuti: ' . $totContentsGeneral . PHP_EOL);
        NotifyUtility::printConsole('network: ' . $totContentsNetwork . PHP_EOL);

        $email = new Email();
        $from = '';
        $subject = $this->getSubject([]);
        if (isset(Yii::$app->params['email-assistenza'])) {
            // Use default platform email assistance
            $from = Yii::$app->params['email-assistenza'];
        }
//        NotifyUtility::printConsole("------> " . $user->email . PHP_EOL);
        $emailReceiver = $user->email;
        if(!empty($this->simulateEmailReceiver)){
            $emailReceiver = $this->simulateEmailReceiver;
            $subject = 'TEST - '.$subject;
            $this->outputEmailText = $text;
        }
        $ok = $email->sendMail($from, $emailReceiver, $subject, $text);

    }

    /**
     * @param $notification
     * @param $classname
     * @return string
     */
    public static function getViewPathContent($isNetwork, $classname)
    {
        $viewPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/_content_list_item";
        if (!empty(self::$cacheModuleConfigs[$classname]['viewPathCachedEmailSummary'][$classname])) {
            $viewPath = self::$cacheModuleConfigs[$classname]['viewPathCachedEmailSummary'][$classname];
        }

        if ($isNetwork) {
            $viewPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/_content_list_network_item";
            if (!empty(self::$cacheModuleConfigs[$classname]['viewPathCachedEmailSummaryNetwork'][$classname])) {
                $viewPath = self::$cacheModuleConfigs[$classname]['viewPathCachedEmailSummaryNetwork'][$classname];
            }
        }

//        if($classname == 'open20\amos\comments\models\Comment'){
//            $viewPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_comment";
//        }
        return $viewPath;
    }

    /**
     * @param $classname
     * @return mixed|null
     */
    public static function getViewPathContentItemExtra($classname)
    {
        $viewPath = null;
        if (!empty(self::$cacheModuleConfigs[$classname]['viewPathCachedEmailSummaryExtra'][$classname])) {
            $viewPath = self::$cacheModuleConfigs[$classname]['viewPathCachedEmailSummaryExtra'][$classname];
        }
        return $viewPath;
    }

    /**
     * @param $classname
     * @return string
     */
    public static function getViewPathContentTitle($classname)
    {
        $viewPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_title";

        if (!empty(self::$cacheModuleConfigs[$classname]['viewPathEmailSummaryTitle'][$classname])) {
            $viewPath = self::$cacheModuleConfigs[$classname]['viewPathEmailSummaryTitle'][$classname];
        }
        return $viewPath;
    }

    /**
     * @return string
     */
    public static function getViewPathTitleNetwork()
    {
        $viewPathNetwork = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/_network_title_cached";
        return $viewPathNetwork;
    }

    /**
     * @return string
     */
    public static function getViewPathContentTitleNetwork()
    {
        return "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_title_network";

    }

    /**
     * @param $modelsEnabled
     * @throws \yii\base\InvalidConfigException
     */
    public static function setModulesConfigsCache($modelsEnabled)
    {
        $attributes = ['viewPathEmailSummaryTitle', 'viewPathCachedEmailSummary', 'viewPathCachedEmailSummaryNetwork', 'viewPathCachedEmailSummaryExtra'];
        //ciclo tutti i moduli dei modelli abilitati
        foreach ($modelsEnabled as $modelClass) {
            $modelClassname = ModelsClassname::find()->andWhere(['classname' => $modelClass])->one();
            if ($modelClassname) {
                $moduleName = $modelClassname->module;
                if ($moduleName) {
                    $module = \Yii::$app->getModule($moduleName);
                    if ($module) {
                        //set module property in cache
                        foreach ($attributes as $property) {
                            if (property_exists($module, $property)) {
                                CachedContentMailBuilder::$cacheModuleConfigs[$modelClass][$property] = $module->$property;
                            }
                        }
                        unset($module);
                    }
                }
            }
        }
    }
}

