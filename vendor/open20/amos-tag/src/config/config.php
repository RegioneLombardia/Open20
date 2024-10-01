<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

$config = [
    'modules' => [
        'treemanager' => [
            'class' => '\kartik\tree\Module',
            // enter other module properties if needed
            // for advanced/personalized configuration
            // (refer module properties available below)
            'dataStructure' => ['nameAttribute' => 'nome']
        ],
    ]
];

return $config;
