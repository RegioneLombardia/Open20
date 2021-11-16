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
final class PSR12RiskySet extends AbstractRuleSetDescription
{
    public function getRules()
    {
        return [
            'no_trailing_whitespace_in_string' => true,
            'no_unreachable_default_argument_value' => true,
        ];
    }

    public function getDescription()
    {
        return 'Rules that follow `PSR-12 <https://www.php-fig.org/psr/psr-12/>`_ standard.';
    }
}
