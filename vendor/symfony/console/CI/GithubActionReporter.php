<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\CI;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Utility class for Github actions.
 *
 */
class GithubActionReporter
{
    private $output;

    /**
     */
    private const ESCAPED_DATA = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
    ];

    /**
     */
    private const ESCAPED_PROPERTIES = [
        '%' => '%25',
        "\r" => '%0D',
        "\n" => '%0A',
        ':' => '%3A',
        ',' => '%2C',
    ];

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public static function isGithubActionEnvironment(): bool
    {
        return false !== getenv('GITHUB_ACTIONS');
    }

    /**
     * Output an error using the Github annotations format.
     *
     */
    public function error(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
    {
        $this->log('error', $message, $file, $line, $col);
    }

    /**
     * Output a warning using the Github annotations format.
     *
     */
    public function warning(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
    {
        $this->log('warning', $message, $file, $line, $col);
    }

    /**
     * Output a debug log using the Github annotations format.
     *
     */
    public function debug(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
    {
        $this->log('debug', $message, $file, $line, $col);
    }

    private function log(string $type, string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
    {
        // Some values must be encoded.
        $message = strtr($message, self::ESCAPED_DATA);

        if (!$file) {
            // No file provided, output the message solely:
            $this->output->writeln(sprintf('::%s::%s', $type, $message));

            return;
        }

        $this->output->writeln(sprintf('::%s file=%s,line=%s,col=%s::%s', $type, strtr($file, self::ESCAPED_PROPERTIES), strtr($line ?? 1, self::ESCAPED_PROPERTIES), strtr($col ?? 0, self::ESCAPED_PROPERTIES), $message));
    }
}
