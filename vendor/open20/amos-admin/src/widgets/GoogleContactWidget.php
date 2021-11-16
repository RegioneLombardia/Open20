<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets
 * @category   CategoryName
 */

namespace open20\amos\admin\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\icons\AmosIcons;
use yii\base\Widget;

/**
 * Class GoogleContactWidget
 * @package open20\amos\admin\widgets
 */
class GoogleContactWidget extends Widget
{
    /** @var  UserProfile $model */
    public $model;

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(AmosAdmin::t('amosadmin', 'Missing model'));
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {

        if($this->model->isGoogleContact()) {
            $googleContactIcon = AmosIcons::show('google', [
                'class' => 'am-2',
                'title' => AmosAdmin::t('amosadmin', 'Google Contact')
            ]);
            return $googleContactIcon;
        }else{
            return '';
        }
    }


}