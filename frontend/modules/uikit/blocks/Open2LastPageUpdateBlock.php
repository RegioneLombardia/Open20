<?php
namespace app\modules\uikit\blocks;

use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\InformativoGroup;
use luya\cms\models\NavItemPage;
use trk\uikit\Uikit;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\uikit\Module;



class Open2LastPageUpdateBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "open2lastpageupdate";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return InformativoGroup::className();
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_backend_last-page-update');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'cog-outline';
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        return '<div><span class="block__empty-text">' . Module::t('Data dell\'ultimo aggiornamento di pagina.') . '</span></div>';
        
    }

    public function frontend(array $params = array()) 
    {
        $date_update = null;
        $time_update = null;
       
        $blockId = $this->getEnvOption('id');
        $params['blockItemId'] = $blockId;
        
        $configs = $this->getValues();
        $configs["id"] = Uikit::unique($this->component);
        $params['data'] = Uikit::configs($configs);
        $params['debug'] = $this->config();
        $params['filters'] = $this->filters;
        
        $nav = $this->getEnvOption('pageObject')->one();
        if (!is_null($nav)) 
        {
            $page = NavItemPage::findOne(['nav_item_id' => $nav->id]);
            if (!is_null($page)) 
            {
                $date_update = Yii::$app->formatter->asDate($nav->timestamp_update);
                $time_update = Yii::$app->formatter->asTime($nav->timestamp_update);
            }
        }
        $params['date_update'] = $date_update;
        $params['time_update'] = $time_update;
        return $this->view->render($this->getViewFileName('php'), $params, $this);
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
