<?php

/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 11/05/2021
 * Time: 12:34
 */

namespace open20\amos\documenti\widgets;

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCartellePath;
use open20\amos\documenti\assets\ModuleFileExplorerAsset;
use open20\amos\core\icons\AmosIcons;
use yii\base\Widget;
use yii\helpers\Url;
use yii\db\Expression;
use Yii;

class FileExplorerWidget extends Widget {

    public $folder = null;  
    
    //params
    public $defaultFolderColumns = 3;
    public $advancedSearch = false;
    public $withSearch = true;
    public $showUpdateDate = true;
    public $pagination = false;
    public $pageSize = 10;
    public $hideFolder = false;
    public $hideCategory = false;
    public $hideBreadcrumb = false;
    /* [
     *   'titolo' => '1'
     *   ]
     */
    public $filters = [];
    
    public $defaultFilters = null;
    
    private $module = null;
    private $model = null;
    private $folders = null;
    private $files = null;
    private $breadcrumbs = [];
    private $params;
    
    public function init() {
        parent::init();
        
        if(empty(Yii::$app->params['bsVersion']))
            Yii::$app->params['bsVersion'] = 4;
        if(empty(Yii::$app->params['bsDependencyEnabled']))
            Yii::$app->params['bsDependencyEnabled'] = false;
        
        if($this->pageSize <= 0)
            $this->pageSize = 10;
        
        $this->module = \Yii::$app->getModule(AmosDocumenti::getModuleName());   
        $this->registerAssets();
        
        if(empty($this->defaultFilters))
            $this->defaultFilters = [
                'and',
                ['documenti.deleted_at'=>null],
                ['<=', 'data_pubblicazione', new \yii\db\Expression('NOW()')],
                [
                    'or',
                    ['data_rimozione' => null],
                    ['>=', 'data_rimozione', new \yii\db\Expression('NOW()')],
                ]
            ];

        $model = $this->module->createModel('Documenti');
        $this->model = $model::findOne(['id'=>$this->folder]);

        $this->createBreadcrumbs();
        $this->searchFolders();
        $this->searchFiles();
    }

    protected function registerAssets() {

        $view = $this->getView();
        
        $this->params = [
            'defaultFolderColumns' => $this->defaultFolderColumns,
            'withSearch' => $this->withSearch,
            'advancedSearch' => $this->advancedSearch,
            'showUpdateDate' => $this->showUpdateDate,
            'pagination' => $this->pagination,
            'pageSize' => $this->pageSize,
            'hideFolder' => $this->hideFolder,
            'hideCategory' => $this->hideCategory,
            'hideBreadcrumb' => $this->hideBreadcrumb,
        ];
        
        $view->registerJsVar('fileExplorerWidgetParams', $this->params);

        ModuleFileExplorerAsset::register($view);
    }
    
    protected function createBreadcrumbs() {

        $parent = empty($this->folder) ? 0 : $this->folder;
        //LOOP FINO ALLA BASE DELL'ALBERO
        while ($parent != 0) {

            $rif = Documenti::find()
                    ->where(['id' => $parent,'is_folder'=>1,'status'=>Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
                    ->andWhere($this->defaultFilters)
                    ->one();
            if (isset($rif)) {
                $parent = empty($rif->parent_id) ? 0 : $rif->parent_id;
            } else
                break;

            $tree[] = $rif;
        } //LOOP PER OGNI LIVELLO DELL'ALBERO

        $tree[] = [
            'id' => 0,
            'titolo' => 'Home',
            'is_home' => true,
        ];

        $this->breadcrumbs = array_reverse($tree, true);
        
    }
    
    protected function searchFolders() {

        $parent_id = empty($this->folder) ? null : $this->folder;
        
        $model = $this->module->createModel('Documenti');
        $query = $model::find();
        $query->where(['parent_id'=>$parent_id,'is_folder'=>1]);
        $query->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
        $query->andWhere($this->defaultFilters);
        
        $query->orderBy('titolo ASC');
        
        $this->folders = $query->all();
        
    }
    
    protected function searchFiles() {

        $parent_id = empty($this->folder) ? null : $this->folder;
        
        $model = $this->module->createModel('Documenti');
        $query = $model::find();
        $query->where(['parent_id'=>$parent_id,'is_folder'=>0]);
        $query->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
        $query->andWhere($this->defaultFilters);
        if(!empty($this->filters))
            $this->queryFilters($query,$parent_id);

        $query->orderBy('year DESC,titolo ASC');
        
        $this->files = $query->all();

    }
    
    protected function queryFilters($query,$parent_id) {
             
        foreach($this->filters as $attribute=>$val){
                   
            switch($attribute){
                
                case 'descrizione':
                    $query->andWhere([
                        'or',
                        ['like', 'titolo', $val],
                        ['like', 'sottotitolo', $val],
                        ['like', 'descrizione', $val],
                        ['like', 'descrizione_breve', $val],
                        
                    ]);
                break;
            
                case 'documenti_categorie_id':
                    $query->andWhere([$attribute => $val]);
                break;
            
                case 'ricerca_sottocartelle':
                    if((bool)$val){
                        unset($query->where[1]['parent_id']);
                        $query->innerJoin(
                        DocumentiCartellePath::tableName(),
                        Documenti::tableName() . '.id = ' . DocumentiCartellePath::tableName() . '.id_doc_folder')
                        ->andWhere([DocumentiCartellePath::tableName() . '.id_folder' => $parent_id]);
                       
                    }
                break;
                
                case 'extensions':
                    $query->leftJoin('attach_file', new Expression('attach_file.item_id = documenti.id AND model = "open20\\\\amos\\\\documenti\\\\models\\\\Documenti"'));   
                    $query->andWhere(['attach_file.type' => $val]);
                break;
            
                case 'data_pubblicazione_from':              
                    $query->andFilterWhere([
                        '>=', 'data_pubblicazione', $val,
                    ]);
                break;
                case 'data_pubblicazione_to':
                    $query->andFilterWhere([
                        '<=', 'data_pubblicazione', $val,
                    ]);
                break;
                
                default:
                    $query->andWhere(['like', $attribute, $val]);
            }
  
        }
  
    }

    public function run() {
 
        return $this->render('file_explorer/file_explorer', [
            'module' => $this->module,
            'model' => $this->model,                
            'breadcrumbs' => $this->breadcrumbs,
            'folders' => $this->folders,
            'files' => $this->files,
            'filters' => $this->filters,
            'params' => $this->params,    
         
        ]);
    }

}
