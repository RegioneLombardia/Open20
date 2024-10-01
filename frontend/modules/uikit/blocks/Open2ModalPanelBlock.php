<?php

namespace app\modules\uikit\blocks;

use Yii;

use app\modules\cms\utility\StorageUtility;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use open20\amos\documenti\models\Documenti;
use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use yii\helpers\ArrayHelper;



final class Open2ModalPanelBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    protected $component = "open2modalpanel";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return ContenutoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_modalpanel');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'application-outline';
    }
    
    public function extraVars()
    {
        $attachments = [];
        $allegati = $this->getVarValue('allegati', []);
        
        foreach($allegati as $allegato){
            
            $link = ['url'=>'','name'=>'','target'=>'_blank','type'=>''];
            
            $fileSystem = new StorageUtility();              
            $infos = $fileSystem->getFileInfo($allegato['link']);
            
            if(isset($infos['type']))
                $link['type'] = $infos['type'];
            if(isset($infos['url']))
                $link['url'] = $infos['url'];
            if(isset($infos['name']))
                $link['name'] = $infos['name'];
           
            
            $attachments[] = $link;
        }
        
        return ['allegati' => $attachments];
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        //return $this->frontend();
        return '<div><span class="block__empty-text">'.Module::t('Finestra modale').'</span></div>';
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
