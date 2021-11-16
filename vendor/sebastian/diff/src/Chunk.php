<?php declare(strict_types=1);
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace SebastianBergmann\Diff;

final class Chunk
{
    /**
     * @var int
     */
    private $start;

    /**
     * @var int
     */
    private $startRange;

    /**
     * @var int
     */
    private $end;

    /**
     * @var int
     */
    private $endRange;

    /**
     * @var array
     */
    private $lines;

    public function __construct(int $start = 0, int $startRange = 1, int $end = 0, int $endRange = 1, array $lines = [])
    {
        $this->start      = $start;
        $this->startRange = $startRange;
        $this->end        = $end;
        $this->endRange   = $endRange;
        $this->lines      = $lines;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getStartRange(): int
    {
        return $this->startRange;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

    public function getEndRange(): int
    {
        return $this->endRange;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function setLines(array $lines)
    {
        $this->lines = $lines;
    }
}
