<?php
namespace app\modules\uikit\blocks;

use Yii;
use luya\TagParser;
use app\modules\uikit\BaseUikitBlock;
use yii\helpers\ArrayHelper;
use app\modules\backendobjects\frontend\blockgroups\LegacyGroup;

/**
 * Table Block.
 *
 * @since 1.0.0
 */
final class TableBlock extends BaseUikitBlock
{

    /**
     *
     * @inheritdoc
     */
    public $cacheEnabled = true;

    /**
     *
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_table_name');
    }

    public function disable()
    {
        return 0;
    }

    public function blockGroup()
    {
        return LegacyGroup::class;
    }

    /**
     *
     * @inheritdoc
     */
    public function icon()
    {
        return 'border_all';
    }

    /**
     *
     * @inheritdoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'table',
                    'label' => "",
                    'type' => 'zaa-table'
                ],
                [
                    'var' => 'visibility',
                    'label' => 'Visibilità del blocco',
                    'description' => 'Imposta la visibilità della sezione.',
                    'initvalue' => '',
                    'type' => 'zaa-select',
                    'options' => [
                        [
                            'value' => '',
                            'label' => 'Visibile a tutti'
                        ],
                        [
                            'value' => 'guest',
                            'label' => 'Visibile solo ai non loggati'
                        ],
                        [
                            'value' => 'logged',
                            'label' => 'Visibile solo ai loggati'
                        ]
                    ]
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
                            'options' => BaseUikitBlock::getClasses()
                        ]
                    ]
                ]
            ],
            'cfgs' => [
                [
                    'var' => 'header',
                    'label' => Yii::t('backendobjects', 'block_table_header_label'),
                    'type' => 'zaa-checkbox'
                ],
                [
                    'var' => 'stripe',
                    'label' => Yii::t('backendobjects', 'block_table_stripe_label'),
                    'type' => 'zaa-checkbox'
                ],
                [
                    'var' => 'border',
                    'label' => Yii::t('backendobjects', 'block_table_border_label'),
                    'type' => 'zaa-checkbox'
                ],
                [
                    'var' => 'equaldistance',
                    'label' => Yii::t('backendobjects', 'block_table_equaldistance_label'),
                    'type' => 'zaa-checkbox'
                ],
                [
                    'var' => 'parseMarkdown',
                    'label' => Yii::t('backendobjects', 'block_table_enable_markdown'),
                    'type' => 'zaa-checkbox'
                ],
                [
                    'var' => 'divCssClass',
                    'label' => Yii::t('backendobjects', 'block_cfg_additonal_css_class'),
                    'type' => self::TYPE_TEXT
                ]
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function getFieldHelp()
    {
        return [
            'table' => Yii::t('backendobjects', 'block_table_help_table')
        ];
    }

    /**
     * Get the table data for the table generator.
     *
     * @return array
     */
    public function getTableData()
    {
        $hasHeader = $this->getCfgValue('header', 0);
        $table = [];
        $i = 0;
        foreach ($this->getVarValue('table', []) as $row) {
            ++ $i;
            // whether the header data can be skipped or not
            if ($hasHeader == 1 && $i == 1) {
                continue;
            }
            // parse markdown for ceil value, if disable ensure newlines converts to br tags.
            foreach ($row as $field => $value) {
                $row[$field] = $this->getCfgValue('parseMarkdown', false) ? TagParser::convertWithMarkdown($value) : nl2br($value);
            }

            $table[] = $row;
        }

        return $table;
    }

    /**
     * Return the table header array data.
     *
     * @return array
     */
    public function getHeaderRow()
    {
        $data = $this->getVarValue('table', []);

        return (count($data) > 0) ? array_values($data)[0] : [];
    }

    /**
     *
     * @inheritdoc
     */
    public function extraVars()
    {
        return [
            'table' => $this->getTableData(),
            'headerData' => $this->getHeaderRow()
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function admin()
    {
        return '<p>{% if extras.table is empty %}<span class="block__empty-text">' . Yii::t('backendobjects', 'block_table_no_table') . '</span>{% else %}' . '<div class="table-responsive-wrapper">' . '<table class="table table-bordered table-striped table-align-middle">' . '{% if cfgs.header %}' . '<thead class="thead-inverse">' . '<tr>' . '{% for column in extras.headerData %}<th>{{ column }}</th>{% endfor %}' . '</tr>' . '</thead>' . '{% endif %}' . '<tbody>' . '{% for row in extras.table %}' . '<tr>' . '{% for column in row %}' . '<td>{{ column }}</td>' . '{% endfor %}' . '</tr>' . '{% endfor %}' . '</tbody>' . '</table>' . '</div>' . '{% endif %}';
    }
}
