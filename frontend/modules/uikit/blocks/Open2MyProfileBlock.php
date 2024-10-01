<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 */

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\Module;
use app\modules\uikit\Uikit;
use open20\amos\admin\AmosAdmin;
use app\modules\uikit\BaseUikitBlock;
use open20\amos\admin\models\UserProfile;
use app\modules\backendobjects\frontend\blockgroups\SviluppoGroup;
use open20\amos\core\record\CachedActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class MyProfileBlock
 * @package app\modules\uikit\blocks
 */
class Open2MyProfileBlock extends BaseUikitBlock
{
    /**
     * @var AmosAdmin $adminModule
     */
    public $adminModule;

    /**
     * @var UserProfile $model
     */
    public $model;
    public $totCounter = 0;
    protected $component = "myprofile";

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->cacheEnabled = false;
    }

    public function disable() {
        return 1;
    }

    /**
     * @return mixed|string
     * @throws \yii\base\InvalidConfigException
     */
    public function admin()
    {
        return $this->frontend();
    }

    public function name()
    {
        return Yii::t('backendobjects', 'block_module_backend_myprofile');
    }

    /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        return SviluppoGroup::class;
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'person';
    }

    /**
     * @param array $params
     * @return mixed|string
     * @throws \yii\base\InvalidConfigException
     */
    public function frontend(array $params = array())
    {
        if (!\Yii::$app->user->isGuest) {
            $this->adminModule = AmosAdmin::instance();
            /** @var UserProfile $userProfileModel */
            $userProfileModel  = $this->adminModule->createModel('UserProfile');
            $userProfileQuery  = $userProfileModel::find()->andWhere(['user_id' => \Yii::$app->user->id]);
            $userProfileCache  = CachedActiveQuery::instance($userProfileQuery);
            $userProfileCache->cache(60);
            $userProfile       = $userProfileCache->one();

            $this->model = $userProfile;

            if (!Uikit::element('data', $params, '')) {
                $configs           = $this->getValues();
                $configs["id"]     = Uikit::unique($this->component);
                $params['data']    = Uikit::configs($configs);
                $params['debug']   = $this->config();
                $params['filters'] = $this->filters;
            }

            $params['model']       = $this->model;
            $params['adminModule'] = $this->adminModule;
            $params['avatarUrl']   = $this->getAvatar();

            return $this->view->render('@vendor/open20/design/src/components/bootstrapitalia/views/bi-widget-myprofile-head',
                    $params);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->model->getAvatarUrl('card_users');
    }
    
    public function config() {
        $configs = [
            'vars' => [
                [
                    'var' => 'visibility',
                    'label' => 'Visibilità del blocco',
                    'description' => 'Imposta la visibilità della sezione.',
                    'initvalue' => '',
                    'type' => 'zaa-select', 'options' => [
                        ['value' => '', 'label' => 'Visibile a tutti'],
                        ['value' => 'guest', 'label' => 'Visibile solo ai non loggati'],
                        ['value' => 'logged', 'label' => 'Visibile solo ai loggati'],
                    ],
                ],
                [
                    'var' => 'addclass',
                    'label' => 'Visibilità per profilo',
                    'description' => 'Imposta la visibilità della sezione in base al profilo dell\'utente loggato',
                    'type' => 'zaa-multiple-inputs',
                    'options' => [
                        [
                            'var' => 'class',
                            'type' => 'zaa-select',
                            'initvalue' => '',
                            'options' => BaseUikitBlock::getClasses(),
                        ]
                    ],
                ],
            ],
        ];

        return ArrayHelper::merge(parent::config(), $configs);
    }
}