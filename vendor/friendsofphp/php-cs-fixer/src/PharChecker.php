<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer;

/**
 * @internal
 */
final class PharChecker implements PharCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkFileValidity($filename)
    {
        try {
            $phar = new \Phar($filename);
            // free the variable to unlock the file
            unset($phar);
        } catch (\Exception $e) {
            if (!$e instanceof \UnexpectedValueException && !$e instanceof \PharException) {
                throw $e;
            }

            return 'Failed to create Phar instance. '.$e->getMessage();
        }

        return null;
    }
}
