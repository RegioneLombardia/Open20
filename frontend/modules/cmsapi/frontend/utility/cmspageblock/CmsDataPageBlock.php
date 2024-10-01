<?php

namespace app\modules\cmsapi\frontend\utility\cmspageblock;

use app\modules\cmsapi\frontend\models\PostCmsCreatePage;
use app\modules\cmsapi\frontend\utility\CmsBlocksBuilder;
use yii\helpers\Json;

class CmsDataPageBlock extends CmsPageBlock
{

    public function buildValues(PostCmsCreatePage $postCmsPage)
    {
        $values                    = Json::decode($this->json_config_values);
        $variables = &$values['items'];
        $variables[] = ['variable' => 'community_id','value' => $postCmsPage->form_landing->community_id];
        $this->json_config_values  = Json::encode($values);
    }

    /**
     *
     * @param type $nav_item_page_id
     * @return array
     */
    public static function findBlocks($nav_item_page_id)
    {
        $id_block = static::findBlock(CmsBlocksBuilder::DATA);
        $blocks   = static::find()->
            andWhere(['nav_item_page_id' => $nav_item_page_id])->
            andWhere(['block_id' => $id_block->id])
            ->all();
        return $blocks;
    }

    /**
     *
     * @return type
     */
    public function getCfgValues()
    {
        $values = [];
        $items  = Json::decode($this->json_config_values);
        if (!empty($items['items'])) {
            foreach ($items['items'] as $item) {
                $values[$item['variable']] = $item['value'];
            }
        }
        return $values;
    }
}