<?php

namespace common\modules\transformermanagers;

use open20\amos\community\models\Community;
use open20\amos\cwh\AmosCwh;
use open20\elasticsearch\transformer\AbstractTransformer;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;

class CommunityMtoETransformer extends BaseModelToElasticTransformer {
    /* @var $model Community */

    public function transform() {
        $values = [];
        $values['name'] = $this->filterString($this->model->name);
        $values['description'] = $this->filterString($this->model->description);
        $values['public'] = "0";
        $values['start_publication'] = date("c", strtotime($this->model->created_at));
        $values = $this->statusEvaluation($values);
        return $values;
    }


    public function cwhEvaluation($values) {
        $ret = $values;

        $cwhmodule = AmosCwh::instance();
        if (!is_null($cwhmodule)) {
            if ($this->model->community_type_id > 1) {
                $ret[AbstractTransformer::NETWORKS_INDEX] = "community" . $this->model->id;
            } else {
                $ret[AbstractTransformer::NETWORKS_INDEX] = AbstractTransformer::PLATFORM_TAG . " ";
            }
        }
        $ret['tags'] = $this->getTags();
        return $ret;
    }

}
