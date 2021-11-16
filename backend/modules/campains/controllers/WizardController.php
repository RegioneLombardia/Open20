<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 27/02/2020
 * Time: 12:10
 */

namespace backend\modules\campains\controllers;

use \Yii;
use Exception;
use Throwable;
use yii\db\Query;
use yii\db\Transaction;
use yii\helpers\VarDumper;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\TargetTagUtility;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\landings\models\PreferenceLanding;
use open20\amos\core\controllers\CrudController;
use backend\modules\campains\utility\MailUpEmailUtility;
use backend\modules\campains\models\PreferenceCampainTagMm;
use backend\modules\campains\models\PreferenceCampainSection;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\models\PreferenceCampainContainer;
use backend\modules\campains\models\base\PreferenceMailupTemplate;
use backend\modules\campains\models\search\PreferenceCampainSearch;
use backend\modules\campains\models\search\UserCampainSearch;
use backend\modules\campains\utility\ExternalContentUtility;

class WizardController extends CrudController
{

    public $scope = null;

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
                            'create-campain',
                            'newsletter-data',
                            'app-data',
                            'newsletter-content',
                            'app-content',
                            'sms-data',
                            'summary',
                            'create-newsletter-section',
                            'create-newsletter-container',
                            'create-app-container',
                            'set-preference-campain-container-order',
                            'delete-preference-campain-container',
                            'delete-preference-campain-section',
                            'set-preference-campain-section-order'
                        ],
                        'roles' => ['PC_ADMIN', 'PC_BASIC_USER', '@']
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

    
    /**
     * @inheritdoc
     */
    public function init()
    {

        $this->setModelObj(new PreferenceCampain());
        $this->setModelSearch(new PreferenceCampainSearch());
        $this->setAvailableViews([]);

        // EventsAsset::register(\Yii::$app->view);

        parent::init();
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container';
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
    }

 
    /**
     * @param null $id
     * @return string|void
     */
    public function actionIndex($id = null)
    {
        // $this->redirect(['base-info', 'id' => $id]);
        $this->redirect(['create-campain', 'id' => $id]);
    }


    /**
     * Metodo per la creazione della campagna 
     * STEP 1 - cap 6.4.3
     *
     * @return redirect to view ../campains/wizard/create-campain
     */
    public function actionCreateCampain($id = null)
    {
        // controllo se esiste la PreferenceCampain altrimenti ne istanzio una nuova
        $this->model = PreferenceCampain::findOne($id);

        if( null == $this->model && null != $id ){
            // altrimenti si sta cercando di accedere a una PreferenceCampain non esistente
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La campagna non è stata trovata."));
            return $this->redirect('campains/preference-campain/index');  

        }elseif( null == $this->model && $id == null ){
            // creazione ddi un nuovo PreferenceCampain
            $this->model = new PreferenceCampain();
        }

        if ($this->model->load(Yii::$app->request->post()) ) {

            // start transaction
            $transaction = \Yii::$app->db->beginTransaction();

            try {

                $this->model->search_params = serialize(Yii::$app->request->post());

                // save PreferenceCampain
                if( $this->model->save() ){

                    // cancellazione dei PreferenceCampainTagMm precedentemente creati PreferenceCampain se esistono
                    CampainsUtility::removeAllPreferenceCampainTag($this->model);

                    // rigenerazione dei PreferenceCampainTagMm precedentemente cancellati
                    $tag = array_merge((array) Yii::$app->request->post('tag_cittadino'),
                                        (array) Yii::$app->request->post('tag_impresa'), 
                                        (array) Yii::$app->request->post('tag_ente_operatore'));

                    CampainsUtility::saveTag($tag, $this->model->id);

                    // creazione dei canali PreferenceCampainChannelMm associati alla PreferenceCampain
                    if (null == Yii::$app->request->post('channel') ){
                        throw new Exception("Errore! Non risulta selezionato nessun canale.", 1);
                    }
                        
                    CampainsUtility::saveChannelUnique(Yii::$app->request->post('channel'), $this->model);

                }else{
                    // rollback transaction
                    $transaction->rollBack();
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La creazione della campagna non è andata a buon fine."));
                }

                // se no esiste o non è stato creato nessun canale per la PreferenceCampain allora faccio il rollback della transaction
                if( false == CampainsUtility::existNewsletterChannel($this->model)
                    &&  false == CampainsUtility::existSmsChannel($this->model) 
                    &&  false == CampainsUtility::existAppChannel($this->model)){

                    $transaction->rollBack();
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Non risulta selezionato nessun canale."));
                }
                
                // commit transaction
                $transaction->commit();

                // redirect alla vista di interesse a seconda del canale selezionato
                if (CampainsUtility::existNewsletterChannel($this->model)) {
                    return $this->redirect(['newsletter-data', 'id' => $this->model->id]);
                
                }elseif(CampainsUtility::existSmsChannel($this->model)) {
                    return $this->redirect(['sms-data', 'id' => $this->model->id]);
                }elseif(CampainsUtility::existAppChannel($this->model)) {
                    return $this->redirect(['app-data', 'id' => $this->model->id]);
                }  

                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore sulla navigazione del WIZARD."));

            } catch (\Throwable $ex) { 

                $transaction->rollBack();

                if( $ex->getCode() == 1 ){
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', $ex->getMessage() ));
                }else{
                    \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La creazione della campagna non è andata a buon fine."));
                }
            }
        }

        $modelSearch = new UserCampainSearch();
        $query_params = unserialize($this->model->search_params);
        $modelSearch->load($query_params);


        // estrazione dei dati per la vista
        $profili = TargetTagUtility::getAllTargetTag();
        $tag = array();
        $tag['cittadino'] = TopicTagUtility::getAllTopicByTargetCode('pctarget_cittadino');
        $tag['impresa'] = TopicTagUtility::getAllTopicByTargetCode('pctarget_impresa');
        $tag['ente_operatore'] = TopicTagUtility::getAllTopicByTargetCode('pctarget_enteeoperatore');


        return $this->render('base-info', [
            'model' => $this->model,
            'profili' => $profili,
            'tag' => $tag,
            'modelSearch' => $modelSearch,
        ]);

    }


    /**
     * Method step 2
     *
     * @param string $id PreferenceCampain ID...
     * @return void
     */
    public function actionNewsletterData($id = null)
    {

        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

        // Action che lavora sul model 
        /** @var PreferenceCampainChannelMm $model */
        $model = CampainsUtility::campainGetNewsletterModel($this->model);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // update / create PreferenceCampainChannelMm
            try {
                
                if( !$model->save() ){

                    throw new Exception("Errore! Non è stato possibile salvare la Campagna Newsletter.");
                }else{

                    // redirect alla vista newsletter content STEP 3
                    return $this->redirect(['newsletter-content', 'id' => $this->model->id]);
                }

            } catch (\Throwable $th) {
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', $th->getMessage()));
            }

        }

        // data for the view
        $template = ArrayHelper::map(PreferenceMailupTemplate::find()->All(), 'mailup_template_id', 'title');

        return $this->render('newsletter_data', [
            'model' => $model,
            'template' => $template
        ]);
    }


    /**
     * Undocumented function
     *
     * @param string $id PreferenceCampain ID...
     * @return void
     */
    public function actionNewsletterContent($id = null)
    {
        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

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

        // VarDumper::dump( ArrayHelper::map($colorChoiches, 'bgcolor', 'label'), $depth = 10, $highlight = true);

        /**
         * data view
         */
        return $this->render('newsletter_content', [
            'model' => $this->model,
            'preference_campain_section' => new PreferenceCampainSection,
            'preference_campain_container' => new PreferenceCampainContainer,
            'newsletter_content' => $this->getNewsletterContent($model),
            'model_preference_campain_channel_mm' => $model,
            'newsletter_section_color' =>  $this->getColorChoices($colorChoiches),
            'newsletter_section_title' =>  $this->getTitleChoices($colorChoiches),
            'colorChoiches' => $colorChoiches,
            'aspectRatioChoices' => $aspectRatioChoices,
            'aspectRatio' => !empty($aspectRatio)? $aspectRatio: '1.7777777777778',
            'type_content_align' => ['center' => 'Nessun allineamento (al centro)', 'left' => 'Allinea immagine a sinistra', 'right' => 'Allinea immagine a destra'],
            'external_source' => ExternalContentUtility::getSources(),
        ]);
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
     * Metodo per la creazione di una Sezione per il canale Newsletter
     *
     * @param int | $id => PreferenceCampain
     * @return void
     */
    public function actionCreateNewsletterSection($id = null){

        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

        // POST DATA
        $post_request_data = Yii::$app->request->post('PreferenceCampainSection');

        // estrazione record PreferenceCampainSection se esite
        if( null == $model = PreferenceCampainSection::findOne($post_request_data['id']) ){
            $model = new PreferenceCampainSection();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            /**
             *  controllo l'esistenza del PrefereceCampain, PreferenceCampainChannelMm e la loro associazione
             */
            $preferece_campain = PreferenceCampain::findOne($id);
            $preferece_campain_channel_mm = PreferenceCampainChannelMm::findOne($post_request_data['preference_campain_channel_mm_id']);

            if( $preferece_campain_channel_mm->preferenceCampain->id != $preferece_campain->id ){

                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La campagna non è stata trovata."));
                // redirect back con l'id PreferenceCampain dal get in get
                return $this->redirect(['newsletter-content', 'id' => $id]);
            }

            // TODO: color ?? 
            /**
             * creazione della newsletter section PreferenceCampainSection
             */
            if(!$model->save()){

                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La creazione della sezione non è andata a buon fine."));
            }else{

                Yii::$app->getSession()->addFlash('success', Yii::t('campains', 'La creazione della sezione è andata a buon fine.'));
            }

        } else {

            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! Il caricamento dei dati non è andato a buon fine."));
        }

        // redirect back con l'id PreferenceCampain dal get in get
        return $this->redirect(['newsletter-content', 'id' => $id]);
    }


    /**
     * Metodo per la creazione della PreferenceCampainContainer
     *
     * @return void
     */
    public function actionCreateNewsletterContainer($id = null){

        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }
        
        // POST DATA
        $post_request_data = Yii::$app->request->post('PreferenceCampainContainer');

        // controllo se esiste il record PreferenceCampainContainer (caso update) oppure se è la creazione di un nuovo record
        if( null == $model = PreferenceCampainContainer::findOne($post_request_data['id']) ){
            $model = new PreferenceCampainContainer(['scenario' => PreferenceCampainContainer::SCENARIO_DEFAULT]);
        }

        if ($model->load(Yii::$app->request->post()) /* && $model->validate()*/) {

            // set scenario PreferenceCampainContainer a seconda del type
            if( null != $model->preference_campain_container_type_id ){

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
        return $this->redirect(['newsletter-content', 'id' => $id]);
    }


    /**
     * Metodo per la creazione della PreferenceCampainContainer
     *
     * @return void
     */
    public function actionCreateAppContainer($id = null){

        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }
        
        // POST DATA
        $post_request_data = Yii::$app->request->post('PreferenceCampainContainer');

        // controllo se esiste il record PreferenceCampainContainer (caso update) oppure se è la creazione di un nuovo record
        if( null == $model = PreferenceCampainContainer::findOne($post_request_data['id']) ){
            $model = new PreferenceCampainContainer(['scenario' => PreferenceCampainContainer::SCENARIO_DEFAULT]);
        }

        if ($model->load(Yii::$app->request->post()) /* && $model->validate()*/) {

            // set scenario PreferenceCampainContainer a seconda del type
            if( null != $model->preference_campain_container_type_id ){

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
        return $this->redirect(['app-content', 'id' => $id]);
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
     * @param string $id PreferenceCampain ID...
     * @return void
     */
    public function actionSmsData($id)
    {
        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

        // Action che lavora sul model PreferenceCampainChannelMm
        /** @var PreferenceCampainChannelMm $model */
        $model = CampainsUtility::campainGetSmsModel($this->model);

        if ($model->load(Yii::$app->request->post())) {

            try {

                /** 
                 * TODO da aggiungere
                 * sezione serverside per il filtraggio dei caratteri textarea del sms secondo le convenzioni dell'operatore telefonico 
                */
                
                if( !$model->save() )
                    throw new Exception("Errore! La creazione della campagna SMS non è andata a buon fine.");

            } catch (\Throwable $th) {
                
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', $th->getMessage()));
                // redirect back to step 4 NUOVA CAMPAGNA: SMS
                return $this->redirect(['summary', 'id' => $model->id]);
            }

            if(CampainsUtility::existAppChannel($this->model)) {
                return $this->redirect(['app-data', 'id' => $this->model->id]);
            }

            // TODO al save corretto -> return $this->redirect(['summary', 'id' => $this->model->id]);
            return $this->redirect(['summary', 'id' => $this->model->id]);
        }

        return $this->render('sms_data', [
            'model' => $model,
        ]);
    }

        /**
     * Method step 2
     *
     * @param string $id PreferenceCampain ID...
     * @return void
     */
    public function actionAppData($id = null)
    {

        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

        // Action che lavora sul model 
        /** @var PreferenceCampainChannelMm $model */
        $model = CampainsUtility::campainGetAppModel($this->model);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // update / create PreferenceCampainChannelMm
            try {
                
                if( !$model->save() ){

                    throw new Exception("Errore! Non è stato possibile salvare la Campagna Newsletter.");
                }else{

                    return $this->redirect(['app-content', 'id' => $this->model->id]);
                }

            } catch (\Throwable $th) {
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', $th->getMessage()));
            }

        }

        // data for the view
        $template = ArrayHelper::map(PreferenceMailupTemplate::find()->All(), 'mailup_template_id', 'title');

        return $this->render('app_data', [
            'model' => $model,
            'template' => $template
        ]);
    }


    /**
     * Undocumented function
     *
     * @param string $id PreferenceCampain ID...
     * @return void
     */
    public function actionAppContent($id = null)
    {

        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

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

            return $this->redirect(['summary', 'id' => $this->model->id]);
        }

        $myTemplate = PreferenceMailupTemplate::findOne(['mailup_template_id' => $model->template_id]);
        $colorChoiches = json_decode($myTemplate->colors_choice, true);
        $aspectRatioChoices = json_decode($myTemplate->aspect_ratio_choices, true);
        $aspectRatio = $myTemplate->aspect_ratio;

        // VarDumper::dump( ArrayHelper::map($colorChoiches, 'bgcolor', 'label'), $depth = 10, $highlight = true);

        /**
         * data view
         */
        return $this->render('app_content', [
            'model' => $this->model,
            'preference_campain_section' => new PreferenceCampainSection,
            'preference_campain_container' => new PreferenceCampainContainer,
            'newsletter_content' => $this->getNewsletterContent($model),
            'model_preference_campain_channel_mm' => $model,
            'newsletter_section_color' =>  $this->getColorChoices($colorChoiches),
            'newsletter_section_title' =>  $this->getTitleChoices($colorChoiches),
            'aspectRatioChoices' => $aspectRatioChoices,
            'aspectRatio' => !empty($aspectRatio)? $aspectRatio: '1.7777777777778',
            'type_content_align' => ['center' => 'Nessun allineamento (al centro)', 'left' => 'Allinea immagine a sinistra', 'right' => 'Allinea immagine a destra'],
            'external_source' => ExternalContentUtility::getSources(),
        ]);
    }

      
    /**
     * Undocumented function
     *
     * @param string $id PreferenceCampain ID...
     * @return void
     */
    public function actionSummary($id)
    {

        $this->model = PreferenceCampain::findOne($id);
        if (empty($this->model)){
            throw new HttpException("Campain not found");
        }

        if ($this->model->load(Yii::$app->request->post())) {

        }




        return $this->render('summary', [
            'model' => $this->model,
        ]);
    }


    /**
     * 
     *  MZ Per fare dei test con il modulo delle newsletter
     * 
     * @param $id
     * @return mixed
     */
    public function actionSendEmailTest($id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $newsletterModule = \Yii::$app->getModule('newsletter');
        $decodedEmail = null;
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $decodedEmails = $mailService->sendEmailToRecipient(4749, \Yii::$app->request->post('email'));
        }
        return $decodedEmails;

    }


    /**
     * Metodo per il cambio dell'order del PreferenceCampainContainer
     * 
     * @param int | preference_campain_section_id ( PreferenceCampainSection ) | newsletter_container_id ( PreferenceCampainContainer )
     *
     * @return boolean 
     */
    public function actionSetPreferenceCampainContainerOrder(){

        // PreferenceCampainContainer record da cambiare l'order
        $preference_campain_container = PreferenceCampainContainer::find()->where([
            'id' => Yii::$app->request->post('preference_campain_container_id'),
            'preference_campain_section_id' => Yii::$app->request->post('preference_campain_section_id'),
            'deleted_at' => null
        ])->one();

        /**
         * Tipo ordinamento "up", "down"
         * procedo con l'estrazione del PreferenceCampainContent e del PreferenceCampainContent
         * con quale cambiare l'ordine
         */
        $all_section_containers = PreferenceCampainContainer::find()->select('id')->where([
            'preference_campain_section_id' => Yii::$app->request->post('preference_campain_section_id'),
            'deleted_at' => NULL
        ])->orderBy(['order' => SORT_ASC])->asArray()->all();


        // trasform l'array di array in array a una dimensione
        $containers_id = array();
        foreach ($all_section_containers as $value) {
            $containers_id[] = $value['id'];
        }
        
        // calcolo della posizione del PreferenceCampainContent e lo incremento / decremento per prelievare id 
        $position_in_array = array_search("$preference_campain_container->id", $containers_id, true);
        if( strcmp( (string) Yii::$app->request->post('type_order'), "up") == 0 ){
            $position_in_array -= 1;

            if( $position_in_array < 0 ){
                return true;
            }

        }elseif( strcmp( (string) Yii::$app->request->post('type_order'), "down" ) == 0){
            $position_in_array += 1;
            
            if( $position_in_array > count($containers_id) ){
                return true;
            }
        }

        // PreferenceCampainContainer con quale cambiare la posizione del order
        $preference_campain_container_exchange = PreferenceCampainContainer::find()
            ->where([
                'preference_campain_section_id' => Yii::$app->request->post('preference_campain_section_id'),
                'id' => $containers_id[$position_in_array],
                'deleted_at' => NULL
            ])->one();
        
        /**
         * controllo che ci siano sia il record PreferenceCampainContainer che il suo corrispettivo record 
         * precedente o successivo (cioe il recordo di order > oppure order < 1) e gli scambio di ordine
         * altrimenti il record si trova gia in prima / ultima posizione e non puo essere piu ordinato
         * questa procedura è per il fatto che i contenuti possono essere cancellati in fase di creazione o modifica
         */
        if( null != $preference_campain_container && null != $preference_campain_container_exchange ){

            // start transaction
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $tmp = $preference_campain_container->order;
                $preference_campain_container->order = $preference_campain_container_exchange->order;
                $preference_campain_container_exchange->order = $tmp;
                
                $preference_campain_container->save(false);
                $preference_campain_container_exchange->save(false);

                // commit transaction
                $transaction->commit();

                return true;

            } catch (\Throwable $ex) { 

                $transaction->rollBack();
                \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! L'ordinamento non è andato a buon fine."));

                return false;
            }
        }
    }


    /**
     * Metodo per il cambio dell'order del PreferenceCampainSection
     * 
     * @param int | preference_campain_section_id ( PreferenceCampainSection ) | preference_campain_channel_mm_id ( PreferenceCampainChannelMm )
     *
     * @return boolean 
     */
    public function actionSetPreferenceCampainSectionOrder(){

        // estrazione della sezione che deve essere ordinato
        $preference_campain_section =  PreferenceCampainSection::findOne(Yii::$app->request->post('preference_campain_section_id'));

        // estrazione di tutte le sezioni per il canale
        $all_preference_campain_section = PreferenceCampainSection::find()->where([
            'preference_campain_channel_mm_id' => Yii::$app->request->post('preference_campain_channel_mm_id'),
            'deleted_at' => null
        ])->orderBy(['order' => SORT_ASC])->asArray()->all();


        // trasform l'array di array in array a una dimensione
        $sections_id = array();
        foreach ($all_preference_campain_section as $value) {
            $sections_id[] = $value['id'];
        }

        // calcolo della posizione del PreferenceCampainContent e lo incremento / decremento per prelievare id 
        $position_in_array = array_search("$preference_campain_section->id", $sections_id, true);

        if( strcmp( (string) Yii::$app->request->post('type_order'), "up") == 0 ){
            $position_in_array -= 1;

            if( $position_in_array < 0 ){
                return true;
            }

        }elseif( strcmp( (string) Yii::$app->request->post('type_order'), "down" ) == 0){
            $position_in_array += 1;
            
            if( $position_in_array > count($sections_id) ){
                return true;
            }
        }

        // estrazione PreferenceCampainSection con qualle scambiare l'ordine
        $preference_campain_section_exchange = PreferenceCampainSection::find()->where([
            'preference_campain_channel_mm_id' => Yii::$app->request->post('preference_campain_channel_mm_id'),
            'id' => $sections_id[$position_in_array],
        ])->one();

  
        /**
         * controllo che ci siano sia il record PreferenceCampainSection che il suo corrispettivo record 
         * precedente o successivo (cioe il recordo di order > oppure order < 1) e gli scambio di ordine
         * altrimenti il record si trova gia in prima / ultima posizione e non puo essere piu ordinato
         * questa procedura è per il fatto che i contenuti possono essere cancellati in fase di creazione o modifica
         */
        if( null != $preference_campain_section && null != $preference_campain_section_exchange ){

            // start transaction
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                
                $tmp = $preference_campain_section->order;
                $preference_campain_section->order = $preference_campain_section_exchange->order;
                $preference_campain_section_exchange->order = $tmp;
                
                $preference_campain_section->save(false);
                $preference_campain_section_exchange->save(false);

                // commit transaction
                $transaction->commit();

                return true;

            } catch (\Throwable $ex) { 

                $transaction->rollBack();
                // \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! L'ordinamento non è andato a buon fine."));

                return false;
            }
        }

        return true;
    }


    /**
     * Metodo per la cancellazione di un contenuto PreferenceCampainContainer 
     * Tipo chiamata ajax
     * 
     * @return void
     */
    public function actionDeletePreferenceCampainContainer(){

        try {

            // estrazione record PreferenceCampainContainer da cancellare
            $preference_campain_container = PreferenceCampainContainer::findOne(Yii::$app->request->post('preference_campain_container_id'));

            if( null != $preference_campain_container ){
                
                $preference_campain_container->delete();
            }
    
        } catch (\Throwable $ex) { 

            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La cancellazione del contenuto non è andata a buon fine."));
        }

        \Yii::$app->getSession()->addFlash('success', \Yii::t('app', 'La cancellazione del contenuto è andata a buon fine.'));

        // redirect back con l'id PreferenceCampain dal get in get
        // return $this->redirect(['newsletter-content', 'id' => $id]);
    }


    /**
     * Metodo per la cancellazione della sezione PreferenceCampainSection e dei suoi contenuti associati PreferenceCampainContainer
     * Tipo chiamata ajax
     *
     * @param int | $preference_campain_section_id => PreferenceCampainSection
     * @return redirect to action newsletter-content
     */
    public function actionDeletePreferenceCampainSection(){

        // estrazione della sezione PreferenceCampainSection da cancellare
        $preference_campain_section = PreferenceCampainSection::findOne(Yii::$app->request->post('preference_campain_section_id'));

        // start transaction
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            
            // estrazion di tutti gli contenuti PreferenceCampainContainer associati alla sezione PreferenceCampainSection
            $preference_campain_containers = CampainsUtility::getAllPreferenceCampainContainersBySection(
                    Yii::$app->request->post('preference_campain_section_id'));

            // cancellazione dei contenuti PreferenceCampainContainer estratti
            foreach ( $preference_campain_containers as $preference_campain_container ) {
                
                $preference_campain_container->delete();
            }

            // controllo essistenza della sezione altrimenti genero una eccezione 
            $preference_campain_section = PreferenceCampainSection::findOne(Yii::$app->request->post('preference_campain_section_id'));

            if( null == $preference_campain_section ){
                throw new Exception();
            }

            // cancellazione della sezione PreferenceCampainSection
            $preference_campain_section->delete(); 

            // commit transaction
            $transaction->commit();
            Yii::$app->getSession()->addFlash('success', Yii::t('app', "La cancellazione della sezione e dei corrispettivi contenuti è andata a buon fine"));

        } catch (\Throwable $th) {
            
            $transaction->rollBack();
            \Yii::$app->getSession()->addFlash('danger', \Yii::t('app', "Errore! La cancellazione della sezione e dei corrispettivi contenuti non è andata a buon fine. "));
        }

        // redirect back con l'id PreferenceCampain dal get in get
        // return $this->redirect(['newsletter-content', 'id' => $id]);
    } 

    protected function getColorChoices($colorChoiches)
    {
        $toret = [];

        foreach ($colorChoiches as $key => $value) {
            $toret[$key] = $value['label'];
        }

        return $toret;
    }

    protected function getTitleChoices($colorChoiches)
    {
        $toret = [];

        foreach ($colorChoiches as $key => $value) {
            $toret[$key] = $value['default_text'];
        }

        return $toret;
    }

}