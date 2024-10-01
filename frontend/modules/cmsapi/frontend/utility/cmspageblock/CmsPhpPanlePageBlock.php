<?php
namespace app\modules\cmsapi\frontend\utility\cmspageblock;

use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\utility\CmsBlocksBuilder;
use app\modules\cmsapi\frontend\utility\cmspageblock\CmsPageBlock;
use yii\helpers\Json;



class CmsPhpPanlePageBlock extends CmsPageBlock
{
    public function buildValues(PostCmsCreatePage $postCmsPage)
    {
        $values    = Json::decode($this->json_config_values);
        $valuesCfg = Json::decode($this->json_config_cfg_values);
        $text      = isset($valuesCfg['first_content']) ? $valuesCfg['first_content'] : (isset($values['content']) ? $values['content'] : "");
        if ($this->parserTextEditor($text, $postCmsPage)) {
            if(!isset($valuesCfg['first_content'])){
                $valuesCfg['first_content'] = $values['content'];
            }
            $values['content'] = $text;
        }
        $this->json_config_cfg_values = Json::encode($valuesCfg);
        $this->json_config_values = Json::encode($values);
    }

    /**
     *
     * @param integer $nav_item_page_id
     * @return type
     */
    public static function findBlocks($nav_item_page_id)
    {
        $id_block = static::findBlock(CmsBlocksBuilder::PHPPANEL);
        $blocks   = static::find()->
            andWhere(['nav_item_page_id' => $nav_item_page_id])->
            andWhere(['block_id' => $id_block->id])
            ->all();
        return $blocks;
    }
}
