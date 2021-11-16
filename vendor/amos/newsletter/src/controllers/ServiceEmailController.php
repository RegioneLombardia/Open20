<?php

namespace amos\newsletter\controllers;


use amos\newsletter\models\FormSubscribeNewsletter;
use amos\newsletter\models\Newsletter;
use amos\newsletter\Module;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class ServiceEmailController extends CrudController
{
    public $module;

    public function init()
    {
        $this->setModelObj(new Newsletter);
        $this->setModelSearch(new Newsletter);
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        $this->module = \Yii::$app->getModule('newsletter');
        parent::init();
//        $this->setModelObj(Newsletter::className());

    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'subscribe-to-group',
                            'subscribe-to-array-group',
                            'get-fields'
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'index-groups',
                            'view-group',
                            'subscribers-list',
                            'subscribe-admin',
                            'fields',
                            'templates',
                            'email-templates',
                            'email',
                            'email-update',
                            'get-send-history',
                            'email-statistics',
                            'recipient-statistics',
                            'enable-disable-dynamic-fields-email',
                            'send-email-to-list',
                            'detail-user',
                            'update-detail-user',
                            'copy-message'
                        ],
                        'roles' => ['ADMIN']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    public function actionIndex($layout = null)
    {
        Url::remember();
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $lists = [];
        $queryParams = \Yii::$app->request->queryParams;
        $decoded = $mailService->getLists($queryParams);
        if (!empty($decoded->Items)) {
            $lists = $decoded->Items;
        }

        $pagination = $this->setPagination($mailService, $decoded);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $lists
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider, 'pagination' => $pagination]);
    }


    /**
     * @param $idList
     * @return string
     */
    public function actionIndexGroups($idList)
    {
        Url::remember();
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $lists = [];
        $decoded = $mailService->getGroupsByList($idList);
        if (!empty($decoded->Items)) {
            $lists = $decoded->Items;
        }

        $pagination = $this->setPagination($mailService, $decoded);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $lists
        ]);

        return $this->render('index-groups', ['dataProvider' => $dataProvider, 'pagination' => $pagination]);
    }

    /**
     * @param $idList
     * @return string
     */
    public function actionViewGroup($idGroup)
    {
        $this->layout = 'list';
        Yii::$app->view->params['createNewBtnParams'] = ['layout' => ""];
        \Yii::$app->view->params['additionalButtons'] = [
            'htmlButtons' => [Html::a('Iscrivi utente', ['subscribe-admin', 'idGroup' => $idGroup], ['class' => 'btn btn-navigation-primary'])]
        ];

        if ($this->module) {
            $mailServiceClassname = $this->module->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $lists = [];

            $queryParams = \Yii::$app->request->queryParams;
            if (!empty(\Yii::$app->request->get('ServiceEmail'))) {
                $queryParams = $mailService->buildQueryParams($queryParams, \Yii::$app->request->get('ServiceEmail'));
            }

            $decoded = $mailService->getSubscribtionsToGroup($idGroup, $queryParams);

            if (!empty($decoded->Items)) {
                $lists = $decoded->Items;
            }

            $pagination = $this->setPagination($mailService, $decoded);
            $dataProvider = new ArrayDataProvider([
                'allModels' => $lists,
            ]);
            return $this->render('subscribers', ['dataProvider' => $dataProvider, 'pagination' => $pagination, 'mailService' => $mailService]);
        }
    }

    /**
     * @param $mailService
     * @param $decoded
     * @return Pagination
     */
    public function setPagination($mailService, $decoded)
    {
        $currentPage = 0;
        $totElement = 0;;

        $paginationConfig = $mailService->getPaginationConfigs();
        $pagination = new \yii\data\Pagination();

        if (!empty($paginationConfig['totalCount'])) {
            $totalCount = $paginationConfig['totalCount'];
            if (property_exists($decoded, $totalCount)) {
                $totElement = $decoded->$totalCount;
                $pagination->totalCount = $totElement;
            }
        }

        if (!empty($paginationConfig['pageParam'])) {
            $pageParam = $paginationConfig['pageParam'];
            if (property_exists($decoded, $pageParam)) {
                $currentPage = $decoded->$pageParam;
                $pagination->pageParam = $pageParam;
                $pagination->setPage($currentPage);
            }

        }

        return $pagination;
    }

    /**
     * @param $idGroup
     * @return \yii\web\Response
     */
    public function actionSubscribeToGroup($idGroup)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();
        $model = new FormSubscribeNewsletter();
        if (\Yii::$app->request->post() && $model->load(\Yii::$app->request->post())) {
            $data = new \stdClass();
            $data->Email = $model->email;
            $data->Name = $model->name;
            $data->Fields = [];
            foreach ($model->fields as $key => $name) {
                $data->Fields[] = ['Id' => $key, 'Value' => $name];
            }
            $data = [$data];
            $res = $mailService->subscribeToGroup($idGroup, $data);

            if ($res) {
                \Yii::$app->session->addFlash('success', Module::t('amosnewsletter', 'You have successfully registered to the newsletter'));
                if (!empty($post['url_redirect'])) {
                    return $this->redirect($post['url_redirect']);
                } elseif (!empty($model->redirect_url)) {
                    return $this->redirect($model->redirect_url);
                }
                return $this->redirect(Url::previous());
            }
        }
        return $this->redirect(Url::previous());
    }

    /**
     *
     * @return type
     */
    public function actionSubscribeToArrayGroup()
    {
        $module = \Yii::$app->getModule('newsletter');
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();
        if (!empty($module) && !empty($module->customModel)) {
            $classModel = $module->customModel;
            $model = new $classModel;
        } else {
            $model = new FormSubscribeNewsletter();
        }

        if (\Yii::$app->request->post() && $model->load(\Yii::$app->request->post())) {
            $idGroups = $model->idgroups;
            $tematiche = \Yii::$app->request->post('tematiche');
            $tematicheser = implode(', ', $tematiche);

            $data = new \stdClass();
            $data->Email = $model->email;
            $data->Name = $model->name;
            $data->Fields = [];
//            foreach ($model->fields as $key => $name){
//                $data->Fields[]= ['Id' => $key, 'Value' => $name];
//            }
            $data->Fields[] = ['Id' => '1', 'Value' => $model->campo1];
            $data->Fields[] = ['Id' => '2', 'Value' => $model->campo2];
            $data->Fields[] = ['Id' => '3', 'Value' => $model->campo3];
            $data->Fields[] = ['Id' => '5', 'Value' => $model->campo5];
            $data->Fields[] = ['Id' => '28', 'Value' => $tematicheser];
            $data->Fields[] = ['Id' => '29', 'Value' => $model->ruolo];

            $data = [$data];
            foreach ($idGroups as $value) {
                $res = $mailService->subscribeToGroup($value, $data, true);
                sleep(5);
                if (!$res) {
                    break;
                }
            }

            if ($res) {
                \Yii::$app->session->addFlash('success', Module::t('amosnewsletter', 'You have successfully registered to the newsletter'));
                if (!empty($model->redirect_url)) {
                    return $this->redirect($model->redirect_url);
                }
                return $this->redirect(Url::previous());
            }
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param $idGroup
     * @return \yii\web\Response
     */
    public function actionSubscribeToList($idList)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();
        $model = new FormSubscribeNewsletter();
        if (\Yii::$app->request->post() && $model->load(\Yii::$app->request->post())) {
            $data = new \stdClass();
            $data->Email = $model->email;
            $data->Name = $model->name;
            $data->Fields = [];
            foreach ($model->fields as $key => $name) {
                $data->Fields[] = ['Id' => $key, 'Value' => $name];
            }
            $data = [$data];
            $res = $mailService->subscribeToList($idList, $data);

            if ($res) {
                \Yii::$app->session->addFlash('success', Module::t('amosnewsletter', 'You have successfully registered to the newsletter'));
                if (!empty($post['url_redirect'])) {
                    return $this->redirect($post['url_redirect']);
                }
                return $this->redirect(Url::previous());
            }
        }
        return $this->redirect(Url::previous());
    }


    /**
     * @param $idGroup
     * @return \yii\web\Response
     */
    public function actionSubscribersList($idList)
    {
        //&filterby="Email==%27ivano.piazza@gmail.com%27"
        $this->setUpLayout('list');
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $lists = [];
        $queryParams = \Yii::$app->request->queryParams;
        if (!empty(\Yii::$app->request->get('ServiceEmail'))) {
            $queryParams = $mailService->buildQueryParams($queryParams, \Yii::$app->request->get('ServiceEmail'));
        }
        $decoded = $mailService->getSubscribersByList($idList, $queryParams);
        if (!empty($decoded->Items)) {
            $lists = $decoded->Items;
        }

        Yii::$app->view->params['createNewBtnParams'] = ['layout' => ""];
        \Yii::$app->view->params['additionalButtons'] = [
            'htmlButtons' => [Html::a('Iscrivi utente', ['subscribe-admin', 'idList' => $idList], ['class' => 'btn btn-navigation-primary'])]
        ];

        $pagination = $this->setPagination($mailService, $decoded);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $lists
        ]);

        return $this->render('subscribers', ['dataProvider' => $dataProvider, 'pagination' => $pagination, 'mailService' => $mailService]);
    }

    /**
     * @return string
     */
    public function actionFields()
    {
        $fields = $this->actionGetFields();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $fields
        ]);

        return $this->render('fields', ['dataProvider' => $dataProvider]);
    }

    /**
     * TODO Sistemare  i parametri e  $decode->Items che sono chiodati per driver
     * @return array
     */
    public function actionGetFields()
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();
        $params = [];
        $decoded = $mailService->getDynamicFields($params);

        $fields = [];
        if (!empty($decoded->Items)) {
            $fields = $decoded->Items;
        }


        if ($decoded->IsPaginated) {
            while ($decoded->Skipped < $decoded->TotalElementsCount) {
                $pagenumber = $decoded->PageNumber + 1;
                $params = ArrayHelper::merge($params, ['PageNumber' => $pagenumber]);
                $decoded = $mailService->getDynamicFields($params);
                if (!empty($decoded->Items)) {
                    $fields = ArrayHelper::merge($decoded->Items, $fields);;
                }
            }
        }
