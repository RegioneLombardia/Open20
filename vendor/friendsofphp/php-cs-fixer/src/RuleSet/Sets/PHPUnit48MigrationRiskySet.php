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
final class PHPUnit48MigrationRiskySet extends AbstractRuleSetDescription
{
    public function getRules()
    {
        return [
            '@PHPUnit43Migration:risky' => true,
            'php_unit_namespaced' => [
                'target' => PhpUnitTargetVersion::VERSION_4_8,
            ],
        ];
    }

    public function getDescription()
    {
        return 'Rules to improve tests code for PHPUnit 4.8 compatibility.';
    }
}
