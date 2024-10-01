<?php

namespace app\modules\cms\admin\importers;

use Yii;
use luya\cms\models\Block;
use luya\cms\models\BlockGroup;
use luya\console\Importer;
use luya\helpers\FileHelper;
use luya\cms\base\BlockInterface;
use luya\admin\models\Config;
use luya\cms\Exception;

/**
 * CMS Blocks Importer.
 *
 * @since 1.0.0
 */
class BlockImporter extends \luya\cms\admin\importers\BlockImporter
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        
        // when the setup timestamp is not yet set, its a fresh installation, therefore skip the 1.0.0 generic block upgrade
        // otherwise its an existing/upgrading instance which is doing the import command.
        if (!Config::has(Config::CONFIG_SETUP_COMMAND_TIMESTAMP)) {
            Config::set('100genericBlockUpdate', true);
        }
        
        if (!Config::has('100genericBlockUpdate')) {
            throw new Exception("You have to run the generic block updater. ./vendor/bin/luya cms/updater/generic");
        }
        
        $exists = [];

        foreach ($this->getImporter()->getDirectoryFiles('blocks') as $file) {
            $exists[] = $this->saveBlock($file['ns']);
        }
    
        foreach (Yii::$app->packageInstaller->configs as $config) {
            continue; //switch all other blocks not in cms uikit
            $exists = array_merge($exists, $this->handleBlockDefinitions($config->blocks));
        }
    
        // provide backwards compatibility for core 1.0.7 and below
        if ($this->hasProperty('module')) {           
            $exists = array_merge($exists, $this->handleBlockDefinitions($this->module->blocks));
        }
    
        foreach (Block::find()->all() as $block) {
            if (!class_exists($block->class)) {
                $this->addLog("[!] The block {$block->class} used {$block->usageCount} times, does not exists anymore. You should either use migrate or cleanup command.");
            }
            
            $path = explode("\\",$block->class);
            if($path[1] != 'app'){
                $block->is_disabled = 1;
                $block->save(false);
                $this->addLog("[!] The block {$block->class} must not be imported, it is been disabled");
            }
        }
        
        // remove unused block groups
        foreach (BlockGroup::find()->andWhere(['not in', 'id', $this->blockGroupIds])->all() as $oldBlockGroup) {
            if ($oldBlockGroup->delete()) {
                $this->addLog('Old blockgroup has been deleted: ' . $oldBlockGroup->name);
            }
        }
        
        return $this->addLog("Block importer finished with ".count($exists) . " blocks.");
    }
    
    private $blockGroupIds = [];
    /**
     * Save a block by its given full class name.
     *
     * Example full class name: luya\cms\blocks\ModuleBlock
     *
     * @param string $fullClassName
     * @return number
     */
    protected function saveBlock($fullClassName)
    {
        // ensure all classes start with trailing slash class name definition like `\foo\bar\Class`
        $fullClassName = '\\'  . ltrim($fullClassName, '\\');
        $model = Block::find()->where(['class' => $fullClassName])->one();
        
        $blockObject = $this->createBlockObject($fullClassName);
        
        $blockGroupId = $this->getBlockGroupId($blockObject);
        
        if (!in_array($blockGroupId, $this->blockGroupIds)) {
            $this->blockGroupIds[] = $blockGroupId;
        }
        
        $log = "block {$fullClassName}: ";
        if (!$model) {
            $model = new Block();
            $model->group_id = $blockGroupId;
            $model->class = $fullClassName;           
            if(method_exists($blockObject, 'disable')){
                $model->is_disabled = $blockObject->disable();
            }
            if ($model->save()) {
                $log .= "Added to database";
            } else {
                $log .= "Error while saving";
            }
        } elseif ($model->group_id != $blockGroupId) {
            $log .= 'Updated group id"';
            $model->updateAttributes(['group_id' => $blockGroupId]);            
            if(method_exists($blockObject, 'disable')){
                $model->updateAttributes(['is_disabled' => $blockObject->disable()]);
            }
        } else {
            if(method_exists($blockObject, 'disable')){
                $model->updateAttributes(['is_disabled' => $blockObject->disable()]);
            }
            $log .= 'remains the same, nothing to update';
        }
        $this->addLog($log);
        
        return $model->id;
    }
        
}
