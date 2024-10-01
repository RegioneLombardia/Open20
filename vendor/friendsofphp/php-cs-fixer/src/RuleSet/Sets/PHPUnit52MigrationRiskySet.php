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

use PhpCsFixer\Fixer\PhpUnit\PhpUnitTargetVersion;
use PhpCsFixer\RuleSet\AbstractRuleSetDescription;

/**
 * @internal
 */
final class PHPUnit52MigrationRiskySet extends AbstractRuleSetDescription
{
    public function getRules()
    {
        return [
            '@PHPUnit50Migration:risky' => true,
            'php_unit_expectation' => [
                'target' => PhpUnitTargetVersion::VERSION_5_2,
            ],
        ];
    }

    public function getDescription()
    {
        return 'Rules to improve tests code for PHPUnit 5.2 compatibility.';
    }
}
