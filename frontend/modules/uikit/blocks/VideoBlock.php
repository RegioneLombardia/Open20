<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;
use yii\helpers\ArrayHelper;


/**
 * Embed YouTube and Vimeo video Block.
 *
 * @since 1.0.0
 */
final class VideoBlock extends BaseUikitBlock
{
    const PROVIDER_YOUTUBE = 'youtube';
    
    const PROVIDER_YOUTUBE_EMBED_URL = 'https://www.youtube.com/embed/';
    
    const PROVIDER_VIMEO = 'vimeo';
    
    const PROVIDER_VIMEO_EMBED_URL = 'https://player.vimeo.com/video/';
    
    /**
     * @inheritdoc
     */
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_video_name');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'video-outline';
    }
    
    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return ContenutoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'url', 'label' => Yii::t('backendobjects', 'block_video_url_label'),
                    'description'=> 'Inserisci l\'URL del video esterno che vuoi presentare. Sono supportati i video di Youtube e Vimeo.',
                    'type' => self::TYPE_TEXT
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
                    'var' => 'width', 
                    'label' => Yii::t('backendobjects', 'block_video_width_label'), 
                    'description'=> 'Scegli la larghezza assoluta in pixel del video.',
                    'type' => self::TYPE_NUMBER
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFieldHelp()
    {
        return [
            'url' => Yii::t('backendobjects', 'block_video_help_url'),
            'controls' => Yii::t('backendobjects', 'block_video_help_controls'),
            'width' => Yii::t('backendobjects', 'block_video_help_width'),
        ];
    }
    
    /**
     * Ensure the emebed youtube url based on url var.
     *
     * @return string
     */
    public function embedYoutube()
    {
        parse_str(parse_url($this->getVarValue('url'), PHP_URL_QUERY), $args);
        // ensure if v argument exists
        if (isset($args['v'])) {
            $params['rel'] = 0;
            if ($this->getCfgValue('controls')) {
                $params['controls'] = 0;
            }
            return self::PROVIDER_YOUTUBE_EMBED_URL . $args['v'] . '?' . http_build_query($params);
        }
        return $this->getVarValue('url');
    }
    
    /**
     * Ensure the emebed vimeo url based on url var.
     *
     * @return string
     */
    public function embedVimeo()
    {
        return self::PROVIDER_VIMEO_EMBED_URL . ltrim(parse_url($this->getVarValue('url'), PHP_URL_PATH), '/');
    }

    /**
     * Construct the url based on url input.
     *
     * @return string
     */
    public function constructUrl()
    {
        if ($this->getVarValue('url')) {
            preg_match('/(?:www\.)?([a-z]+)(?:\.[a-z]+)?/', parse_url($this->getVarValue('url'), PHP_URL_HOST), $match);
            if (isset($match[1])) {
                switch ($match[1]) {
                    case self::PROVIDER_YOUTUBE: return $this->embedYoutube();
                    case self::PROVIDER_VIMEO: return $this->embedVimeo();
                }
            }
        }
        
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function extraVars()
    {
        return [
            'url' => $this->constructUrl(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        return '{% if extras.url is not empty %}<div class="iframe-container"><iframe src="{{ extras.url }}" frameborder="0" allowfullscreen></iframe></div>{% else %}<span class="block__empty-text">' . Yii::t('backendobjects', 'block_video_no_video') . '</span>{% endif %}';
    }
}
