<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

return [
        'view' => [
            'class' => 'open20\amos\core\components\AmosView',
			'theme' => [
            'pathMap' => [
                '@vendor/open20/amos-layout/src/views/layouts/fullsize' => '@backend/views/layouts/fullsize',
                '@backend/views/layouts/fullsize' => '@vendor/open20/amos-layout/src/views/layouts/fullsize',
				],
				],
        ],
];
