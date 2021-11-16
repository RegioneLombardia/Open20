<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\controllers 
 */
 
namespace backend\modules\campains\controllers;

use Yii;
use Exception;
use yii\helpers\VarDumper;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use Zend\Feed\Reader\Reader;
use yii\bootstrap4\ActiveForm;
use yii\filters\AccessControl;
use  yii\data\ActiveDataProvider;
use open20\amos\core\utilities\Email;
use common\components\MailupTransactionalSMS;
use open20\amos\emailmanager\AmosEmail;
use backend\modules\campains\utility\UIUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\TargetTagUtility;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\landings\models\PreferenceLanding;
use backend\modules\campains\utility\MailUpEmailUtility;
use Zend\Http\Client\Adapter\Exception\TimeoutException;
use backend\modules\campains\models\PreferenceCampainCopy;
use backend\modules\campains\utility\CampainsQueryUtility;
use backend\modules\campains\utility\ExternalContentUtility;
use backend\modules\campains\models\PreferenceCampainSection;
use backend\modules\campains\models\PreferenceMailupTemplate;
use backend\modules\campains\models\search\UserCampainSearch;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\models\PreferenceCampainContainer;
use backend\modules\campains\models\base\PreferenceCampainChannelMm as BasePreferenceCampainChannelMm;

/**
 * Class PreferenceCampainController 
 * This is the class for controller "PreferenceCampainController".
 * @package backend\modules\campains\controllers 
 */
