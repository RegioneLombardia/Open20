<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Fixer\Phpdoc;

use PhpCsFixer\AbstractProxyFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;

/**
 */
final class PhpdocNoAccessFixer extends AbstractProxyFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            '`@access` annotations should be omitted from PHPDoc.',
            [
                new CodeSample(
                    '<?php
class Foo
{
    /**
     * @internal
     * @access private
     */
    private $bar;
}
'
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * Must run before NoEmptyPhpdocFixer, PhpdocAlignFixer, PhpdocSeparationFixer, PhpdocTrimFixer.
     * Must run after AlignMultilineCommentFixer, CommentToPhpdocFixer, PhpdocIndentFixer, PhpdocScalarFixer, PhpdocToCommentFixer, PhpdocTypesFixer.
     */
    public function getPriority()
    {
        return parent::getPriority();
    }

    /**
     * {@inheritdoc}
     */
    protected function createProxyFixers()
    {
        $fixer = new GeneralPhpdocAnnotationRemoveFixer();
        $fixer->configure(['annotations' => ['access']]);

        return [$fixer];
    }
}
