<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Linter;

/**
 *
 * @internal
 */
final class TokenizerLintingResult implements LintingResultInterface
{
    /**
     * @var null|\Error
     */
    private $error;

    public function __construct(\Error $error = null)
    {
        $this->error = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function check()
    {
        if (null !== $this->error) {
            throw new LintingException(
                sprintf('%s: %s on line %d.', $this->getMessagePrefix(), $this->error->getMessage(), $this->error->getLine()),
                $this->error->getCode(),
                $this->error
            );
        }
    }

    private function getMessagePrefix()
    {
        return $this->error instanceof \ParseError ? 'Parse error' : 'Fatal error';
    }
}
