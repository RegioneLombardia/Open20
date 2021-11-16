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

/**
 * CSS selector whitespace handler.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class WhitespaceHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(Reader $reader, TokenStream $stream)
    {
        $match = $reader->findPattern('~^[ \t\r\n\f]+~');

        if (false === $match) {
            return false;
        }

        $stream->push(new Token(Token::TYPE_WHITESPACE, $match[0], $reader->getPosition()));
        $reader->moveForward(strlen($match[0]));

        return true;
    }
}
