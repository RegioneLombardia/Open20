<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Parser\Handler;

use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\CssSelector\Parser\Token;
use Symfony\Component\CssSelector\Parser\TokenStream;
use Symfony\Component\CssSelector\Parser\Tokenizer\TokenizerPatterns;

/**
 * CSS selector comment handler.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class NumberHandler implements HandlerInterface
{
    /**
     * @var TokenizerPatterns
     */
    private $patterns;

    /**
     * @param TokenizerPatterns $patterns
     */
    public function __construct(TokenizerPatterns $patterns)
    {
        $this->patterns = $patterns;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Reader $reader, TokenStream $stream)
    {
        $match = $reader->findPattern($this->patterns->getNumberPattern());

        if (!$match) {
            return false;
        }

        $stream->push(new Token(Token::TYPE_NUMBER, $match[0], $reader->getPosition()));
        $reader->moveForward(strlen($match[0]));

        return true;
    }
}
