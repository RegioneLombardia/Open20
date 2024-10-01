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

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\DocBlock\DocBlock;
use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverRootless;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 */
final class GeneralPhpdocAnnotationRemoveFixer extends AbstractFixer implements ConfigurationDefinitionFixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'Configured annotations should be omitted from PHPDoc.',
            [
                new CodeSample(
                    '<?php
/**
 * @internal
 */
function foo() {}
',
                    ['annotations' => ['author']]
                ),
                new CodeSample(
                    '<?php
/**
 * @package ACME API
 * @subpackage Authorization
 * @version 1.0
 */
function foo() {}
',
                    ['annotations' => ['package', 'subpackage']]
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * Must run before NoEmptyPhpdocFixer, PhpdocAlignFixer, PhpdocLineSpanFixer, PhpdocSeparationFixer, PhpdocTrimFixer.
     * Must run after AlignMultilineCommentFixer, CommentToPhpdocFixer, PhpdocIndentFixer, PhpdocScalarFixer, PhpdocToCommentFixer, PhpdocTypesFixer.
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_DOC_COMMENT);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        if (!\count($this->configuration['annotations'])) {
            return;
        }

        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(T_DOC_COMMENT)) {
                continue;
            }

            $doc = new DocBlock($token->getContent());
            $annotations = $doc->getAnnotationsOfType($this->configuration['annotations']);

            // nothing to do if there are no annotations
            if (empty($annotations)) {
                continue;
            }

            foreach ($annotations as $annotation) {
                $annotation->remove();
            }

            if ('' === $doc->getContent()) {
                $tokens->clearTokenAndMergeSurroundingWhitespace($index);
            } else {
                $tokens[$index] = new Token([T_DOC_COMMENT, $doc->getContent()]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition()
    {
        return new FixerConfigurationResolverRootless('annotations', [
            (new FixerOptionBuilder('annotations', 'List of annotations to remove, e.g. `["author"]`.'))
                ->setAllowedTypes(['array'])
                ->setDefault([])
                ->getOption(),
        ], $this->getName());
    }
}