class PreferenceCampainController extends \backend\modules\campains\controllers\base\PreferenceCampainController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'list-campain-detail',
                            'update-recipients',
                            'update-content-email',
                            'update-content-app',
                            'update-detail-email',
                            'update-detail-app',
                            'update-content-sms',
                            'update-landing-pages',
                            'update-sending-tracking',
                            'calculate-recipients-ajax',
                            'send-test-email',
                            'mailup-email-preview',
                            'mailup-email-content-preview',
                            'grafici-elena',
                            'grafici-mattia',
                            'test-sms',
                            'test',
                            'test-content',
                            'create-newsletter-section',
                            'create-newsletter-container',
                            'create-app-container',
                            'send-test-sms',
                            'get-values',
                            'render-search-ajax',
                            'mailup_email_content_preview',
                            'mailup-email-content-preview-iframe',
                            'get-souce-categories-ajax',
                            'get-souce-rss-ajax',
                            'copy-app-content-from-email',
                            'copy-preference-campain',
                        ],
                        'roles' => ['PC_ADMIN', 'PC_BASIC_USER']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    public function actionGraficiElena(){
        return $this->render('grafici-elena');
    }
    public function actionGraficiMattia(){
        return $this->render('grafici-mattia');
    }

    public function actionListCampainDetail()
    {
        if (isset($_POST['expandRowKey'])) {
            $model = PreferenceCampain::findOne($_POST['expandRowKey']);
            return $this->renderPartial('_campain_details', ['model' => $model] );
        } else {
            return '<div class="alert alert-danger">No data found</div>';
        }

    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function actionUpdateRecipients($id = null)
    {

        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        $this->model = $this->findModel($id);

        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            // start transaction 
            $transaction = \Yii::$app->db->beginTransaction();

            try {

               // VarDumper::dump( Yii::$app->request->post(), $depth = 4, $highlight = true);

                $this->model->search_params = serialize(Yii::$app->request->post());

                /**
                 * Update del PreferenceCampain, PreferenceCampainChannelMm, PreferenceCampainTagMm
                 */
                if( $this->model->save() ){

                    /**
                     * cancellazione dei tag PreferenceCampainTagMm associati alla PreferenceCampain
                     */
                    CampainsUtility::removeAllPreferenceCampainTag($this->model);

                    /**
                     * creazione dei tag PreferenceCampainTagMm associati alla PreferenceCampain
                     */
                    $tag = array_merge((array) Yii::$app->request->post('tag_cittadino'),
                                        (array) Yii::$app->request->post('tag_impresa'), 
                                        (array) Yii::$app->request->post('tag_ente_operatore'));

                    CampainsUtility::saveTag($tag, $this->model->id);

                    /**
                     * creazione dei canali PreferenceCampainChannelMm per la PreferenceCampain
                     */
                    if ( null == Yii::$app->request->post('channel') ){
                        throw new Exception('Scegliere almeno un canale');
                    }
                        
                    CampainsUtility::saveChannelUnique(Yii::$app->request->post('channel'), $this->model);

                }else{

                    // rollback transaction
                    $transaction->rollBack();
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La modifica della campagna non è andata a buon fine."));
                }


                /**
                 * controllo che la PreferenceCampain abbia almeno un canale selezionato
                 */
                if( false == CampainsUtility::existNewsletterChannel($this->model)
                    &&  false == CampainsUtility::existSmsChannel($this->model) 
                    &&  false == CampainsUtility::existAppChannel($this->model) ){

                    $transaction->rollBack();
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Non risulta selezionato nessun canale."));
                }
                
                // commit transaction
                $transaction->commit();

                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Elemento salvato correttamente'));
                return $this->redirect(['update-recipients', 'id' => $this->model->id]);       

            } catch (\Throwable $ex) { 

                $transaction->rollBack();
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La creazione della campagna non è andata a buon fine. " . $ex->getMessage()));
            }

        }

        /**
         * get data to pass to view
         */
        $profili = TargetTagUtility::getAllTargetTag();

        $tag = array();
        $tag['cittadino'] = TopicTagUtility::getAllTopicByTargetCode('pctarget_cittadino');
        $tag['impresa'] = TopicTagUtility::getAllTopicByTargetCode('pctarget_impresa');
        $tag['ente_operatore'] = TopicTagUtility::getAllTopicByTargetCode('pctarget_enteeoperatore');


        $modelSearch = new UserCampainSearch();
        $query_params = unserialize($this->model->search_params);
        $modelSearch->load($query_params);

        /**
         * render to view
         */
        return $this->render('update_recipients', [
            'model' => $this->model,
            'profili' => $profili,
            'tag' => $tag,
            'modelSearch' => $modelSearch,
        ]);
    }

    public function actionGetValues()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $field = $parents[0];
                $results = UserCampainSearch::getAttributesValues($field);
                foreach ($results as $id => $val) {
                    $out [] = ['id' => $id, 'name' => $val];
                }

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

       /**
     * @param $query_params
     * @return mixed
     */
    public function updateParams($query_params)
    {
        $queryParamsToUpdate = $query_params;
        $queryParamsToUpdate['UserEventSearch']['field'] = null;
        $queryParamsToUpdate['UserEventSearch']['includeExclude'] = null;
        $queryParamsToUpdate['UserEventSearch']['value'] = null;
        $i = 1;
        foreach ($query_params['UserEventSearch']['field'] as $key => $field) {
            $queryParamsToUpdate['UserEventSearch']['field'][$i] = $field;
            $queryParamsToUpdate['UserEventSearch']['includeExclude'][$i] = $query_params['UserEventSearch']['includeExcluse'];
            $queryParamsToUpdate['UserEventSearch']['value'][$i][$i] = $query_params['UserEventSearch']['value'][$key];
            $i++;
        }
        return $queryParamsToUpdate;
    }

        /**
     * @return string
     */
    public function actionRenderSearchAjax()
    {
        $post = \Yii::$app->request->post();

        parse_str(urldecode($post['data']), $query_params);
        $modelSearch = new UserCampainSearch();
        $form = new ActiveForm();

        $queryParamsToUpdate = $this->updateParams($query_params);
        //pr($query_params);
        $modelSearch->load($queryParamsToUpdate);
        $count = count($modelSearch->field);
        if ($post['plus'] == 1) {
            $count++;
        }

        return $this->renderAjax('../wizard/_parts/_search_users', ['modelSearch' => $modelSearch, 'form' => $form, 'count' => $count]);
    }

    
    /**
     * Method to update email details
     *
     * @param int | $id
     * @return redirect to view
     */
    public function actionUpdateDetailEmail($id = null){
        
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
       
        // estrazione del canale PreferenceCampainChannelMm
        $this->model = PreferenceCampainChannelMm::findOne($id);
        $this->model->setScenario(PreferenceCampainChannelMm::SCENARIO_NEWSLETTER);
        
        // cotnrollo se esiste il canale PreferenceCampainChannelMm
        if( null == $this->model || null == $id ){

            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Il canale non è stata trovato."));
            return $this->redirect('campains/preference-campain/index');  
        }

        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model->preferenceCampain);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Elemento salvato correttamente'));
                // return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Elemento non salvato, controlla i dati inseriti'));
            }
        }

        $template = ArrayHelper::map(PreferenceMailupTemplate::find()->all(), 'mailup_template_id', 'title');

        return $this->render('update_detail_email', [
            'model' => $this->model,
            'template' => $template
        ]);
    }

    /**
     * Method to update email details
     *
     * @param int | $id
     * @return redirect to view
     */
    public function actionUpdateDetailApp($id = null){
        
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
       
        // estrazione del canale PreferenceCampainChannelMm
        $this->model = PreferenceCampainChannelMm::findOne($id);
        $this->model->setScenario(PreferenceCampainChannelMm::SCENARIO_APP);
        
        // cotnrollo se esiste il canale PreferenceCampainChannelMm
        if( null == $this->model || null == $id ){

            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Il canale non è stata trovato."));
            return $this->redirect('campains/preference-campain/index');  
        }

        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model->preferenceCampain);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Elemento salvato correttamente'));
                // return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Elemento non salvato, controlla i dati inseriti'));
            }
        }

        // $template = ArrayHelper::map(PreferenceMailupTemplate::find()->all(), 'mailup_template_id', 'title');

        return $this->render('update_detail_app', [
            'model' => $this->model,
            // 'template' => $template
        ]);
    }


    /**
     * Metodo per la gestione dei contenuti per la newsletter
     *
     * @param [type] $id
     * @return redirect to view
     */
    public function actionUpdateContentEmail($id = null)
    {
        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model);

        // Action che lavora sul model 
        /** @var PreferenceCampainChannelMm $model */
        $model = CampainsUtility::campainGetNewsletterModel($this->model);

        if ($model->load(Yii::$app->request->post())) {

            // TODO al save corretto... navigazione condizionata se esiste SMS channel selezionato allora step SMS altrimenti ultimo step SUMMARY

            if (CampainsUtility::campainHaveSmsChannel($this->model)) {
                return $this->redirect(['sms-data', 'id' => $this->model->id]);
            } 
            
            return $this->redirect(['summary', 'id' => $this->model->id]);

        }

        $myTemplate = PreferenceMailupTemplate::findOne(['mailup_template_id' => $model->template_id]);
        $colorChoiches = json_decode($myTemplate->colors_choice, true);
        $aspectRatioChoices = json_decode($myTemplate->aspect_ratio_choices, true);
        $aspectRatio = $myTemplate->aspect_ratio;

        /**
         * data view
         */
        return $this->render('newsletter_content', [
            'model' => $this->model,
            'preference_campain_section' => new PreferenceCampainSection,
            'preference_campain_container' => new PreferenceCampainContainer,
            'newsletter_content' => $this->getNewsletterContent($model),
            'model_preference_campain_channel_mm' => $model,
            'newsletter_section_color' => $this->getColorChoices($colorChoiches),
            'newsletter_section_title' => $this->getTitleChoices($colorChoiches),
            'colorChoiches' => $colorChoiches,
            'aspectRatioChoices' => $aspectRatioChoices,
            'aspectRatio' => !empty($aspectRatio)? $aspectRatio: '1.7777777777778',
            'type_content_align' => ['center' => 'Nessun allineamento (al centro)', 'left' => 'Allinea immagine a sinistra', 'right' => 'Allinea immagine a destra'],
            'external_source' => ExternalContentUtility::getSources(),
        ]);
    }

    /**
     * Metodo per la gestione dei contenuti per la newsletter
     *
     * @param [type] $id
     * @return redirect to view
     */
    public function actionUpdateContentApp($id = null)
    {
        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model);

        // Action che lavora sul model 
        /** @var PreferenceCampainChannelMm $model */
        $model = CampainsUtility::campainGetAppModel($this->model);

        if(PreferenceCampainSection::find()->andWhere(['preference_campain_channel_mm_id' => $model->id])->count() < 1) {
            $section = new PreferenceCampainSection();
            $section->preference_campain_channel_mm_id = $model->id;
            $section->title = '';
            $section->color = '#ffffff00';
            $section->save(false);
        }

        if ($model->load(Yii::$app->request->post())) {

            // if (CampainsUtility::campainHaveSmsChannel($this->model)) {
            //     return $this->redirect(['sms-data', 'id' => $this->model->id]);
            // } 
            
            // return $this->redirect(['summary', 'id' => $this->model->id]);

        }

        $myTemplate = PreferenceMailupTemplate::findOne(['mailup_template_id' => $model->template_id]);
        $colorChoiches = json_decode($myTemplate->colors_choice, true);
        $aspectRatioChoices = json_decode($myTemplate->aspect_ratio_choices, true);
        $aspectRatio = $myTemplate->aspect_ratio;

        // VarDumper::dump( $colorChoiches, $depth = 10, $highlight = true);

        /**
         * data view
         */
        return $this->render('app_content', [
            'model' => $this->model,
            'preference_campain_section' => $section,
            'preference_campain_container' => new PreferenceCampainContainer,
            'newsletter_content' => $this->getNewsletterContent($model),
            'model_preference_campain_channel_mm' => $model,
            'newsletter_section_color' => $this->getColorChoices($colorChoiches),
            'aspectRatioChoices' => $aspectRatioChoices,
            'aspectRatio' => !empty($aspectRatio)? $aspectRatio: '1.7777777777778',
            'type_content_align' => ['center' => 'Nessun allineamento (al centro)', 'left' => 'Allinea immagine a sinistra', 'right' => 'Allinea immagine a destra'],
            'external_source' => ExternalContentUtility::getSources(),
        ]);
    }

    public function actionGetSouceCategoriesAjax(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        

        if (isset(Yii::$app->request->post()['depdrop_all_params']['external_source-id'])) {
            $id = Yii::$app->request->post()['depdrop_all_params']['external_source-id'];
            if (!empty($id)) {

                $flagMatchItems = null;
                if (isset(Yii::$app->request->post()['depdrop_all_params']['match-preference-checkbox-val-id'])) {
                    $val = Yii::$app->request->post()['depdrop_all_params']['match-preference-checkbox-val-id'];

                    // VarDumper::dump( $val, $depth = 10, $highlight = true); die;
                    if ($val == 0) {
                        $flagMatchItems = false;
                    } else {
                        $flagMatchItems = true;
                    }
                }

                /** @var PreferenceCampain $campain */
                $campain = null;
                if (isset(Yii::$app->request->post()['depdrop_all_params']['campain_id-id'])) {
                    $val = Yii::$app->request->post()['depdrop_all_params']['campain_id-id'];
                    $campain = PreferenceCampain::findOne(['id' => $val]);
                }

                $numericItemIds = null;
                if (!empty($campain) && $flagMatchItems) {
                    $tagsMm = $campain->preferenceCampainTagMms;
                    foreach ($tagsMm as $tagMm) {
                        $tag = $tagMm->tag;
                        $pieces = explode("_", $tag->codice);
                        if (isset($pieces[2])){
                            $numericItemIds[] = intval($pieces[2]);
                        }
                    }
                }

                $source = ExternalContentUtility::getSourceById($id);
                $resources = $source['resources'];
                if (!empty($numericItemIds)) {
                    $newResources = [];
                    foreach ($resources as $resource) {
                        if (count(array_intersect($resource['link_context'], $numericItemIds)) > 0) {
                            $newResources[] = $resource;
                        }
                    }
                    $resources = $newResources;
                }
                
                $toret = [];
                foreach (ArrayHelper::map($resources, 'id', 'name') as $id => $name) {
                    $toret[] = [
                        'id' => $id,
                        'name' => $name];
                } 

                return ['output' => $toret, 'selected'=> ''];
            }
        }
        return null;
    }

    public function actionGetSouceRssAjax($page = 1)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $pageSize = 5;
        try {
            $source = Yii::$app->request->post('source');
            $category = Yii::$app->request->post('category');
            if (!empty($source) && !empty($category)) {

                $url = ExternalContentUtility::getUrlByIdAndResource($source, $category);
                $feed = Reader::import($url);
                $data = array(
                    'status'       => 'ok',
                    'title'        => $feed->getTitle(),
                    'link'         => $feed->getLink(),
                    'dateModified' => $feed->getDateModified(),
                    'description'  => $feed->getDescription(),
                    'language'     => $feed->getLanguage(),
                    'entries'      => array(),
                    'numTotalFeed' => count($feed),
                    'pagSize'      => $pageSize,
                    'page'      => $page,
                );
                $startPageCount = (($page - 1) * $pageSize);
                $i = 0;
                foreach ($feed as $entry) {
                    if (($i >= $startPageCount) && ($i < ($startPageCount + $pageSize))) {
                        $edata = array(
                            'title'        => $entry->getTitle(),
                            'description'  => $entry->getDescription(),
                            'dateModified' => $entry->getDateModified(),
                            'authors'      => $entry->getAuthors(),
                            'link'         => $entry->getLink(),
                            'content'      => $entry->getContent(),
                            'dateCreated'  => $entry->getDateCreated(),
                            'enclosure'    => $entry->getEnclosure(),
                        );
                        $data['entries'][] = $edata;
                    }

                    $i++;
                }
                return $data;
            }
        } catch (TimeoutException $e) {
            $data = array(
                'status'       => 'error',
                'message'       => 'La fonte esterna non ha risposto, seleziona un\'altra fonte per vedere se il problema persiste...',
            );
            return $data;
        } catch (Exception $exc) {
            $data = array(
                'status'       => 'error',
                'message'      => 'Errore: ' .$exc->getMessage(),
            );
            return $data;
        }

        $data = array(
            'status'       => 'error',
            'message'       => 'Errore imprevisto...',
        );
        return $data;
    }


    /**
     * Metodo per l'estrazione dei dati delle section, container associati alla campagna
     *
     * @param PreferenceCampainChannelMm | $preference_campain_channel_mm
     * @return array | $newsletter_content
     */
    public function getNewsletterContent($preference_campain_channel_mm){

        // array per la struttura dei dati 
        $newsletter_content = array();

        // estrazione delle section associate alla PreferenceCampainChannelMM ordiate per il hcamp order
        $preference_campain_sections = $preference_campain_channel_mm->getPreferenceCampainSections()
                                        ->orderBy(['order' => SORT_ASC])->all();

        for( $i = 0; $i < count($preference_campain_sections); $i++ ){

            $newsletter_content[$i]['section'] = $preference_campain_sections[$i];

            $section_container = PreferenceCampainContainer::find()->where([
                'preference_campain_section_id' => $preference_campain_sections[$i]->id,
                'deleted_at' => null
            ])->orderBy(['order' => SORT_ASC])->all();

            foreach ($section_container as $container) {
                $newsletter_content[$i]['container'][] = $container;
            }
        }

        return $newsletter_content;
    }


    /**
     * Metodo per la creazione di una sezione PreferenceCampainSection associata alla campagna PreferenceCampain
     *
     * @param int | $id => PreferenceCampain
     * @return redirect to view
     */
    public function actionCreateNewsletterSection($id = null){

        // estrazione della campagna PreferenceCampain
        $this->model = PreferenceCampain::findOne($id);

        // controllo il caso in cui la campagna no sia stata trovata
        if( null == $this->model ){
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La campagna non è stata trovata."));
            return $this->redirect('campains/preference-campain/index');  
        }

        // $this->model = PreferenceCampain::findOne($id);
        // if (empty($this->model)){
        //     throw new HttpException("Campain not found");
        // }

        // post request form data
        $post_request_data = Yii::$app->request->post('PreferenceCampainSection');

        // estrazione della sezione PreferenceCampainSection associata alla campagna 
        if( null == $model = PreferenceCampainSection::findOne($post_request_data['id']) ){
            $model = new PreferenceCampainSection();
        }

     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if( !$model->save() ){
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La creazione della sezione non è andata a buon fine."));
            }

            Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Il salvataggio della sezione è andata a buon fine.'));
        } else {

            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Il caricamento dei dati non è andato a buon fine."));
        }

        // redirect back con l'id PreferenceCampain dal get in get
        return $this->redirect(['update-content-email', 'id' => $id]);
    }

    
    /**
     * Metodo per la creazione di un contenuto PreferenceCampainContainer da associare alla sezione PreferenceCampainSection
     *
     * @param int | $id
     * @return void
     */
    public function actionCreateNewsletterContainer($id = null){

        // controllo il caso in cui la campagna PreferenceCampain no sia stata trovata
        if( null == $this->model = PreferenceCampain::findOne($id) ){
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La campagna non è stata trovata."));
            return $this->redirect('campains/preference-campain/index');  
        }
        
        // POST DATA
        $post_request_data = Yii::$app->request->post('PreferenceCampainContainer');

        // controllo l'esistenza del record contenuto 
        if( null == $model = PreferenceCampainContainer::findOne($post_request_data['id']) ){
            $model = new PreferenceCampainContainer(['scenario' => PreferenceCampainContainer::SCENARIO_DEFAULT]);
        }


        if ( $model->load(Yii::$app->request->post()) ) {

            // set scenario PreferenceCampainContainer a seconda del type
            if( null != $model->preference_campain_container_type_id ){
                $externalImageUrl = null;
                // set dei scenari a seconda del tipo di contenitore
                if( $model->preference_campain_container_type_id == 
                    CampainsUtility::preferenceCampainContainerTypeByName('Separatore')->id ){
                    
                    $model->scenario = PreferenceCampainContainer::SCENARIO_SEPARATORE;

                }elseif( $model->preference_campain_container_type_id == 
                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto')->id ){

                        $model->scenario = PreferenceCampainContainer::SCENARIO_CONTENUTO;

                        // set null preference_landing_id se in post essite content_news_link
                        if( isset($post_request_data['content_news_link']) ){
                            $model->preference_landing_id = null;
                        }

                        // set null content_news_link se in post essite preference_landing_id
                        if( isset($post_request_data['preference_landing_id']) ){
                            $model->content_news_link = null;
                        }

                }elseif( $model->preference_campain_container_type_id == 
                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto Esterno')->id ){

                    $model->scenario = PreferenceCampainContainer::SCENARIO_CONTENUTO_ESTERNO;

                    /**
                     * il set dei parametri per i contenuti esterni va fatto a mano
                     * in quanto i dati vengono inviati in unioni tramite il value della radio button option checked
                     * 
                     * Mappatura post content_to_insert
                     * [0] => content_title (view: title)
                     * [1] => content_news_link (view: link)
                     * [2] => content_text (view: description)
                     * [3] => (view: pubDate )  ??? TODO campo per la data della news ? per il momento lo lascio senza alcun uso
                     * [4] => externalImageUrl l'immagine
                     * 
                     */

                    if( $model->isNewRecord){
                        $content_to_insert = explode("###", $post_request_data['content_to_insert']);

                        $model->content_title = $content_to_insert[0];
                        $model->content_news_link = $content_to_insert[1];
                        $model->content_text = /*$content_to_insert[3]." ".*/$content_to_insert[2];
                        $externalImageUrl = isset($content_to_insert[4])? $content_to_insert[4]: null;
                    }

                }

                if($model->validate() && $model->save()){

                    $importImageFlag = true;
                    if (!empty($externalImageUrl)) {
                        $importImageFlag = $this->importImageUrl($model, $externalImageUrl);
                    }
                    
                    if($importImageFlag) {
                        \Yii::$app->getSession()->addFlash('success', \Yii::t('app', 'Il contenuto è stato salvato correttamente.'));
                    } else {
                        \Yii::$app->getSession()->addFlash('warning', \Yii::t('app', 'Il contenuto è stato salvato. Tuttavia, non è stato possibile allegare l\'immagine alla notizia. Questa, sarà da inserire manualmente modificando il contenuto.'));
                    }

                }else{

                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', 'Errore! Non è stato possibile salvare il contenuto.'));
                }
            }

        } else {

            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Il caricamento dei dati non è andato a buon fine."));
        }

        // redirect back con l'id PreferenceCampain dal get in get
        return $this->redirect(['update-content-email', 'id' => $id]);
    }

    /**
     * Metodo per la creazione di un contenuto PreferenceCampainContainer da associare alla sezione PreferenceCampainSection
     *
     * @param int | $id
     * @return void
     */
    public function actionCreateAppContainer($id = null){

        // controllo il caso in cui la campagna PreferenceCampain no sia stata trovata
        if( null == $this->model = PreferenceCampain::findOne($id) ){
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La campagna non è stata trovata."));
            return $this->redirect('campains/preference-campain/index');  
        }
        
        // POST DATA
        $post_request_data = Yii::$app->request->post('PreferenceCampainContainer');

        // controllo l'esistenza del record contenuto 
        if( null == $model = PreferenceCampainContainer::findOne($post_request_data['id']) ){
            $model = new PreferenceCampainContainer(['scenario' => PreferenceCampainContainer::SCENARIO_DEFAULT]);
        }


        if ( $model->load(Yii::$app->request->post()) ) {

            // set scenario PreferenceCampainContainer a seconda del type
            if( null != $model->preference_campain_container_type_id ){
                $externalImageUrl = null;
                // set dei scenari a seconda del tipo di contenitore
                if( $model->preference_campain_container_type_id == 
                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto')->id ){

                        $model->scenario = PreferenceCampainContainer::SCENARIO_CONTENUTO;

                        // set null preference_landing_id se in post essite content_news_link
                        if( isset($post_request_data['content_news_link']) ){
                            $model->preference_landing_id = null;
                        }

                        // set null content_news_link se in post essite preference_landing_id
                        if( isset($post_request_data['preference_landing_id']) ){
                            $model->content_news_link = null;
                        }

                }elseif( $model->preference_campain_container_type_id == 
                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto Esterno')->id ){

                    $model->scenario = PreferenceCampainContainer::SCENARIO_CONTENUTO_ESTERNO;

                    /**
                     * il set dei parametri per i contenuti esterni va fatto a mano
                     * in quanto i dati vengono inviati in unioni tramite il value della radio button option checked
                     * 
                     * Mappatura post content_to_insert
                     * [0] => content_title (view: title)
                     * [1] => content_news_link (view: link)
                     * [2] => content_text (view: description)
                     * [3] => (view: pubDate )  ??? TODO campo per la data della news ? per il momento lo lascio senza alcun uso
                     * [4] => externalImageUrl l'immagine
                     * 
                     */

                    if( $model->isNewRecord){
                        $content_to_insert = explode("###", $post_request_data['content_to_insert']);

                        $model->content_title = $content_to_insert[0];
                        $model->content_news_link = $content_to_insert[1];
                        $model->content_text = /*$content_to_insert[3]." ".*/$content_to_insert[2];
                        $externalImageUrl = isset($content_to_insert[4])? $content_to_insert[4]: null;
                    }

                }

                if($model->validate() && $model->save()){

                    $importImageFlag = true;
                    if (!empty($externalImageUrl)) {
                        $importImageFlag = $this->importImageUrl($model, $externalImageUrl);
                    }
                    
                    if($importImageFlag) {
                        \Yii::$app->getSession()->addFlash('success', \Yii::t('app', 'Il contenuto è stato salvato correttamente.'));
                    } else {
                        \Yii::$app->getSession()->addFlash('warning', \Yii::t('app', 'Il contenuto è stato salvato. Tuttavia, non è stato possibile allegare l\'immagine alla notizia. Questa, sarà da inserire manualmente modificando il contenuto.'));
                    }

                }else{

                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', 'Errore! Non è stato possibile salvare il contenuto.'));
                }
            }

        } else {

            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Il caricamento dei dati non è andato a buon fine."));
        }

        // redirect back con l'id PreferenceCampain dal get in get
        return $this->redirect(['update-content-app', 'id' => $id]);
    }


    /**
     * @param PreferenceCampainContainer $model
     * @param string $externalImageUrl
     * @return bool
     */
    private function importImageUrl($model, $externalImageUrl)
    {
        $baseDir = Yii::getAlias('@common/uploads/temp');
        if (!is_dir($baseDir)) {
            return false;
        }
        $urlImageName = basename($externalImageUrl);
        $image = $baseDir . DIRECTORY_SEPARATOR . uniqid('pc_external_image') . '_' . $urlImageName;
        
        $ret = file_put_contents($image, file_get_contents($externalImageUrl));
        if ($ret === false) {
            return false;
        }

        $moduleAttach = \Yii::$app->getModule('attachments');
        if (!empty($moduleAttach) && method_exists($moduleAttach, 'attachFile')) {
            try {
                $ret = $moduleAttach->attachFile($image, $model, 'contentImage');
            } catch(Exception $e) {
                return false;
            }

            if ($ret) {
                return true;
            }
            
        } else {
            return false;
        }

        return false;
    }


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function actionUpdateContentSms($id)
    {
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        // $this->model = $this->findModel($id);
        $this->model = PreferenceCampainChannelMm::findOne($id);
        $this->model->setScenario(PreferenceCampainChannelMm::SCENARIO_SMS);

        // get Sidebar Campains passing PrefrenceCampain
        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model->preferenceCampain);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Elemento salvato correttamente'));
                return $this->redirect(['update-content-sms', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Elemento non salvato, controlla i dati inseriti'));
            }
        }

        return $this->render('update_content_sms', [
            'model' => $this->model,
        ]);
    }


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function actionUpdateLandingPages($id)
    {
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        /** @var PreferenceCampain $this->model */
        $this->model = $this->findModel($id);

        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Elemento salvato correttamente'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Elemento non salvato, controlla i dati inseriti'));
            }
        }


        $ids = [];
        /** @var PreferenceCampainChannelMm $channel */
        foreach ($this->model->preferenceCampainChannelMms as $channel) {
            if (!empty($channel)) {
                /** @var PreferenceCampainSection $section */
                foreach ($channel->preferenceCampainSections as $section) {
                    if (!empty($section)) {
                        /** @var  PreferenceCampainContainer $container */
                        foreach ($section->preferenceCampainContainers as $container) {
                            if (!empty($container->preference_landing_id)) {
                                $ids[] = $container->preference_landing_id;
                            }
                        }
                    }
                }
            }
        }


        $dataProvider = new ActiveDataProvider([
            'query' => PreferenceLanding::find()->andWhere(['id' => $ids]),
        ]);


        return $this->render('update_landing_pages', [
            'dataProvider' => $dataProvider,
            'model' => $this->model,
        ]);
    }


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function actionUpdateSendingTracking($id = null)
    {
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        $this->model = $this->findModel($id);

        \Yii::$app->getView()->params['bi-menu-sidebar'] = UIUtility::getSidebarCampains($this->model);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Elemento salvato correttamente'));
                return $this->redirect(['update-sending-tracking', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Elemento non salvato, controlla i dati inseriti'));
            }
        }

        if (isset(Yii::$app->request->post('PreferenceCampainChannelMm')['id'])) {
            
            $channel = PreferenceCampainChannelMm::findOne(Yii::$app->request->post('PreferenceCampainChannelMm')['id']);
            $channel->sendToStatus(Yii::$app->request->post('PreferenceCampainChannelMm')['status']);

            // VarDumper::dump( Yii::$app->request->post('PreferenceCampainChannelMm'), $depth = 10, $highlight = true); 
            // VarDumper::dump( $channel->errors, $depth = 10, $highlight = true); 
            
            // die;

            if ($channel->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Canale aggiornato correttamente'));
                return $this->redirect(['update-sending-tracking', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Elemento non salvato... è avvenuto un errore... '));
            }
        }

        return $this->render('update_sending_tracking', [
            'model' => $this->model,
        ]);
    }
    

    public function actionCalculateRecipientsAjax(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $toret = [
            'status' => 'ko',
            'data' => ''
        ];
        try {
            $allTags = Yii::$app->request->post('tags');
            $allChannels = Yii::$app->request->post('channels');

            $query_params = null;

            parse_str(urldecode(Yii::$app->request->post('form')), $query_params);
            $modelSearch = new UserCampainSearch();
            $queryParamsToUpdate = $this->updateParams($query_params);
            $modelSearch->load($queryParamsToUpdate);
            
            $allTags = is_null($allTags)? []: $allTags;
   
            $data = [];
            foreach ($allChannels as $channelId) {
                $data[$channelId] = CampainsQueryUtility::getQueryRecipientsByChannelAndTags($channelId, $allTags, $modelSearch)->count();
            }

            $toret = [
                'status' => 'ok',
                'data' => $data
            ];
            
        } catch (\Exception $e) {
            $toret['data'] = $e->getMessage();
            return $toret;
        }
        return $toret;
    }


    // public function actionTestSms()
    // {

    //     $mailupSms = new MailupTransactionalSMS();
    //     $mailupSms->setContent('Ecco [Campo] un test per linvio di un SMS');
    //     $mailupSms->setRecipient('347150217');
    //     $mailupSms->setCampaignCode('sessioni-di-test');
    //     $mailupSms->setDynamicFields([
    //         [
    //             'N' => 'Campo',
    //             'V' => 'Michele Zucchini',
    //         ]
    //     ]);
    //     $ret = $mailupSms->sendSms();
    //     VarDumper::dump($ret, 2, true);
    // }


    public function actionMailupEmailPreview($id)
    {
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        

        $moduleNewsletter = \Yii::$app->getModule('newsletter');
        $mailServiceClassname = $moduleNewsletter->mail_service_driver;
        $mailService = new $mailServiceClassname();
        

        $listId = null;

        if (isset(Yii::$app->params['mailup']['newsletter']['list-id'])) {
            $listId = Yii::$app->params['mailup']['newsletter']['list-id'];
        }

        $template = PreferenceMailupTemplate::findOne(['mailup_template_id' => $id]);

        $email = $mailService->getEmail($listId, $template->mailup_template_preview_id);

        $content = $email->Content;

        return $this->renderPartial('mailup_email_preview', [
            'content' => $content,
        ]);
    }

    public function actionMailupEmailContentPreviewIframe($id)
    {
        return $this->renderPartial('mailup_email_content_preview', [
            'id' => $id,
        ]);
    }

    public function actionMailupEmailContentPreview($id)
    {
        // $this->setUpLayout('bootstrap-italia-layout-with-sidebar');

        // VarDumper::dump( $id, $depth = 10, $highlight = true); die;
        
        $channel = BasePreferenceCampainChannelMm::findOne($id);

        $moduleNewsletter = \Yii::$app->getModule('newsletter');
        $mailServiceClassname = $moduleNewsletter->mail_service_driver;
        $mailService = new $mailServiceClassname();
        

        $listId = null;

        if (isset(Yii::$app->params['mailup']['newsletter']['list-id'])) {
            $listId = Yii::$app->params['mailup']['newsletter']['list-id'];
        }

        $newContent = MailUpEmailUtility::generateNewsletterContent($channel);

        if (empty($channel->template_id)) {
            throw new Exception('Template email inesistente!!! impossibile spedire la comunicazione');
        }
        
        $emailTemplate = $mailService->getEmail($listId, $channel->template_id);

        $htmlWithParams = MailUpEmailUtility::getDynamicContentMailup($emailTemplate, $newContent, $channel);

        return $this->renderPartial('mailup_email_preview', [
            'content' => $htmlWithParams,
        ]);
    }

    /**
     * 
     */
    public function actionSendTestEmail(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $toret = [
            'status' => 'ko',
            'data' => ''
        ];
        try {
            $channelId = \Yii::$app->request->post('channelId');
            $toEmail = \Yii::$app->request->post('email');
            //michele.zucchini@open20.it
            // VarDumper::dump( $channelId, $depth = 10, $highlight = true);
            // VarDumper::dump( $toEmail, $depth = 10, $highlight = true);

            if (empty($channelId)) {
                throw new Exception('Canale non definito');
            }

            if (empty($toEmail)) {
                throw new Exception('Indirizzo email non definito');
            }

            $channel = PreferenceCampainChannelMm::findOne($channelId);
            $content = MailUpEmailUtility::generateNewsletterContent($channel);
            // per fare andare i link all'invio della mail di esempio...
            // http://[track]/
            $content = str_replace('http://[track]/', '', $content);

            $newsletterModule = \Yii::$app->getModule('newsletter');
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $listId = null;
            if (isset(Yii::$app->params['mailup']['newsletter']['list-id'])) {
                $listId = Yii::$app->params['mailup']['newsletter']['list-id'];
            }
            $emailTemplate = $mailService->getEmail($listId, $channel->template_id);
            $htmlWithParams = MailUpEmailUtility::getDynamicContentMailup($emailTemplate,  $content, $channel);

            $mailModule = Yii::$app->getModule(AmosEmail::getModuleName());
            $mailModule->defaultLayout = "layout_without_header_and_footer";
            $ok = Email::sendMail(
                Yii::$app->params['supportEmail'],
                $toEmail,
                "=?utf-8?B?" . base64_encode($channel->subject) . "?=",
                $htmlWithParams
            );

            if ($ok) {
                return true;
            } else {
                throw new Exception('Errore nell\'invio email');
            }

            $toret = [
                'status' => 'ok',
                'data' => ''
            ];
            
        } catch (\Exception $e) {
            $toret['data'] = $e->getMessage();
            return $toret;
        }
        return $toret;
    }

    public function actionTestContent()
    {
die;

        $var =      [
            '#003354' => [
                'label' => 'Blu',
                'txtcolor' => '#003354',
                'bgcolor' => '#003354',
            ],
            '#a73541' => [
                'label' => 'Rosso',
                'txtcolor' => '#a73541',
                'bgcolor' => '#a73541',
            ],
            '#297a38' => [
                'label' => 'Verde',
                'txtcolor' => '#297a38',
                'bgcolor' => '#297a38',
            ],
            '#ffffff00' => [
                'label' => 'Trasparente',
                'txtcolor' => '#000000',
                'bgcolor' => '#ffffff00',
            ]
        ];
//         $newsletterModule = \Yii::$app->getModule('newsletter');
//             $mailServiceClassname = $newsletterModule->mail_service_driver;
//             $mailService = new $mailServiceClassname();

//          $emailTemplate = $mailService->getEmail(1, 1094);
// //         // VarDumper::dump( $emailTemplate, $depth = 3, $highlight = true);


//         echo dirname(__FILE__) . "/content.txt";

//         $myfile = fopen(dirname(__FILE__) . "/content.txt", "w") or die("Unable to open file!");

// fwrite($myfile, $emailTemplate->Content);
// fclose($myfile);
//         die;


        //  $test = '{"#003354":{"label":"Blu","txtcolor":"#003354","bgcolor":"#003354"},"#a73541":{"label":"Rosso","txtcolor":"#a73541","bgcolor":"#a73541"},"#297a38":{"label":"Verde","txtcolor":"#297a38","bgcolor":"#297a38"},"#ffffff00":{"label":"Trasparente","txtcolor":"#000000","bgcolor":"#ffffff00"}}';
        //  echo VarDumper::dumpAsString( json_decode($test, true),3,true); die;
        $color = [
            [
                'bef_rosso' => [
                    'label' => 'Bandi e fondi (Rosso)',
                    'section_icon' => 'https://a4f6d9.emailsp.com/assets/1/icon-bandi.png',
                    'default_text' => 'Bandi e fondi',
                    'txtcolor' => '#a73541',
                    'bgcolor' => '#a73541',
                ],
                'bef_verde' => [
                    'label' => 'Bandi e fondi (verde)',
                    'section_icon' => 'https://a4f6d9.emailsp.com/assets/1/icon-bandi.png',
                    'default_text' => 'Bandi e fondi',
                    'txtcolor' => '#297a38',
                    'bgcolor' => '#297a38',
                ],
                'territorio_blu' => [
                    'label' => 'Territorio (blu)',
                    'section_icon' => 'https://a4f6d9.emailsp.com/assets/1/icon-territorio.png',
                    'default_text' => 'Territorio',
                    'txtcolor' => '#003354',
                    'bgcolor' => '#003354',
                ],
                '#003354' => [
                    'label' => 'Generico (Blu)',
                    'section_icon' => 'https://a4f6d9.emailsp.com/assets/1/arrow-white.png',
                    'default_text' => '',
                    'txtcolor' => '#003354',
                    'bgcolor' => '#003354',
                ],
                '#a73541' => [
                    'label' => 'Generico (Rosso)',
                    'section_icon' => 'https://a4f6d9.emailsp.com/assets/1/arrow-white.png',
                    'default_text' => '',
                    'txtcolor' => '#a73541',
                    'bgcolor' => '#a73541',
                ],
                '#297a38' => [
                    'label' => 'Generico (Verde)',
                    'section_icon' => 'https://a4f6d9.emailsp.com/assets/1/arrow-white.png',
                    'default_text' => '',
                    'txtcolor' => '#297a38',
                    'bgcolor' => '#297a38',
                ],
                '#ffffff00' => [
                    'label' => 'Trasparente',
                    'section_icon' => 'https://a4f6d9.emailsp.com/assets/1/arrow-white.png',
                    'default_text' => '',
                    'txtcolor' => '#1C2833',
                    'bgcolor' => '#ffffff00',
                ]

            ]
            // '#ffffff00' => [
            //     'label' => 'Trasparente',
            //     'txtcolor' => '#555555',
            //     'bgcolor' => '#ffffff00',
            // ]
            ];
            echo '<textarea>';  echo json_encode($color,1); echo '</textarea>';
        /*
      {"#003354":{"label":"Blu","txtcolor":"#003354","bgcolor":"#003354"},"#a73541":{"label":"Rosso","txtcolor":"#a73541","bgcolor":"#a73541"},"#297a38":{"label":"Verde","txtcolor":"#297a38","bgcolor":"#297a38"},"#ffffff00":{"label":"Trasparente","txtcolor":"#000000","bgcolor":"#ffffff00"}}  
        

        */
            
            die;

        // echo $htmlWithParams;
    }


    /**
     * 
     */
    public function actionSendTestSms(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $toret = [
            'status' => 'ko',
            'data' => ''
        ];
        try {
            $phone = \Yii::$app->request->post('phone');
            $text = \Yii::$app->request->post('text');
            //michele.zucchini@open20.it
            // VarDumper::dump( $phone, $depth = 10, $highlight = true);
            // VarDumper::dump( $text, $depth = 10, $highlight = true);

            if (empty($phone)) {
                throw new Exception('Numero di telefono non definito');
            }

            if (empty($text)) {
                throw new Exception('Corpo del messaggio non definito');
            }

            $mailupSms = new MailupTransactionalSMS();
            $mailupSms->setContent($text);
            $mailupSms->setRecipient($phone);
            $mailupSms->setCampaignCode('test-inivio');
            $mailupSms->setDynamicFields([]);
            $ret = $mailupSms->sendSms();
            \Yii::$app->getSession()->addFlash('success', \Yii::t('campain', "messaggio inviato correttamente al numero: $phone"));
            \Yii::$app->getSession()['preference-sms-text'] = $text;
            $toret = 'true';
            
        } catch (\Exception $e) {
            $toret['data'] = $e->getMessage();
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('campain', "Errore nell'invio del messaggio: " . $e->getMessage()));
            return $toret;
        }
        return $toret;
    }

    /**
     * 
     */
    public function actionCopyAppContentFromEmail() {
        // if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
        // }

        $model = new PreferenceCampainCopy(['scenario' => PreferenceCampainCopy::SCENARIO_COPY_CHANNEL]);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()
            && (strcmp($model->type_copy, "struttura_e_contenuti") == 0 || strcmp($model->type_copy, "solo_struttura") == 0) ) {


            // start transaction 
            $transaction = \Yii::$app->db->beginTransaction();

            try {

                // check if exist PreferenceCampain
                $preference_campain = PreferenceCampain::find()
                    ->andWhere(['id' => $model->preference_campain_id])
                    ->one();

                if( null != $preference_campain ){

                    // get app channel mm and channel sections
                    $app_channel = CampainsUtility::campainGetAppModel($preference_campain);
                    $app_channel_section = $app_channel->getPreferenceCampainSections()->one();

                    // check if app channel section hav container and remove it
                    $app_channel_section_containers = $app_channel_section->preferenceCampainContainers;
                    foreach ($app_channel_section_containers as $key => $app_section) {
                        $app_section->delete(false);
                    }
                    
                    // get preference_campain_channel_mm Newsletter and preference_campain_section
                    $newsletter_channel = CampainsUtility::campainGetNewsletterModel($preference_campain);
                    $newsletter_channel_sections = $newsletter_channel->getPreferenceCampainSections()->orderBy(['order' => SORT_ASC])->all();

                    $last_container_order = 1;

                    foreach ($newsletter_channel_sections as $key => $section) {

                        // get all preference_campain_container for section order by section container order
                        $nesletter_channel_section_containers = $section->getPreferenceCampainContainers()->orderBy(['order' => SORT_ASC])->all();

                        foreach ($nesletter_channel_section_containers as $key => $container) {
                            
                            // check type of content copy
                            if( strcmp($model->type_copy, "struttura_e_contenuti") == 0 ){

                                // copy only container type "Contenuto"
                                if( strcmp($container->preferenceCampainContainerType->name, 'Contenuto') == 0
                                    || strcmp($container->preferenceCampainContainerType->name, 'Contenuto Esterno') == 0 ){

                                    $copy_app_channel_container = CampainsUtility::copyPreferenceCampainContainer($container, $app_channel_section);
                                    $copy_app_channel_container->order = $last_container_order++;
                                    $copy_app_channel_container->save();

                                    // copy container image
                                    if( null != $container->contentImage ){

                                        $moduleAttachments = \Yii::$app->getModule('attachments');
                                        $moduleAttachments->attachFile($container->contentImage->getPath(), $copy_app_channel_container, 'contentImage', false);

                                        // $this->importImageUrl($copy_app_channel_container, \Yii::$app->urlManager->createAbsoluteUrl($container->contentImage->getWebUrl()));
                                    }
                                }

                            }elseif( strcmp($model->type_copy, "solo_struttura") == 0 ){
                                    
                                // copy only container type "Contenuto"
                                if( strcmp($container->preferenceCampainContainerType->name, 'Contenuto') == 0 ){

                                    $copy_app_channel_container = new PreferenceCampainContainer;
                                    $copy_app_channel_container->preference_campain_section_id = $app_channel_section->id;
                                    $copy_app_channel_container->preference_campain_container_type_id = $container->preference_campain_container_type_id;
                                    $copy_app_channel_container->preference_landing_id = $container->preference_landing_id;
                                    $copy_app_channel_container->order = $last_container_order++;
                                    $copy_app_channel_container->save();
                                }
                            }
                        }
                    }

                    // commit transaction
                    $transaction->commit();
                    Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'Il canale è copiata correttamente.'));
                }

            } catch (\Throwable $ex) { 

                $transaction->rollBack();
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La copia del canale non è andata a buon fine." . $ex->getMessage()));
            }

        }else{

            $errors = $model->errors;
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La copia del canale non è andata a buon fine." . $errors));
        }



        // Yii::$app->getSession()->addFlash('danger', \Yii::t('campain', "Errore"));
        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * Method ad per la copia di una campagna
     *
     * @return void
     */
    public function actionCopyPreferenceCampain(){

        $model = new PreferenceCampainCopy;
        $model->scenario = PreferenceCampainCopy::SCENARIO_COPY_CAMPAIN;
        
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            
            // check type of copy preference campain (struttura_e_contenuti oppure solo_struttura)
            if( strcmp($model->type_copy, "struttura_e_contenuti") == 0 || strcmp($model->type_copy, "solo_struttura") == 0){

                // start transaction 
                $transaction = \Yii::$app->db->beginTransaction();

                try {

                    // check if exist PreferenceCampain
                    $preference_campain = PreferenceCampain::find()
                        ->andWhere(['id' => $model->preference_campain_id])
                        ->one();

                    
                    if( null != $preference_campain ){

                        $copy_preference_campain = CampainsUtility::copyPreferenceCampain($preference_campain, $model);
                        $copy_preference_campain->status = "PreferenceCampainWorkflow/TOBEAPPROVED";
                        $copy_preference_campain->save();


                        // get and copy all PreferenceCampainTagMm
                        foreach ($preference_campain->preferenceCampainTagMms as $key => $tag) {

                            $copy_preference_campain_tag_mm = CampainsUtility::copyPreferenceCampainTagMm($tag, $copy_preference_campain);
                            $copy_preference_campain_tag_mm->save();
                        }


                        // get and copy all PreferenceCampainChannel
                        foreach ($preference_campain->preferenceCampainChannelMms as $key => $channel) {

                            if( strcmp($model->type_copy, "solo_struttura") == 0 
                                && strcmp($channel->preferenceChannel->title, "SMS") == 0 ){

                                $copy_preference_campain_channel_mm = new PreferenceCampainChannelMm;
                        
                                $copy_preference_campain_channel_mm->preference_campain_id = $copy_preference_campain->id;
                                $copy_preference_campain_channel_mm->id = NULL;
                                $copy_preference_campain_channel_mm->preference_channel_id = $channel->preference_channel_id;
                                $copy_preference_campain_channel_mm->status = "PreferenceCampainChannelMmWorkflow/TOBEAPPROVED";
                                $copy_preference_campain_channel_mm->save();

                            }else{

                                $copy_preference_campain_channel_mm = CampainsUtility::copyPreferenceCampainChannelMms($channel, $copy_preference_campain);
                                $copy_preference_campain_channel_mm->save();
                            }


                            // get and copy all PreferenceCampainSections for all channels
                            foreach ($channel->preferenceCampainSections as $key => $section) {

                                $copy_preference_campain_section = CampainsUtility::copyPreferenceCampainSection($section, $copy_preference_campain_channel_mm);
                                $copy_preference_campain_section->save();
                                

                                if( strcmp($model->type_copy, "struttura_e_contenuti") == 0 ){

                                    // get and copy all PreferenceCampainContainer
                                    foreach ($section->preferenceCampainContainers as $key => $container) {
                                        $copy_preference_campain_container = CampainsUtility::copyPreferenceCampainContainer($container, $copy_preference_campain_section);
                                        $copy_preference_campain_container->save();

                                        // copy container image
                                        if( null != $container->contentImage ){
                                            $moduleAttachments = \Yii::$app->getModule('attachments');
                                            $moduleAttachments->attachFile($container->contentImage->getPath(), $copy_preference_campain_container, 'contentImage', false);
                                            // $this->importImageUrl($copy_preference_campain_container, \Yii::$app->urlManager->createAbsoluteUrl($container->contentImage->getWebUrl()));
                                        }
                                    }

                                }elseif( strcmp($model->type_copy, "solo_struttura") == 0 ){

                                    // get and copy all PreferenceCampainContainer
                                    foreach ($section->preferenceCampainContainers as $key => $container) {

                                        // copy only container type "Contenuto" / "Separatore"
                                        if( strcmp($container->preferenceCampainContainerType->name, 'Contenuto') == 0 
                                            || strcmp($container->preferenceCampainContainerType->name, 'Separatore') == 0){

                                            $copy_preference_campain_container = new PreferenceCampainContainer;
                                            
                                            $copy_preference_campain_container->preference_campain_section_id = $copy_preference_campain_section->id;
                                            $copy_preference_campain_container->preference_campain_container_type_id = $container->preference_campain_container_type_id;
                                            // $copy_preference_campain_container->preference_landing_id = $container->preference_landing_id;
                                            $copy_preference_campain_container->separator_title = $container->separator_title;

                                            $copy_preference_campain_container->save();

                                        }

                                    }
                                }

                            }
                        }

                    }else{

                        \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La copia della campagna non è andata a buon fine."));
                    }

                    
                    // commit transaction
                    $transaction->commit();
                    Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'La campagna è stata copiata correttamente.'));
     
                } catch (\Throwable $ex) { 

                    $transaction->rollBack();
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La copia della campagna non è andata a buon fine." . $ex->getMessage()));
                }


            }
            

            // TODO TO REMOVE
            /*elseif ( strcmp($model->type_copy, "solo_struttura") == 0 ) {

                // start transaction 
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                

                    // commit transaction
                    $transaction->commit();
                    Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'La campagna è stata copiata correttamente.'));
        
                } catch (\Throwable $ex) { 

                    $transaction->rollBack();
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La copia della campagna non è andata a buon fine." . $ex->getMessage()));
                }
                
            }*/

        } else {

            $errors = $model->errors;
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La copia della campagna non è andata a buon fine."));
        }

        return \Yii::$app->response->redirect(['campains/preference-campain/index']);
    }
    
}
