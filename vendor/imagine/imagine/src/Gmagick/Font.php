<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Gmagick;

use Imagine\Driver\InfoProvider;
use Imagine\Image\AbstractFont;
use Imagine\Image\Palette\Color\ColorInterface;

/**
 * Font implementation using the Gmagick PHP extension.
 */
final class Font extends AbstractFont implements InfoProvider
{
    /**
     * @var \Gmagick
     */
    private $gmagick;

    /**
     * @param \Gmagick $gmagick
     * @param string $file
     * @param int $size
     * @param \Imagine\Image\Palette\Color\ColorInterface $color
     */
    public function __construct(\Gmagick $gmagick, $file, $size, ColorInterface $color)
    {
        $this->gmagick = $gmagick;

        parent::__construct($file, $size, $color);
    }

    /**
     * {@inheritdoc}
     *
     * @since 1.3.0
     */
    public static function getDriverInfo($required = true)
    {
        return DriverInfo::get($required);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function box($string, $angle = 0)
    {
        $text = new \GmagickDraw();

        $text->setfont($this->file);
        /*
         *
         * ensure font resolution is the same as GD's hard-coded 96
         */
        $text->setfontsize((int) ($this->size * (96 / 72)));
        $text->setfontstyle(\Gmagick::STYLE_OBLIQUE);

        $info = $this->gmagick->queryfontmetrics($text, $string);

        $box = $this->getClassFactory()->createBox($info['textWidth'], $info['textHeight']);

        return $box;
    }
}