//        pr($fields);

        return $fields;

    }

//    /**
//     * @param $idList
//     * @return string
//     */
//    public function actionTemplates($idList){
//        $mailServiceClassname = $this->module->mail_service_driver;
//        $mailService = new $mailServiceClassname();
//
//        $lists = [];
//        $decoded = $mailService->getTemplates($idList);
//        if(!empty($decoded->Items)){
//            $lists = $decoded->Items;
//        }
//
//        $pagination = $this->setPagination($mailService, $decoded);
//
//        $dataProvider = new ArrayDataProvider([
//            'allModels'=> $lists
//        ]);
//        return $this->render('templates', ['dataProvider' => $dataProvider, 'pagination' => $pagination]);
//    }
    /**
     * @param $idList
     * @return string
     */
    public function actionEmailTemplates($idList, $idGroups = null)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $lists = [];
        $queryParams = \Yii::$app->request->queryParams;

        $decoded = $mailService->getEmailList($idList, $queryParams);
        if (!empty($decoded->Items)) {
            $lists = $decoded->Items;
        }
        $pagination = $this->setPagination($mailService, $decoded);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $lists
        ]);
        return $this->render('email_messages', ['dataProvider' => $dataProvider, 'pagination' => $pagination, 'idGroup' => $idGroups]);
    }

    /**
     * @param $idList
     * @return string
     */
    public function actionEmail($idList, $idMessage)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $decoded = $mailService->getEmail($idList, $idMessage);
        return $this->render('preview-email', ['email' => $decoded]);
    }

    /**
     * @param $idList
     * @return string
     */
    public function actionEnableDisableDynamicFieldsEmail($idList, $idMessage, $enable = 'true')
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $decoded = $mailService->enableDisableDynamicFieldsEmail($idList, $idMessage, $enable);
        return $this->redirect(['email', 'idList' => $idList, 'idMessage' => $idMessage]);
    }


    /**
     * @param $idList
     * @param $idMessage
     * @return string
     */
    public function actionEmailUpdate($idList, $idMessage)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();
        $decoded = $mailService->getEmail($idList, $idMessage);
