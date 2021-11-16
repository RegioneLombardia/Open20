<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Console\Report\ListSetsReport;

use PhpCsFixer\RuleSet\RuleSetDescriptionInterface;

/**
 *
 * @internal
 */
final class TextReporter implements ReporterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return 'txt';
    }

    /**
     * {@inheritdoc}
     */
    public function generate(ReportSummary $reportSummary)
    {
        $output = '';
        $i = 0;

        $sets = $reportSummary->getSets();
        usort($sets, function (RuleSetDescriptionInterface $a, RuleSetDescriptionInterface $b) {
            return $a->getName() > $b->getName() ? 1 : -1;
        });

        foreach ($sets as $set) {
            ++$i;
            $output .= sprintf('%2d) %s', $i, $set->getName()).PHP_EOL.'      '.$set->getDescription().PHP_EOL;

            if ($set->isRisky()) {
                $output .= '      Set contains risky rules.'.PHP_EOL;
            }
        }

        return $output;
    }
}
