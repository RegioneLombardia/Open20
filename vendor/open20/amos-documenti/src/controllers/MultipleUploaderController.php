<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\community\exceptions\CommunityException;
use open20\amos\community\models\base\CommunityType;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\ReportNode;
use open20\amos\documenti\models\UploaderImportList;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class MultipleUploaderController extends Controller
{

    /**
     * @var type $dirTrees A tree for each file because is maybe more than one
     */
    protected $dirTrees = [];

    /**
     * @var type $projectRoothPath Root of the project installation
     */
    protected $projectRoothPath;

    /**
     * @var type $uploadDir Where the files has to be stocked
     */
    protected $uploadDir;

    /**
     * @var type $uploaderDir The uploader dir
     */
    protected $uploaderDir;

    /**
     * @var $pubblicationConfig
     */
    protected $pubblicationConfig;

    /**
     * The working community module
     * @var $communityModule AmosCommunity
     */
    protected $communityModule;

    /**
     * @var type $selectedNodes User selected nodes
     */
    protected $selectedNodes = [];

    /**
     * @var $attachmentsModule FileModule
     */
    protected $attachmentsModule;

    /**
     * @var $treeForReport
     */
    public $treeForReport = [];

    /**
     *
     * @var type $moduleCwh
     */
    public $moduleCwh;

    /**
     * @var AmosDocumenti $documentsModule
     */
    public $documentsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        //Setup variable used in this class
        $this->initVars();

        $this->documentsModule = Yii::$app->getModule(AmosDocumenti::getModuleName());
    }

    /**
     * Set base vars for dir and or more
     */
    protected function initVars()
    {
        //Path alias
        $pathAlias = Yii::getAlias('@app/../');

        //Root of the project installation
        $this->projectRoothPath = realpath($pathAlias);

        //Where the files has to be stocked
        $this->uploadDir = $this->projectRoothPath . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'uploads';

        //The uploader dir
        $this->uploaderDir = $this->uploadDir . DIRECTORY_SEPARATOR . 'uploader';
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     *
     * @param type $communityId
     * @return type
     * @throws Exception
     */
    public function actionExtract($communityId = null)
    {
        //Uploaded file data
        $getData = $this->getData();

        foreach ($getData as $file) {
            //The file location
            $uploadFilePath = $this->projectRoothPath . DIRECTORY_SEPARATOR . $file['path'];

            //Dir location of extracted files
            $dirLocation = $this->uploaderDir . DIRECTORY_SEPARATOR . 'extract_' . $file['hash'];

            //Extract the zip and return true if succeed
            $result = $this->extractZip($uploadFilePath, $dirLocation);

            // If the file is successfully extracted, redirect to the view, otherwise an error will be displayed
            if ($result == '0') {
                return $this->redirect([
                    'choose-nodes',
                    'item' => $file['hash'],
                    'communityId' => $communityId
                ]);
            } else {
                throw new Exception(AmosDocumenti::t('amosdocumenti', 'Unable to Unzip File') . ' ' . $uploadFilePath);
            }
        }
    }

    /**
     *
     * @param type $item
     * @param type $communityId
     * @return type
     */
    public function actionChooseNodes($item, $communityId = null)
    {
        //Dir location of extracted files
        $dirLocation = $this->uploaderDir . DIRECTORY_SEPARATOR . 'extract_' . $item;

        //Array Tree of the directory
        $dirTree = $this->dirTree($dirLocation, false, true);

        return $this->render(
            'choose-nodes',
            [
                'dirTree' => $dirTree,
                'item' => $item,
                'communityId' => $communityId
            ]
        );
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function actionBuildPlatform()
    {
        set_time_limit(0);

        Yii::$app->db->createCommand(<<<SQL
          ALTER TABLE `attach_file` DISABLE KEYS;
          ALTER TABLE `attach_file_refs` DISABLE KEYS;
          ALTER TABLE `community` DISABLE KEYS;
          ALTER TABLE `community_user_mm` DISABLE KEYS;
          ALTER TABLE `cwh_auth_assignment` DISABLE KEYS;
          ALTER TABLE `cwh_config` DISABLE KEYS;
          ALTER TABLE `cwh_config_contents` DISABLE KEYS;
          ALTER TABLE `cwh_nodi_mt` DISABLE KEYS;
          ALTER TABLE `cwh_nodi_view` DISABLE KEYS;
          ALTER TABLE `cwh_pubblicazioni` DISABLE KEYS;
          ALTER TABLE `cwh_pubblicazioni_cwh_nodi_editori_mm` DISABLE KEYS;
          ALTER TABLE `cwh_pubblicazioni_cwh_nodi_validatori_mm` DISABLE KEYS;
          ALTER TABLE `documenti` DISABLE KEYS;
          ALTER TABLE `documenti_allegati` DISABLE KEYS;
          SET FOREIGN_KEY_CHECKS = 0;
          SET UNIQUE_CHECKS = 0;
          SET AUTOCOMMIT = 0;
SQL
        )->execute();

        //Selected nodes
        $this->selectedNodes = json_decode(Yii::$app->request->post('nodes'));

        //Selected name (not required
        $selectedName = Yii::$app->request->post('name');

        $communityCreated = null;

        //Community ID for override
        $communityId = Yii::$app->request->post('communityId');

        //Extracted item
        $item = Yii::$app->request->post('item');

        //Check correct data
        if (empty($item)) {
            throw new Exception(AmosDocumenti::t('amosdocumenti', 'Wrong Data, Try Later'));
            return false;
        }

        //Dir location of extracted files
        $dirLocation = $this->uploaderDir . DIRECTORY_SEPARATOR . 'extract_' . $item;

        //Array Tree of the directory
        $dirTree = $this->dirTree($dirLocation, true);

        //Env vars setup used by document generation
        $this->setupEnv();

        //Push root node if not set
        if (!in_array($dirTree['dataAttr']['path'], $this->selectedNodes)) {
            $this->selectedNodes = ArrayHelper::merge([$dirTree['dataAttr']['path']], $this->selectedNodes);
        }

        if ($communityId) {
            $communitySearch = Community::findOne(['id' => $communityId]);
            $selectedName = $communitySearch->name;

            $communityCreated = $communitySearch->id;
        }

        /** @var UploaderImportList $importation */
        $importation = $this->documentsModule->createModel('UploaderImportList');
        $importation->name_file = $selectedName;
        $importation->path_log = $importation->getPathForLog();
        $importation->save();

        //If the community is set i must override current one
        if ($communityId) {
            //Generate all docs in this tree node
            $this->treeForReport[] = $this->documentsModule->createModel('ReportNode', [
                'type' => ReportNode::COMMUNITY,
                'id' => $communityCreated,
                'name' => $selectedName ? $selectedName : 'default',
                'logfile' => $importation->path_log
            ]);

            $documentsTree = $this->createDocumentsInTree($dirTree, $communityCreated, null, true, true);
        } else {
            //Start community generation
            $communityCreated = $this->createCommunityByNode($dirTree, null, $selectedName, $importation->path_log);
        }

        if (empty($communityCreated)) {
            throw new Exception(AmosDocumenti::t('amosdocumenti', 'The community does not exists'));
            return false;
        }

        if (!is_null($this->moduleCwh)) {
            $this->oduleCwh->resetCwhMaterializatedView();
        }

        $importation->successfull = true;
        $importation->save();

        Yii::$app->db->createCommand(<<<SQL
          ALTER TABLE `attach_file` ENABLE KEYS;
          ALTER TABLE `attach_file_refs` ENABLE KEYS;
          ALTER TABLE `community` ENABLE KEYS;
          ALTER TABLE `community_user_mm` ENABLE KEYS;
          ALTER TABLE `cwh_auth_assignment` ENABLE KEYS;
          ALTER TABLE `cwh_config` ENABLE KEYS;
          ALTER TABLE `cwh_config_contents` ENABLE KEYS;
          ALTER TABLE `cwh_nodi_mt` ENABLE KEYS;
          ALTER TABLE `cwh_nodi_view` ENABLE KEYS;
          ALTER TABLE `cwh_pubblicazioni` ENABLE KEYS;
          ALTER TABLE `cwh_pubblicazioni_cwh_nodi_editori_mm` ENABLE KEYS;
          ALTER TABLE `cwh_pubblicazioni_cwh_nodi_validatori_mm` ENABLE KEYS;
          ALTER TABLE `documenti` ENABLE KEYS;
          ALTER TABLE `documenti_allegati` ENABLE KEYS;
          SET UNIQUE_CHECKS = 1;
          SET FOREIGN_KEY_CHECKS = 1;
          COMMIT;
SQL
        )->execute();

        return $this->render(
            'report',
            [
                'importation' => $importation,
                'communityCreated' => $communityCreated
            ]
        );
    }

    /**
     * @return \yii\web\Response
     * @throws CommunityException
     * @throws Exception
     */
    public function actionImport()
    {
        set_time_limit(0);

        $communityId = Yii::$app->request->get('communityId');

        if (!$communityId) {
            throw new Exception('Need communityid');
        }

        //Uploaded file data
        $getData = $this->getData();

        foreach ($getData as $file) {
            //The file location
            $uploadFilePath = $this->projectRoothPath . DIRECTORY_SEPARATOR . $file['path'];

            //Dir location of extracted files
            $dirLocation = $this->uploaderDir . DIRECTORY_SEPARATOR . 'extract_' . $file['hash'];

            //Extract the zip and return true if succeed
            $result = $this->extractZip($uploadFilePath, $dirLocation);

            // If the file is successfully extracted, redirect to the view, otherwise an error will be displayed
            if ($result == '0') {
                return $this->redirect([
                    'choose-nodes',
                    'item' => $file['hash'],
                    'communityId' => $communityId
                ]);
            } else {
                throw new Exception(AmosDocumenti::t('amosdocumenti', 'Unable to Unzip File') . ' ' . $uploadFilePath);
            }
        }
    }

    /**
     * Setup env vars
     */
    public function setupEnv()
    {
        //Trovo config per pubblicazione contenuto
        $this->pubblicationConfig = CwhConfigContents::findOne(['tablename' => Documenti::tableName()]);

        //require community module
        $this->communityModule = Yii::$app->getModule('community');

        //Setup attachments module
        $this->attachmentsModule = Yii::$app->getModule('attachments');
    }

    /**
     * @return array|bool|mixed
     * @throws Exception
     */
    public function getData()
    {
        $getData = Yii::$app->request->get();

        //Passed GET data wich contains files
        $getData = $getData['files'];

        //Check if files is passed correctly
        if (!is_array($getData)) {
            throw new Exception(AmosDocumenti::t('amosdocumenti', 'Invalid data, try later'));
            return false;
        }

        return $getData;
    }

    /**
     * @param $uploadFilePath
     * @param $dirLocation
     * @return string
     */
    public function extractZip($uploadFilePath, $dirLocation)
    {
        set_time_limit(0);

        // Base 7zip command for extracting a compressed file
        $unzipBaseCommand = "7za x ";

        // Building the full command with the upload file path and the location directory
        // where the compressed file will be extracted
        $unzipCommand = $unzipBaseCommand . $uploadFilePath . ' -o' . $dirLocation;

        // Executing the unzip command build above
        exec($unzipCommand);

        // Catching the result of the command run above
        return exec('echo $?');
    }

    /**
     * @param array $node The Tree node for the community
     * @param int $parentCommunity The parent community
     * @param string $communityName Override the name of the community
     * @return mixed
     * @throws Exception|CommunityException
     */
    protected function createCommunityByNode($node, $parentCommunity = null, $communityName = null, $logfile = null)
    {
        //Create the new community
        $this->treeForReport[] = $this->documentsModule->createModel('ReportNode', [
            'type' => ReportNode::COMMUNITY,
            'id' => $communityId,
            'name' => $communityName ? $communityName : $node['text'],
            'logfile' => $logfile
        ]);

        try {
            $communityId = $this->createCommunity($node, $parentCommunity, $communityName);
            if (!$communityId) {
                throw new Exception(AmosDocumenti::t('amosdocumenti', 'Community not created ') . $node);
                return false;
            }
//            $this->treeForReport[] = new ReportNode([
//                'type' => ReportNode::COMMUNITY,
//                'id' => $communityId,
//                'name' => $communityName ? $communityName : $node['text'],
//                'logfile' => $logfile
//            ]);
        } catch (Exception $e) {
//            $this->treeForReport[] = new ReportNode([
//                'type' => ReportNode::COMMUNITY,
//                'id' => $communityId,
//                'name' => $communityName ? $communityName : $node['text'],
//                'logfile' => $logfile
//            ]);

            return null;
        }

        // Generate all docs in this tree node
        $documentsTree = $this->createDocumentsInTree($node, $communityId, null, true);

        return $communityId;
    }

    /**
     * @param array $node
     * @param int $parentCommunity
     * @param string $communityName Override the community name
     * @return int
     * @throws CommunityException
     */
    protected function createCommunity($node, $parentCommunity = null, $communityName = null)
    {
        //Set community name or override with user selected name
        $name = empty($communityName) ? $node['text'] : $communityName;

        //Create first community
        $communityId = $this->communityModule->createCommunity(
            $name,
            CommunityType::COMMUNITY_TYPE_CLOSED,
            Community::className(),
            CommunityUserMm::ROLE_COMMUNITY_MANAGER,
            '',
            null,
            CommunityUserMm::STATUS_ACTIVE,
            Yii::$app->user->getId()
        );

        //if creation goes wrong the id is a FALSE
        if (!$communityId) {
            throw new Exception(AmosDocumenti::t('amosdocumenti', 'Unable to create the community'));
        }

        //the new community record
        $communityRecord = Community::findOne(['id' => $communityId]);

        //Set Community Validated
        $communityRecord->status = Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED;
        $communityRecord->validated_once = true;

        //Set Parent community
        if ($parentCommunity) {
            //Set parent and save
            $communityRecord->parent_id = $parentCommunity;
        }

        $communityRecord->save(false);

        return $communityId;
    }

    /**
     * @param array $node The Tree node for the community
     * @param int $communityId the owner community
     * @param Documenti|null $parentDoc The parent document dir
     * @param boolean $skipRoot the root node has to be skipped?
     * @param boolean $override Override existing communities if in update mode
     * @throws Exception|CommunityException
     */
    protected function createDocumentsInTree($node, $communityId, $parentDoc = null, $skipRoot = false, $override = false)
    {
        //Directory config data
        $directoryData = [
            'name' => $node['text'],
            'path' => $node['dataAttr']['path']
        ];

        //The first level for each community has to be skipped
        if (!$skipRoot && !in_array($node['dataAttr']['path'], $this->selectedNodes)) {
            //Create node directory
            $nodeDirectory = $this->createDocument($directoryData, $parentDoc, true, $communityId);
            //Avoid to create files under the directory that failed to be created
            if (is_null($nodeDirectory)) {
                return false;
            }
            //Attach cwh rules to this document
        } else {
            $nodeDirectory = null;
        }

        //Create all attached documents
        foreach ($node['files'] as $file) {
            //Directory config data
            $documentData = [
                'name' => $file['name'],
                'path' => $file['path']
            ];

            //Create the document
            $documento = $this->createDocument($documentData, $nodeDirectory, false, $communityId);
        }

        //Create sub nodes
        foreach ($node['nodes'] as $subNode) {
            if (in_array($subNode['dataAttr']['path'], $this->selectedNodes)) {
                //Check if the community exists (only in override mode)
                $communityExists = false;

                if ($override) {
                    //the new community record
                    $communityRecord = Community::findOne(['name' => $subNode['text'], 'parent_id' => $communityId]);

                    //Set parent and save
                    $communityExists = ($communityRecord && $communityRecord->id);

                    if ($communityExists) {
                        $this->treeForReport[] = $this->documentsModule->createModel('ReportNode', [
                            'type' => ReportNode::COMMUNITY,
                            'id' => $communityRecord->id,
                            'name' => $communityRecord->name,
                            'logfile' => null
                        ]);

                        //Create only a new tree in documents
                        $this->createDocumentsInTree($subNode, $communityRecord->id, $nodeDirectory, false, $override);
                    }
                }

                if (!$communityExists) {
                    //Create a new community for this node
                    $this->createCommunityByNode($subNode, $communityId);
                }
            } else {
                //Create only a new tree in documents
                $this->createDocumentsInTree($subNode, $communityId, $nodeDirectory, false, $override);
            }
        }

        if (!$skipRoot || !$override) {
            /** @var ReportNode $reportNodeModel */
            $reportNodeModel = $this->documentsModule->createModel('ReportNode');
            $reportNodeModel::popDirectoryPath($node['text']);
        }

        //Return this node
        return $nodeDirectory;
    }

    /**
     * @param array $documentData the data for this file as and array
     * @param Community $community
     * @param Documenti $parentDoc The parent document
     * @param bool $isDir is a directory or file document
     * @return Documenti|boolean|array
     */
    public function createDocument($documentData, $parentDoc = null, $isDir = false, $communityId = null, $status = null)
    {
        //Create the new document
        try {
            //Check if the document already exists
            $documentExists = $this->documentExists($documentData, $parentDoc, $isDir, $communityId);

            if (!$documentExists && !($documentExists instanceof Documenti)) {
                /** @var Documenti $documento */
                $documento = $this->documentsModule->createModel('Documenti');
                $documento->titolo = $documentData['name'];
                $documento->created_by = $documento->updated_by = Yii::$app->getUser()->id;
                $documento->created_at = date('Y-m-d H:i:s');
                $documento->status = $status;
                $documento->is_folder = $isDir;

                //If no parent is set
                if (!is_null($parentDoc)) {
                    $documento->parent_id = $parentDoc->id;
                }

                $documento->detachBehaviors();
                $saved = $documento->save(false);
                $this->addCwhRules($documento, $communityId);
            } elseif ($documentExists instanceof Documenti) {
                //DocumentExists is the document in case exists
                $documento = $documentExists;

                //The current Attachment
                $currAtt = $documento->documentMainFile;

                //Drop Attachment
                $currAtt ? $currAtt->delete() : null;

                //Nothing to do for save
                $saved = true;
            } else {
                return [
                    'error' => AmosDocumenti::t('amosdocumenti', "File Not Attachable {$documentData['name']}")
                ];
            }

            //Attach file to module
            if (!$isDir && $saved) {
                $attached = $this->attachmentsModule->attachFile(
                    urldecode($documentData['path']),
                    $documento,
                    'documentMainFile'
                );

                //Check if the file is attached
                if (!$attached) {
                    throw new Exception(AmosDocumenti::t('amosdocumenti', 'Unable to attach file'));
                }
            }

            $this->treeForReport[] = $this->documentsModule->createModel('ReportNode', [
                'type' => $isDir ? ReportNode::DIRECTORY : ReportNode::FILE,
                'id' => !empty($documento->id) ? $documento->id : null,
                'name' => $documentData['name']
            ]);
        } catch (\Exception $e) {
            $this->treeForReport[] = $this->documentsModule->createModel('ReportNode', [
                'type' => $isDir ? ReportNode::DIRECTORY : ReportNode::FILE,
                'id' => null,
                'name' => $documentData['name']
            ]);

            return $e->getMessage();
        }


        //The created document
        return $documento;
    }

    /**
     * @param Documenti $documento
     * @param int $communityId
     * @return CwhPubblicazioniCwhNodiEditoriMm
     */
    public function addCwhRules($documento, $communityId)
    {
        //Cwh pubblication row
        $cwhPubblicazione = new CwhPubblicazioni();
        $cwhPubblicazione->cwh_regole_pubblicazione_id = CwhActiveQuery::RULE_NETWORK;
        $cwhPubblicazione->cwh_config_contents_id = $this->pubblicationConfig->id;
        $cwhPubblicazione->content_id = $documento->id;
        $cwhPubblicazione->detachBehaviors();
        $cwhPubblicazione->save(false);

        //Connect cwh config with community and document
        $editorCwh = new CwhPubblicazioniCwhNodiEditoriMm();
        $editorCwh->cwh_config_id = 3;
        $editorCwh->cwh_network_id = $communityId;
        $editorCwh->cwh_pubblicazioni_id = $cwhPubblicazione->id;
        $editorCwh->cwh_nodi_id = "community-" . $communityId;
        $editorCwh->detachBehaviors();
        $editorCwh->save(false);

        //Cwh Validators node
        $validatorCwh = new CwhPubblicazioniCwhNodiValidatoriMm();
        $validatorCwh->cwh_config_id = 3;
        $validatorCwh->cwh_network_id = $communityId;
        $validatorCwh->cwh_pubblicazioni_id = $cwhPubblicazione->id;
        $validatorCwh->cwh_nodi_id = "community-" . $communityId;
        $validatorCwh->detachBehaviors();
        $validatorCwh->save(false);

        //Return the mm row
        return $editorCwh;
    }

    /**
     *
     * @param type $documentData
     * @param type $parentDoc
     * @param type $isDir
     * @param type $communityId
     * @return type
     */
    protected function documentExists($documentData, $parentDoc, $isDir, $communityId)
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');

        /**
         * @var $find ActiveQuery
         */
        $find = $documentiModel::find();

        //CWH Pubblicazioni Join as bridge to communioty MM
        $find->innerJoin(
            CwhPubblicazioni::tableName(),
            "`content_id` = `" . Documenti::tableName()
            . "`.`id` AND `cwh_config_contents_id` = {$this->pubblicationConfig->id} 
            AND `cwh_regole_pubblicazione_id` = " . CwhActiveQuery::RULE_NETWORK
        );

        //Join with community
        $find->innerJoin(
            CwhPubblicazioniCwhNodiEditoriMm::tableName(),
            "`cwh_config_id` = 3 
            AND `cwh_network_id` = {$communityId} 
            AND `cwh_pubblicazioni_id` = `" . CwhPubblicazioni::tableName() . "`.`id` 
            AND `cwh_nodi_id` = 'community-{$communityId}'
        ");

        $find->andWhere([
            'titolo' => $documentData['name'],
            'parent_id' => $parentDoc
        ]);

        return $find->one();
    }

    /**
     * @param $path
     * @param $withFiles Put files into array?
     * @return array
     */
    protected function dirTree($path, $withFiles = false, $skipRoot = false)
    {
        //Scan dir and return tree
        $dirContent = scandir($path);

        //Sort result
        natcasesort($dirContent);

        //Current Dir Base name
        $baseName = basename($path);

        //List of directories
        $dirs = [
            'text' => utf8_encode($baseName),
            'hideCheckbox' => $skipRoot,
            'dataAttr' => [
                'path' => urlencode($path)
            ],
            'nodes' => [],
            'files' => []
        ];

        //Parse dirs
        foreach ($dirContent as $item) {
            if (!in_array($item, ['.', '..'])) {
                if (is_dir($path . '/' . $item)) {
                    $dirs['nodes'][] = $this->dirTree($path . '/' . ($item), $withFiles);
                } elseif ($withFiles) {
                    $dirs['files'][] = [
                        'name' => utf8_encode($item),
                        'path' => urlencode($path . '/' . $item)
                    ];
                }
            }
        }

        return $dirs;
    }

    /**
     * @return string
     */
    public function actionImportList()
    {
        /** @var UploaderImportList $uploaderImportListModel */
        $uploaderImportListModel = $this->documentsModule->createModel('UploaderImportList');
        $dataProvider = new ActiveDataProvider([
            'query' => $uploaderImportListModel::find()->orderBy('created_at DESC')
        ]);

        return $this->render(
            'import_list',
            ['dataProvider' => $dataProvider]
        );
    }

    /**
     * @param int $id
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGenerateExcel($id)
    {
        /** @var UploaderImportList $uploaderImportListModel */
        $uploaderImportListModel = $this->documentsModule->createModel('UploaderImportList');
        $import = $uploaderImportListModel::findOne($id);

        if ($import) {
            /** @var ReportNode $reportNodeModel */
            $reportNodeModel = $this->documentsModule->createModel('ReportNode');
            $reportNodeModel::generateExcellFromFile($import->path_log);
        }
    }
}
