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
final class JsonReporter implements ReporterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function generate(ReportSummary $reportSummary)
    {
        $json = ['sets' => []];

        $sets = $reportSummary->getSets();
        usort($sets, function (RuleSetDescriptionInterface $a, RuleSetDescriptionInterface $b) {
            return $a->getName() > $b->getName() ? 1 : -1;
        });

        foreach ($sets as $set) {
            $json['sets'][$set->getName()] = [
                'description' => $set->getDescription(),
                'isRisky' => $set->isRisky(),
                'name' => $set->getName(),
            ];
        }

        return json_encode($json, JSON_PRETTY_PRINT);
    }
}
