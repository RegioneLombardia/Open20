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
final class PHP74MigrationRiskySet extends AbstractRuleSetDescription
{
    public function getRules()
    {
        return [
            '@PHP71Migration:risky' => true,
            'implode_call' => true,
            'no_alias_functions' => true,
            'use_arrow_functions' => true,
        ];
    }

    public function getDescription()
    {
        return 'Rules to improve code for PHP 7.4 compatibility.';
    }
}
