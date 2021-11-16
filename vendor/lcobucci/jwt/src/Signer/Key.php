<?php
/**
 *
 */

namespace Lcobucci\JWT\Signer;

use Exception;
use InvalidArgumentException;
use SplFileObject;

/**
 * @since 3.0.4
 */
final class Key
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $passphrase;

    /**
     * @param string $content
     * @param string $passphrase
     */
    public function __construct($content, $passphrase = null)
    {
        $this->setContent($content);
        $this->passphrase = $passphrase;
    }

    /**
     * @param string $content
     *
     * @throws InvalidArgumentException
     */
    private function setContent($content)
    {
        if (strpos($content, 'file://') === 0) {
            $content = $this->readFile($content);
        }

        $this->content = $content;
    }

    /**
     * @param string $content
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    private function readFile($content)
    {
        try {
            $file    = new SplFileObject(substr($content, 7));
            $content = '';

            while (! $file->eof()) {
                $content .= $file->fgets();
            }

            return $content;
        } catch (Exception $exception) {
            throw new InvalidArgumentException('You must inform a valid key file', 0, $exception);
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPassphrase()
    {
        return $this->passphrase;
    }
}
