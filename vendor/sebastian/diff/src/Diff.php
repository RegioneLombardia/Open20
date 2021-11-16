<?php declare(strict_types=1);
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace SebastianBergmann\Diff;

final class Diff
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var Chunk[]
     */
    private $chunks;

    /**
     * @param string  $from
     * @param string  $to
     * @param Chunk[] $chunks
     */
    public function __construct(string $from, string $to, array $chunks = [])
    {
        $this->from   = $from;
        $this->to     = $to;
        $this->chunks = $chunks;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return Chunk[]
     */
    public function getChunks(): array
    {
        return $this->chunks;
    }

    /**
     * @param Chunk[] $chunks
     */
    public function setChunks(array $chunks)
    {
        $this->chunks = $chunks;
    }
}
