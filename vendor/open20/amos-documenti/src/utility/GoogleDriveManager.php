<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 12/11/2019
 * Time: 15:15
 */

namespace open20\amos\documenti\utility;

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use open20\amos\core\models\AmosModel as Model;

class GoogleDriveManager extends Model
{
    public $clientId;
    public $clientSecret;
    public $rootFolderId;
    public $relativeRedirectUrl;
    public $relativePathCredential;

    public $emailServiceAccount;
    public $relativePathCredentialServiceAccount;

    public $model;
    public $attribute = 'documentMainFile';

    public $useServiceAccount = false;

    private $redirectUrl;
    private $pathCredential;
    private $pathCredentialServiceAccount;
    private $scope = \Google_Service_Drive::DRIVE;
    private $authUrl = '';
    private $service;
    private $adapter;
    private $client;

    private $syncFolder = false;

    private $mapExtensionsGSuits = [
        'application/vnd.google-apps.document' => 'docx',
        'application/vnd.google-apps.spreadsheet' => 'xlsx',
        'application/vnd.google-apps.drawing' => 'pdf',
        'application/vnd.google-apps.presentation' => 'pptx',
        'application/vnd.google-apps.script' => 'json',
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {

        $this->configureWithModuleParams();

        if ($this->clientId === null) {
            throw new InvalidConfigException('The "clientId" property must be set.');
        }

        if ($this->clientSecret === null) {
            throw new InvalidConfigException('The "clientSecret" property must be set.');
        }

        $this->redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . $this->relativeRedirectUrl;
        $this->pathCredential = \Yii::$app->getBasePath() . $this->relativePathCredential;
        $this->pathCredentialServiceAccount = \Yii::$app->getBasePath() . $this->relativePathCredentialServiceAccount;


//        try {
        $this->setAuthTokenOnSession();
        $this->adapter = $this->prepareAdapter();
//            if (empty($this->adapter)) {
//                throw new NotInstantiableException(GoogleDriveAdapter::class, "Error on init Google drive adapter");
//            }
//        }catch (NotInstantiableException $ex){
//            \Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
//        }catch (Exception $ex){
//            \Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
//        } catch (\Google_Exception $ex){
//            \Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
//        }
    }

    /**
     * @throws InvalidConfigException
     */
    public function configureWithModuleParams()
    {
        $module = \Yii::$app->getModule('documenti');
        try {
            if ($module) {
                $this->clientId = $module->googleDriveConf['clientId'];
                $this->clientSecret = $module->googleDriveConf['clientSecret'];
                $this->relativeRedirectUrl = $module->googleDriveConf['relativeRedirectUrl'];
                $this->relativePathCredential = $module->googleDriveConf['relativePathCredential'];
                $this->emailServiceAccount = $module->googleDriveConf['emailServiceAccount'];
                $this->relativePathCredentialServiceAccount = $module->googleDriveConf['relativePathCredentialServiceAccount'];
            }
        } catch (Exception $e) {
            throw new InvalidConfigException('You have to configure correctly AmosDocument with google drive auth-params');
        }
    }

    /**
     * @return \open20\amos\documenti\utility\GoogleDriveAdapter|null
     * @throws \Google_Exception
     */
    public function prepareAdapter()
    {
        $client = new \Google_Client();
        $redirect_uri = $this->redirectUrl;
        if ($this->useServiceAccount) {
            $client->setAuthConfig($this->pathCredentialServiceAccount);
        } else {
            $client->setAuthConfig($this->pathCredential);
        }
        $client->setRedirectUri($redirect_uri);
        $client->addScope($this->scope);
        $this->client = $client;

        if (isset($_REQUEST['logout'])) {
            unset($_SESSION['upload_token']);
        }


        if (isset($_GET['code']) && empty($_SESSION['upload_token'])) {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            $this->client->setAccessToken($token);
            // store in the session also
            $_SESSION['upload_token'] = $token;
            // redirect back to the example
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
// set the access token as part of the client
        if (!empty($_SESSION['upload_token'])) {
            $this->client->setAccessToken($_SESSION['upload_token']);
            if ($this->client->isAccessTokenExpired()) {
                unset($_SESSION['upload_token']);
            }
        } else {
            //access to Service Account
            $token = $client->fetchAccessTokenWithAssertion();
            $client->setAccessToken($token);
//            $this->authUrl = $this->client->createAuthUrl();
        }


        if ($this->client->getAccessToken()) {
            $service = new \Google_Service_Drive($this->client);
            $this->service = $service;

            $adapter = new \open20\amos\documenti\utility\GoogleDriveAdapter($service);
            return $adapter;
//            $results = $adapter->listContents();
//            pr($results,'FFF');
        }
        return null;
    }

    /**
     * @param $fileId
     * @throws \Google_Exception
     */
    public function getFile($fileId)
    {
        $file = null;

        /** @var  $adapter GoogleDriveAdapter */
        $adapter = $this->adapter;
        if ($adapter) {
            $file = $adapter->read($fileId);
        }
        return $file;
    }

    /**
     * @param $fileId
     * @return null
     */
    public function getFileOject($fileId)
    {
//        $adapter = $this->adapter;
        if ($response = $this->service->files->get($fileId, ['fields' => 'id, name, size, modifiedTime, createdTime, mimeType, fileExtension'])) {
            return $response;
        }
        return null;
    }

    /**
     * @param $fileId
     * @param null $fileName
     * @return bool|\open20\amos\attachments\models\File
     */
    public function getResourcesAndSave($fileId)
    {
        if ($this->model->is_folder) {
            return $this->getFolderAndSaveFiles($fileId);

        } else {
            return $this->getFileAndSave($fileId);
        }
    }

    /**
     * @param $fileId
     * @param null $fileName
     * @return bool|\open20\amos\attachments\models\File
     * @throws InvalidConfigException
     * @throws \Google_Exception
     */
    public function getFileAndSave($fileId, $folderId = null, $isFolder = false)
    {
        try {
            $file = $this->getFileOject($fileId);
            if ($file) {

                $title = $file->name;
                $mimeType = $file->mimeType;
                $extension = '';
//                $extension = $file->fileExtension;
                $lastModified = $file->modifiedTime;
                $modified_at = $this->formatDate($lastModified);

                if (!empty($mimeType)) {
                    $extension = $this->getExtensionFromMimeType($mimeType);
                }
                return $this->saveFile($fileId, $isFolder, $title, $extension, $modified_at, $folderId);
            }
        } catch (\Google_Service_Exception $e) {
//            pr($e->getMessage());
//            pr($e->getFile());
//            pr($e->getCode());
//            pr($e->getErrors());
            $code = $e->getCode();
            if ($code == 403) {
                \Yii::$app->session->addFlash('danger', AmosDocumenti::t('amosdocumenti', 'Accesso al file negato'));
            }

        }
        return false;
    }

    /**
     * @param $fileId
     */
    public function getFolderAndSaveFiles($folderId, $isFirst = true)
    {
        try {
            $file = $this->getFileOject($folderId);
            $this->syncFolder = true;

            if ($file) {
                $title = $file->name;
                $mimeType = $file->mimeType;
                $extension = '';
//                $extension = $file->fileExtension;
                $lastModified = $file->modifiedTime;
                $modified_at = $this->formatDate($lastModified);

                if ($isFirst) {
                    $this->saveFolder($folderId, $modified_at);
                }
                $list = $this->getList($folderId);
                foreach ($list as $elemFile) {
                    $path = explode('/', $elemFile['path']);
                    $fileId = end($path);
                    $isFolder = false;
//                pr($elemFile);
                    if ($elemFile['type'] == 'dir') {
                        $isFolder = true;
                    }
//                pr($path);
//                pr(end($path));
                    $this->getFileAndSave($fileId, $folderId, $isFolder);
                }
//            pr($list);
//                $this->printFilesInFolder($fileId);
            }
//            die;
        } catch (\Google_Service_Exception $e) {
//            pr($e->getMessage());
//            pr($e->getFile());
//            pr($e->getCode());
//            pr($e->getErrors());
            $code = $e->getCode();
            if (in_array($code, [400, 403, 401])) {
                \Yii::$app->session->addFlash('danger', AmosDocumenti::t('amosdocumenti', 'Accesso al file negato'));
            }

        }
    }


    /**
     * @param $fileId
     * @return mixed
     */
    public function getFilename($fileId)
    {
        if ($file = $this->service->files->get($fileId, [])) {
            $title = $file->name;
        }
        return $title;
    }

    /**
     * @param $file
     * @param $filename
     * @return bool|\open20\amos\attachments\models\File
     * @throws InvalidConfigException
     */
    public function saveFile($fileId, $isFolder, $filename, $extension = '', $lastModifiedDateTime = null, $folderId = null)
    {
        $recursive = false;
        $attribute = $this->attribute;
        $basePath = \Yii::getAlias('@common');
        $fullExtension = (!empty($extension)) ? '.' . $extension : '';
        $uploadPath = $basePath . "/uploads/temp/$filename" . $fullExtension;
        $compact = $this->adapter->readStream($fileId);
        file_put_contents($uploadPath, $compact['stream']);


        /** @var  $moduleAttach FileModule */
        if ($fileId) {
            $document = null;
            if(empty($folderId) && !$this->model->is_folder){
                $document = $this->model;
            }

            if (empty($document)) {
                $document = new Documenti();
                $document->drive_file_id = $fileId;
                $document->titolo = $filename;

                $document->validatori = $this->model->validatori;
                $document->regola_pubblicazione = $this->model->regola_pubblicazione;
                $document->destinatari = $this->model->destinatari;
                if ($isFolder) {
                    $document->is_folder = true;
                    $recursive = true;
                }
            }
        }
        if (!empty($folderId)) {
            $folder = Documenti::find()->andWhere(['drive_file_id' => $folderId, 'is_folder' => true])->orderBy('id desc')->one();
            if ($folder) {
                $document->parent_id = $folder->id;
            }
        }

        $moduleAttach = \Yii::$app->getModule('attachments');
        $attachFile = $document->$attribute;
        if ($attachFile) {
            $attachFile->delete();
        }
        $document->drive_file_modified_at = $lastModifiedDateTime;
        $document->save(false);
        if ($recursive) {
            $this->getFolderAndSaveFiles($fileId, false);
        }
        return $moduleAttach->attachFile($uploadPath, $document, $this->attribute);
    }


    /**
     * @param $fileId
     * @param null $lastModifiedDateTime
     */
    public function saveFolder($fileId, $lastModifiedDateTime = null)
    {
        $this->model->drive_file_modified_at = $lastModifiedDateTime;
        $this->model->save(false);
    }

    /**
     * @return mixed
     */
    public function getAuthUrl()
    {
        return $this->authUrl;
    }

    /**
     * @return \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter|null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     *
     */
    public function setAuthTokenOnSession()
    {
        $token = \Yii::$app->request->post('authtoken');
        if ($token) {
            $_SESSION['upload_token'] = $token;
        }
    }

    /**
     * @param $mimeType
     * @return mixed|null
     */
    public function getExtensionFromMimeType($mimeType)
    {
        if (!empty($this->mapExtensionsGSuits[$mimeType])) {
            $extension = $this->mapExtensionsGSuits[$mimeType];
            return $extension;
        }
        return null;
    }

    /**
     * @param string $dirname
     * @param bool $recursive
     * @return array
     */
    public function getList($dirname = '', $recursive = false)
    {
        $results = [];
        $adapter = $this->adapter;
        //
//        'and sharedWithMe = true ';
        if ($dirname) {
            $query = '';
//            $query = 'trashed = false and includeTeamDriveItems = true';

        } else {
            $query = 'trashed = false';
        }
        /** @var  $adapter GoogleDriveAdapter */
        if ($adapter) {
            $results = $adapter->listContents($dirname, $recursive, 100, $query);
        }
        return $results;
    }


    /**
     * Publish specified path item
     *
     * @param string $path
     *            itemId path
     *
     * @return bool
     */
    public function shareWithUser($fileId)
    {
        $sharePermission = [
            'type' => 'user',
            'role' => 'reader',
            'emailAddress' => $this->emailServiceAccount,
            'withLink' => true
        ];

        try {
            if ($fileId) {
                $permission = new \Google_Service_Drive_Permission($sharePermission);
                if ($this->service->permissions->create($fileId, $permission)) {
                    return true;
                }
            }
        } catch (Exception $e) {
            return false;
        }


        return false;
    }

    /**
     * @throws \Google_Exception
     */
    public function isDocumentUpdated()
    {
        $fileId = $this->model->drive_file_id;
        $objFile = $this->getFileOject($fileId);
        if ($objFile) {
            $modifiedTime = $objFile->modifiedTime;
            $lastModifiedTimeOnDrive = new \DateTime($modifiedTime);
            $driveModifiedAt = new \DateTime($this->model->drive_file_modified_at);


            $eu_time = new \DateTimeZone('Europe/Rome');
            $lastModifiedTimeOnDrive->setTimezone($eu_time);

//            pr($lastModifiedTimeOnDrive, 'ondrive');
//            pr($driveModifiedAt, 'ondlatform');die;
            return ($lastModifiedTimeOnDrive->format('Y-m-d H:i:s') > $driveModifiedAt->format('Y-m-d H:i:s'));
        }
        return false;
    }

    /**
     * @param $date
     * @return string
     */
    protected function formatDate($date)
    {
        $lastModifiedDateTime = new \DateTime($date);
        $eu_time = new \DateTimeZone('Europe/Rome');
        $lastModifiedDateTime->setTimezone($eu_time);
        $modified_at = $lastModifiedDateTime->format('Y-m-d H:i:s');
        return $modified_at;
    }
}