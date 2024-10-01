<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\backendobjects\frontend\blockgroups\SocialGroup;
use yii\httpclient\Client;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Social Block.
 *
 */
final class SocialWallBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    public $component = "socialwall";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        if (empty(\Yii::$app->getModule('socialwall'))) {
            return \app\modules\backendobjects\frontend\blockgroups\DisabledGroup::class;
        }
        return SocialGroup::class;
    }

    public function disable() {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_block_socialwall');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'share';
    }

    public function config() {
        return [
            'vars' => [
//                [
//                    'var' => 'posts',
//                    'label' => Yii::t('backendobjects', 'Post trovati'),
//                    'type' => 'zaa-social-search',
//                    'options' => $this->socialContent(),
//                    'description'=> 'Post social trovati per il termine di ricerca inserito.',
//
//                ],
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
    }

    /**
     * @inheritdoc
     */
    public function extraVars() {
        $template = $this->getVarValue('template');
        $array = [];

        switch ($template) {
            case 0:
                $array = [
                    ['class' => 'col-xs-12 col-md-12', 'social' => 'instagram'],
                    ['class' => 'col-xs-12 col-md-12', 'social' => 'twitter'],
                ];
                break;
            case 1:
                $array = [
                    ['class' => 'col-xs-12 col-md-6', 'social' => 'instagram'],
                    ['class' => 'col-xs-12 col-md-6', 'social' => 'twitter'],
                ];
                break;

            case 2:
                $array = [
                    ['class' => 'col-xs-12 col-md-8', 'social' => 'instagram'],
                    ['class' => 'col-xs-12 col-md-4', 'social' => 'twitter'],
                ];
                break;

            case 3:
                $array = [
                    ['class' => 'col-xs-12 col-md-4', 'social' => 'instagram'],
                    ['class' => 'col-xs-12 col-md-8', 'social' => 'twitter'],
                ];
                break;
        }

        return [
            'template' => $array,
        ];
    }

    public function socialContent() {

        $posts = $this->getVarValue('posts');

        $data = [
            'items' => [],
        ];

        foreach ($posts as $post) {

            $exist = array_search($post['id'], array_column($data['items'], 'id'));
            if ($exist === false)
                $data['items'][] = [
                    'id' => $post['id'],
                    'value' => $post['value'],
                    'label' => $post['value'],
                    'social' => $post['social'],
                    'image' => $post['image'],
                    'user' => $post['user'],
                    'user_icon' => $post['user_icon'],
                    'date' => $post['date'],
                    'link' => $post['link'],
                    'user_link' => $post['user_link'],
                    'tot_share' => $post['tot_share'],
                    'tot_comments' => $post['tot_comments'],
                    'tot_like' => $post['tot_like'],
                ];
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function frontend(array $params = array()) {

        return parent::frontend($params);
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        return "<img src=\"/img/preview_cms/socialcards.png\" />";
    }

}
