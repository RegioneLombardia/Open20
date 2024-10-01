<?php

namespace app\modules\cmsapi\frontend\utility\cmspageblock;

use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\utility\CmsBlocksBuilder;
use yii\helpers\Json;

class CmsBackEndModulesPageBlock extends CmsPageBlock
{

    public function buildValues(PostCmsCreatePage $postCmsPage)
    {
        $values                    = Json::decode($this->json_config_values);
        $values['conditionSearch'] = "['tag_id' => ".$postCmsPage->event_data->event_id."]";
        $this->json_config_values  = Json::encode($values);
    }



    /**
     *
     * @param type $nav_item_page_id
     * @return type
     */
    public static function findBlocks($nav_item_page_id)
    {
        $id_block = static::findBlock(CmsBlocksBuilder::BACKENDMODULE);
        $blocks   = static::find()->
            andWhere(['nav_item_page_id' => $nav_item_page_id])->
            andWhere(['block_id' => $id_block->id])
            ->all();
        return $blocks;
    }
}