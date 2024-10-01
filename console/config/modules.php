<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
use kartik\datecontrol\Module;

return [
    'news' => [
        'class' => 'open20\amos\news\AmosNews',
        'hidePubblicationDate' => false,
        'newsRequiredFields' => [
            // 'news_categorie_id',
            'titolo',
            'status',
            'descrizione',
        ],
        'params' => [
            'site_featured_enabled' => true,
            'site_publish_enabled' => true,
        ],
        'defaultEnableComments' => 0,
        'useFrontendView' => false,
        'usePrettyUrl' => true,
        'enableOnlyRedactional' => false,
        'showCategory' => true,
        'rteToolbar' => "styleselect | bold italic strikethrough | link unlink image | alignleft aligncenter | numlist bullist outdent indent | removeformat",
        'dateFormatNews' => Module::FORMAT_DATETIME,
        'enableLikeWidget' => false
    ],
    'FLcommands' => [
        'class' => 'frontend\modules\FLcommands\Module',
    ],
];