//pr($decoded);
        if (\Yii::$app->request->post()) {
            $post = \Yii::$app->request->post();
            $body = [
                'Subject' => $decoded->Subject,
                'idList' => $decoded->idList,
                'Content' => $decoded->Content,
                'Tags' => $decoded->Tags,
                'TrackingInfo' => $decoded->TrackingInfo,
                'Embed' => $decoded->Embed,
                'IsConfirmation' => $decoded->IsConfirmation,
                'UseDynamicField' => $decoded->UseDynamicField,
                'Structure' => $decoded->Structure
            ];

            if (!empty($post['Subject'])) {
                $body['Subject'] = $post['Subject'];
            }
            if (!empty($post['Content'])) {
                $body['Content'] = $post['Content'];
            }
            $decodedReponse = $mailService->updateEmail($idList, $idMessage, $body);
            if (!empty($post['UseDynamicField'])) {
                $mailService->enableDisableDynamicFieldsEmail($idList, $idMessage, 'true');
            }
            return $this->redirect(['email-update', 'idList' => $idList, 'idMessage' => $idMessage]);
        }
        return $this->render('update-email', ['email' => $decoded]);
    }

    /**
     * @param $list_id
     * @param $message_id
     */
    public function actionSendEmailToList($idList, $idMessage, $idGroups = null)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $params = [];
        if (!empty($idGroups)) {
            $params = ['inGroups' => $idGroups];
        }

        $decoded = $mailService->sendEmail($idList, $idMessage, $params);
        \Yii::$app->session->addFlash('success', "<strong>Email inviate: </strong>" . $decoded->Sent
            . "<br><strong>Email non valide</strong>: " . implode(', ', $decoded->InvalidRecipients)
            . "<br><strong>Email non processate</strong>: " . implode(', ', $decoded->UnprocessedRecipients));
        return $this->redirect(['email-templates', 'idList' => $idList]);

    }

    /**
     * @param $idList
     * @param $idMessage
     * @return \yii\web\Response
     */
    public function actionCopyMessage($idList, $idMessage){
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $decoded = $mailService->getEmail($idList, $idMessage);
        if (\Yii::$app->request->post()) {
            $post = \Yii::$app->request->post();
            $body = [
                'Subject' => $post['Subject'],
                'Notes' => $post['Notes'],
            ];
        }
        else {
            $body = [
                'Subject' => $decoded->Subject .' (Copy)',
            ];
        }
        $decoded = $mailService->copyMessage($idList, $idMessage, $body);
        return $this->redirect(['email-templates','idList'=> $idList]);
    }


    /**
     * @param $idList
     * @param $idMessage
     * @return string
     */
    public function actionGetSendHistory($idList, $idMessage)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $lists = [];
        $queryParams = \Yii::$app->request->queryParams;

        $decoded = $mailService->getEmailSendHistory($idList, $idMessage, $queryParams);
        if (!empty($decoded->Items)) {
            $lists = $decoded->Items;
        }
        $pagination = $this->setPagination($mailService, $decoded);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $lists
        ]);
        return $this->render('send-history', ['dataProvider' => $dataProvider, 'pagination' => $pagination]);
    }

    /**
     * @param $idList
     * @param $idMessage
     * @return string
     */
    public function actionEmailStatistics($idMessage, $idList = null)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $lists = [];
        $listsHistory = [];
        $listsRecipients = [];
        $queryParams = \Yii::$app->request->queryParams;

        $decodedOpened = $mailService->getStatisticOpened($idMessage, true, $queryParams);
        $decodedClicks = $mailService->getStatisticClicks($idMessage, true, $queryParams);
        $decodedBounces = $mailService->getStatisticBounces($idMessage, true, $queryParams);
        $decodedUnsubscribed = $mailService->getStatisticUnsubscribed($idMessage, true, $queryParams);
        $decodedClickedLinks = $mailService->getStatisticClickedLinks($idMessage, true, $queryParams);
        $decodedRecipients = $mailService->getEmailRecipients($idMessage, $queryParams);


        if ($idList) {
            $decodedHistory = $mailService->getEmailSendHistory($idList, $idMessage, $queryParams);
        }


        $counts = [
            'idmessage' => $idMessage,
            'opened' => $decodedOpened,
            'clicks' => $decodedClicks,
            'bounces' => $decodedBounces,
            'unsubscribed' => $decodedUnsubscribed,
        ];

        if (!empty($decodedClickedLinks->Items)) {
            $lists = $decodedClickedLinks->Items;
        }

        if (!empty($decodedHistory->Items)) {
            $listsHistory = $decodedHistory->Items;
        }

        if (!empty($decodedRecipients->Items)) {
            $listsRecipients = $decodedRecipients->Items;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $lists
        ]);
        $dataProviderHistory = new ArrayDataProvider([
            'allModels' => $listsHistory
        ]);

        $dataProviderRecipients = new ArrayDataProvider([
            'allModels' => $listsRecipients
        ]);

        $pagination = $this->setPagination($mailService, $decodedRecipients);


        return $this->render('email-statistics', [
            'dataProvider' => $dataProvider,
            'dataProviderHistory' => $dataProviderHistory,
            'dataProviderRecipients' => $dataProviderRecipients,
            'counts' => $counts,
            'pagination' => $pagination]);
    }

    /**
     * @param $idList
     * @param $idMessage
     * @return string
     */
    public function actionRecipientStatistics($idMessage, $idRecipient, $idList = null)
    {
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $lists = [];
        $listsHistory = [];
        $listsRecipients = [];
        $queryParams = \Yii::$app->request->queryParams;

        $decodedRecipient = $mailService->getRecipient($idRecipient);
        $decodedOpened = $mailService->getStatisticRecipientOpened($idRecipient, $idMessage, true, $queryParams);
        $decodedClicks = $mailService->getStatisticRecipientClicks($idRecipient, $idMessage, true, $queryParams);
        $decodedBounces = $mailService->getStatisticRecipientBounces($idRecipient, $idMessage, true, $queryParams);
        $decodedDeliveries = $mailService->getStatisticRecipientDeliveries($idRecipient, $idMessage, true, $queryParams);


        $recipient = [];
        if (!empty($decodedRecipient->Fields)) {
            $recipient['Email'] = $decodedRecipient->Email;
            foreach ($decodedRecipient->Fields as $field) {
                if (!empty($field->Value)) {
                    $recipient[$field->Description] = $field->Value;
                }
            }
        }

        $counts = [
            'idmessage' => $idMessage,
            'opened' => $decodedOpened,
            'clicks' => $decodedClicks,
            'bounces' => $decodedBounces,
            'deliveries' => $decodedDeliveries,
        ];

        return $this->render('recipient-statistics', ['counts' => $counts, 'recipient' => $recipient, 'idList' => $idList]);
    }


    /**
     * @param $idList
     */
    public function actionSubscribeAdmin($idList = null, $idGroup = null)
    {
        $model = new FormSubscribeNewsletter();
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();
        $dynamicFields = $mailService->getDynamicFields();
        $fields = [];
        if (!empty($dynamicFields)) {
            $fields = $dynamicFields->Items;
        }

        if ($model->load(\Yii::$app->request->post())) {
            $data = new \stdClass();
            $data->Email = $model->email;
            $data->Name = $model->name;
            $data->Fields = [];
            foreach ($model->fields as $key => $name) {
                $data->Fields[] = ['Id' => $key, 'Value' => $name];
            }
            $data = [$data];
            if($idGroup){
                $decode = $mailService->subscribeToGroup($idGroup, $data);
                return $this->redirect(['view-group', 'idGroup' => $idGroup]);

            } else {
                $decode = $mailService->subscribeToList($idList, $data);
                return $this->redirect(['subscribers-list', 'idList' => $idList]);
            }
//            pr($decode);die;
        }
        return $this->render('subscribe-admin', [
            'idList' => $idList,
            'idGroup' => $idGroup,
            'model' => $model,
            'fields' => $fields
        ]);

    }

    /**
     * @param $idRecipient
     * @return string
     */
    public function actionDetailUser($idRecipient){
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();

        $decodedRecipient = $mailService->getRecipient($idRecipient);
        $recipient = [];
        if (!empty($decodedRecipient->Fields)) {
            $recipient['Email'] = $decodedRecipient->Email;
            $recipient['Name'] = $decodedRecipient->Name;
            foreach ($decodedRecipient->Fields as $field) {
                if (!empty($field->Value)) {
                    $recipient[$field->Description] = $field->Value;
                }
            }
        }
        return $this->render('detail-recipient', [
            'recipient' => $recipient,

        ]);
    }

    /**
     * @param $idRecipient
     * @return string
     */
    public function actionUpdateDetailUser($idRecipient)
    {
        $model = new FormSubscribeNewsletter();
        $mailServiceClassname = $this->module->mail_service_driver;
        $mailService = new $mailServiceClassname();
        $dynamicFields = $mailService->getDynamicFields();
        $fields = [];
        if (!empty($dynamicFields)) {
            $fields = $dynamicFields->Items;
        }

        $decodedRecipient = $mailService->getRecipient($idRecipient);
        foreach ($decodedRecipient->Fields as $item){
            $model->fields[$item->Id] = $item->Value;
        }
        $model->email = $decodedRecipient->Email;
        $model->name = $decodedRecipient->Name;
        $model->privacy = true;

        if($model->load(\Yii::$app->request->post())){
            $data = new \stdClass();
            $data->Email = $model->email;
            $data->Name = $model->name;
            $data->Fields = [];
            foreach ($model->fields as $key => $name) {
                $data->Fields[] = ['Id' => $key, 'Value' => $name];
            }
            $data->IdRecipient = $idRecipient;
            $data = [$data];
            $decode = $mailService->updateRecipient($idRecipient, $data);
            pr($decode);die;
            return $this->redirect(['update-detail-user', 'idRecipient' => $idRecipient]);
        }

        return $this->render('subscribe-admin', [
            'isUpdate' => true,
            'idList' => null,
            'idGroup' => null,
            'model' => $model,
            'fields' => $fields
        ]);
    }


}