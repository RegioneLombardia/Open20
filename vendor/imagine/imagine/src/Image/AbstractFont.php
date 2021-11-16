<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image;

use Imagine\Exception\InvalidArgumentException;
use Imagine\Factory\ClassFactory;
use Imagine\Factory\ClassFactoryAwareInterface;
use Imagine\Factory\ClassFactoryInterface;
use Imagine\Image\Palette\Color\ColorInterface;

/**
 * Abstract font base class.
 */
abstract class AbstractFont implements FontInterface, ClassFactoryAwareInterface
{
    /**
     * @var \Imagine\Factory\ClassFactoryInterface|null
     */
    private $classFactory;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var \Imagine\Image\Palette\Color\ColorInterface
     */
    protected $color;

    /**
     * Constructs a font with specified $file, $size and $color.
     *
     * The font size is to be specified in points (e.g. 10pt means 10)
     *
     * @param string $file
     * @param int $size
     * @param \Imagine\Image\Palette\Color\ColorInterface $color
     */
    public function __construct($file, $size, ColorInterface $color)
    {
        $this->file = $file;
        $this->size = $size;
        $this->color = $color;
    }

    /**
     * {@inheritdoc}
     *
     */
    final public function getFile()
    {
        return $this->file;
    }

    /**
     * {@inheritdoc}
     *
     */
    final public function getSize()
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     *
     */
    final public function getColor()
    {
        return $this->color;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function wrapText($string, $maxWidth, $angle = 0)
    {
        $string = (string) $string;
        if ($string === '') {
            return $string;
        }
        $maxWidth = (int) round($maxWidth);
        if ($maxWidth < 1) {
            throw new InvalidArgumentException(sprintf('The $maxWidth parameter of wrapText must be greater than 0.'));
        }
        $words = explode(' ', $string);
        $lines = array();
        $currentLine = null;
        foreach ($words as $word) {
            if ($currentLine === null) {
                $currentLine = $word;
            } else {
                $testLine = $currentLine . ' ' . $word;
                $testbox = $this->box($testLine, $angle);
                if ($testbox->getWidth() <= $maxWidth) {
                    $currentLine = $testLine;
                } else {
                    $lines[] = $currentLine;
                    $currentLine = $word;
                }
            }
        }
        if ($currentLine !== null) {
            $lines[] = $currentLine;
        }

        return implode("\n", $lines);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getClassFactory()
    {
        if ($this->classFactory === null) {
            $this->classFactory = new ClassFactory();
        }

        return $this->classFactory;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function setClassFactory(ClassFactoryInterface $classFactory)
    {
        $this->classFactory = $classFactory;

        return $this;
    }
}
