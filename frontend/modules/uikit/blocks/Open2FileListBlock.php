<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use luya\cms\helpers\BlockHelper;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\ListatoContenutiGroup;
use yii\helpers\ArrayHelper;

/**
 * File list block.
 *
 * @since 1.0.0
 */
final class Open2FileListBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    public $cacheEnabled = true;
    
    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return ListatoContenutiGroup::class;
    }
    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_block_file_list_name');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'attachment';
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'files',
                    'label' => 'Allegati',
                    'description' => 'Se viene compilato il nome sostitutivo dell\'allegato ne sostituisce il nome originario in visualizzazione',
                    'type' => 'zaa-multiple-inputs',
                    'options' => [
                        [
                            'var' => 'file', 
                            'label' => Yii::t('backendobjects', "File"),
                            'description' => Yii::t('backendobjects', "Seleziona gli allegati da inserire nel listato: potrai cambiarne il nome visualizzato ed aggiungervi una breve descrizione."),
                            'type' => 'zaa-file-upload'
                        ],
                        [
                            'var' => 'nome_sostitutivo', 
                            'label' => Yii::t('backendobjects', "Nome sostitutivo dell'allegato"),
                            'description' => Yii::t('backendobjects', "Se compilato è il nome del file che viene visualizzato nell'elenco."),
                            'type' => 'zaa-text'
                        ],
                        [
                            'var' => 'description', 
                            'label' => Yii::t('backendobjects', "Descrizione dell'allegato"),
                            'description' => Yii::t('backendobjects', "Se compilato è la descrizione che viene visualizzata nell'elenco."),
                            'type' => 'zaa-wysiwyg'
                        ],
                    ],
                ],
                
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
            'cfgs' => [
                [
                    'var' => 'showType',
                    'label' => Yii::t('backendobjects', "block_file_list_files_showtype_label"),
                    'initvalue' => 0,
                    'type' => 'zaa-select',
                    'description' => Yii::t('backendobjects', "Se selezionato per ogni file mostrerà la sua estensione. Di default non è selezionato, e quindi l'estensione non si vede."),
                    'options' => [
                        ['value' => '1', 'label' => Yii::t('backendobjects', "block_file_list_showtype_yes")],
                        ['value' => '0', 'label' => Yii::t('backendobjects', "block_file_list_showtype_no")],
                    ],
                ],
                [
                    'var' => 'showSize',
                    'label' => Yii::t('backendobjects', "block_file_list_files_showsize_label"),
                    'initvalue' => 0,
                    'type' => 'zaa-select',
                    'description' => Yii::t('backendobjects', "Se selezionato per ogni file mostrerà il nome il suo peso. Di default non è selezionato, e quindi il peso non si vede."),
                    'options' => [
                        ['value' => '1', 'label' => Yii::t('backendobjects', "block_file_list_showtype_yes")],
                        ['value' => '0', 'label' => Yii::t('backendobjects', "block_file_list_showtype_no")],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function extraVars()
    {
        return [
            'fileList' => $this->getVarValue('files'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        if (count($this->getVarValue('files', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">'.Module::t('Nessun documento inserito.').'</span></div>';
        }
    }
}
