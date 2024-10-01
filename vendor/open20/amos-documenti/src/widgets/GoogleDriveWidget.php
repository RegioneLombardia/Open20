<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 03/12/2019
 * Time: 16:21
 */

namespace open20\amos\documenti\widgets;


use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class GoogleDriveWidget extends Widget
{
    public $model;
    public $form;
    public $attribute;

    private $developerKey;
    private $clientId;
    private $appId;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->configureWithModuleParams();
        if ($this->clientId === null) {
            throw new InvalidConfigException('The "clientId" property must be set.');
        }

        if ($this->developerKey === null) {
            throw new InvalidConfigException('The "developerKey" property must be set.');
        }

        if ($this->appId === null) {
            throw new InvalidConfigException('The "appId" property must be set.');
        }

    }


    public function run()
    {
        $this->registerJs();
        $driveButton = '';
        if($this->model->is_folder){
            $driveButton = $this->render('google_drive_upload_folder', [
                'model' => $this->model,
                'form' => $this->form
            ]);
        }
        return $driveButton.$this->render('google_drive_upload', [
            'model' => $this->model,
            'form' => $this->form
        ]);
    }

    /**
     * @throws InvalidConfigException
     */
    public function configureWithModuleParams(){
        $module = \Yii::$app->getModule('documenti');
        try {
            if ($module) {
                $this->clientId = $module->googleDriveConf['clientId'];
                $this->appId = $module->googleDriveConf['appId'];
                $this->developerKey = $module->googleDriveConf['developerKey'];
            }
        }catch (Exception $e){
            throw new InvalidConfigException('You have to configure correctly AmosDocument with google drive auth-params');
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function registerJs()
    {
        $developerKey = $this->developerKey;
        $clientId = $this->clientId;
        $appId = $this->appId;
        $isFolder = $this->model->is_folder;

        $jsGoogleDrive = <<<JS
  // The Browser API key obtained from the Google API Console.
        // Replace with your own Browser API key, or your own key.
        var developerKey = '$developerKey';

        // The Client ID obtained from the Google API Console. Replace with your own Client ID.
        var clientId = "$clientId";

        // Replace with your own project number from console.developers.google.com.
        // See "Project number" under "IAM & Admin" > "Settings"
        var appId = "$appId";

        // Scope to use to access user's Drive items.
        var scope = ['https://www.googleapis.com/auth/drive'];

        var pickerApiLoaded = false;
        var oauthToken;
        var is_folder = "$isFolder";

        // Use the Google API Loader script to load the google.picker script.
        function loadPicker() {
            gapi.load('auth', {'callback': onAuthApiLoad});
            gapi.load('picker', {'callback': onPickerApiLoad});
        }

        function onAuthApiLoad() {
            window.gapi.auth.authorize(
                {
                    'client_id': clientId,
                    'scope': scope,
                    'immediate': false
                },
                handleAuthResult);
        }


        function onPickerApiLoad() {
            pickerApiLoaded = true;
            createPicker();
        }

        function handleAuthResult(authResult) {
            if (authResult && !authResult.error) {
                oauthToken = authResult.access_token;
                 var token = document.getElementById('auth-token');
                 var tokenlink = document.getElementById('btnprova');
                 $(token).val(oauthToken);
                 $(tokenlink).attr('href', '/documenti/documenti/update/?id=4&oauthToken='+oauthToken);
                createPicker();
            }
        }

        // Create and render a Picker object for searching images.
        function createPicker() {
            if (pickerApiLoaded && oauthToken) {
                // var view = new google.picker.View(google.picker.ViewId.DOCS);
                var DisplayView = new google.picker.DocsView()
                .setIncludeFolders(true)
                .setParent('root');
                
                if(is_folder == "1"){
                   DisplayView.setMimeTypes('application/vnd.google-apps.folder')
                   .setSelectFolderEnabled(true);

                }
                
               // view.setMimeTypes("image/png,image/jpeg,image/jpg");
                var picker = new google.picker.PickerBuilder()
                  //  .enableFeature(google.picker.Feature.NAV_HIDDEN)
                    .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
                    .enableFeature(google.picker.Feature.SUPPORT_DRIVES)
                    .setAppId(appId)
                    .setLocale('it')
                    .setOAuthToken(oauthToken)
                    // .addView(view)                    
                    .addView(DisplayView)                    
                   // .addView(new google.picker.DocsUploadView())
                    .setDeveloperKey(developerKey)
                    .setCallback(pickerCallback)
                    .build();
                picker.setVisible(true);
            }
        }

        // A simple callback implementation.
        function pickerCallback(data) {
            if (data.action == google.picker.Action.PICKED) {
                console.log(data);
                var fileId = data.docs[0].id;
                var fileName = data.docs[0].name;
                var size = data.docs[0].sizeBytes;

                $('#file-name').val(fileName);
                $('#file-id').val(fileId);
                $('#drive-file-id').val(fileId);
                
                if(is_folder == '1'){
                    addPreviewFolder(fileName);
                    $('#documenti-titolo').val(fileName);
                }else {
                    addpreview(fileName, size);
                }
                
                console.log(data);
                // console.log(fileName);
                // alert('The user selected: ' + fileId);
            }
        }
        
        $(document).on('click','#auth',  function() {
        // $(authBtn).on('click', function() {
            $('#modal-choose-upload').modal('hide');
            loadPicker();
        });
        
        $('#file-dummy').click(function(e){
            e.preventDefault();
            $('#modal-choose-upload').modal('show');
        });  
        
        $('#browse-id').click(function(e){
            e.preventDefault();
            $('#modal-choose-upload').modal('hide');
            $('#documenti-documentmainfile').trigger('click');
        });
        
        
        function addpreview(filename, size){
            var html = '<div class="file-preview-thumbnails">' +
             '<div class="file-preview-frame krajee-default  kv-preview-thumb" id="preview-1575458180114_39-0" data-fileindex="0" data-fileid="330108-'+filename+'" data-template="other" title="'+filename+'">' +
              '<div class="kv-file-content">' +
               '<div class="kv-preview-data file-preview-other-frame" style="width:213px;height:160px;">' +
                '<div class="file-preview-other">' +
                 '<span class="file-other-icon"><i class="glyphicon glyphicon-file"></i></span>' +
                  '</div>' +
                   '</div>' +
                    '</div>' +
                     '<div class="file-thumbnail-footer">' +
                      '<div class="file-footer-caption" title="'+filename+'">' +
                       '<div class="file-caption-info">'+filename+'</div>' +
                        '<div class="file-size-info"><samp>('+size+' B)</samp></div>' +
                         '</div>' +
                          '<div class="file-upload-indicator" title="Non ancora caricato">' +
                           '<i class="glyphicon glyphicon-plus-sign text-warning"></i>' +
                            '</div>' +
                             '<div class="file-actions"><div class="file-footer-buttons"></div><div class="clearfix"></div></div></div></div></div>';
            $('#container-document-mainfile .file-preview .file-preview-thumbnails').html(html);
            $('#container-document-mainfile .file-input.file-input-new').removeClass('file-input-new');
            
            $('#container-document-mainfile input.file-caption-name').val(filename);
        }
        
        function addPreviewFolder(filename){
            $('<span class="dash dash-folder icon-folder"></span>').insertBefore( "#drive-folder-filename" );
            $('#drive-folder-filename').text(filename);
        }
        
        
JS;

        $this->getView()->registerJs($jsGoogleDrive);
        //The Google API Loader script.
        $this->getView()->registerJsFile('https://apis.google.com/js/api.js');
    }
}


