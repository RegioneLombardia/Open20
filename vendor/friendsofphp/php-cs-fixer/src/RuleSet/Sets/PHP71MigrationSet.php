<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\RuleSet\Sets;

use PhpCsFixer\RuleSet\AbstractRuleSetDescription;

/**
 * @internal
 */
final class PHP71MigrationSet extends AbstractRuleSetDescription
{
    public function getRules()
    {
        return [
            '@PHP70Migration' => true,
            'list_syntax' => ['syntax' => 'short'],
            'visibility_required' => [
                'elements' => [
                    'const',
                    'method',
                    'property',
                ],
            ],
        ];
    }

    public function getDescription()
    {
        return 'Rules to improve code for PHP 7.1 compatibility.';
    }
}
