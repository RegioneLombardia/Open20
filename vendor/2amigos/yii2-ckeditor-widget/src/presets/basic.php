<?php

/**
 *
 * basic preset returns the basic toolbar configuration set for CKEditor.
 *
 */
return [
    'height' => 200,
    'toolbarGroups' => [
        ['name' => 'undo'],
        ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
        ['name' => 'colors'],
        ['name' => 'links', 'groups' => ['links', 'insert']],
        ['name' => 'others', 'groups' => ['others', 'about']],
    ],
    'removeButtons' => 'Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe',
    'removePlugins' => 'elementspath',
    'resize_enabled' => false
];
