<?php
namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;
use yii\helpers\ArrayHelper;

/**
 * Social Block.
 *
 */
final class SocialBlock extends BaseUikitBlock
{

    /**
     *
     * @inheritdoc
     */
    public $component = "social";

    /**
     *
     * @inheritdoc
     */
    public function blockGroup()
    {
        return LegacyGroup::class;
    }

    public function disable()
    {
        return 0;
    }

    /**
     *
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_block_social');
    }

    /**
     *
     * @inheritdoc
     */
    public function icon()
    {
        return 'share-variant-outline';
    }

    /**
     *
     * @inheritdoc
     */
    public function frontend(array $params = array())
    {
        if (count($this->getVarValue('items', []))) {
            return parent::frontend($params);
        } else {
            return "";
        }
    }

    /**
     *
     * @inheritdoc
     */
    public function admin()
    {
        if ($output = $this->frontend()) {
            return $output;
        } else {
            return '<div><span class="block__empty-text">' . Yii::t('backendobjects', 'block_module_block_social_no_content') . '</span></div>';
        }
    }
}
