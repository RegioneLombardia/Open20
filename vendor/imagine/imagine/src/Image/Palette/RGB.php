<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Palette;

use Imagine\Exception\RuntimeException;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Palette\Color\RGB as RGBColor;
use Imagine\Image\Profile;
use Imagine\Image\ProfileInterface;

/**
 * The RGB palette.
 */
class RGB implements PaletteInterface
{
    /**
     * @var \Imagine\Image\Palette\ColorParser
     */
    private $parser;

    /**
     * @var \Imagine\Image\ProfileInterface|null
     */
    private $profile;

    /**
     * @var \Imagine\Image\Palette\Color\RGB[]
     */
    protected static $colors = array();

    public function __construct()
    {
        $this->parser = new ColorParser();
    }

    /**
     * {@inheritdoc}
     *
     */
    public function name()
    {
        return PaletteInterface::PALETTE_RGB;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function pixelDefinition()
    {
        return array(
            ColorInterface::COLOR_RED,
            ColorInterface::COLOR_GREEN,
            ColorInterface::COLOR_BLUE,
        );
    }

    /**
     * {@inheritdoc}
     *
     */
    public function supportsAlpha()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getChannelsMaxValue()
    {
        return 255;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function useProfile(ProfileInterface $profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function profile()
    {
        if (!$this->profile) {
            $this->profile = Profile::fromPath(__DIR__ . '/../../resources/color.org/sRGB_IEC61966-2-1_black_scaled.icc');
        }

        return $this->profile;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function color($color, $alpha = null)
    {
        if ($alpha === null) {
            $alpha = 100;
        }

        $color = $this->parser->parseToRGB($color);
        $index = sprintf('#%02x%02x%02x-%d', $color[0], $color[1], $color[2], $alpha);

        if (array_key_exists($index, static::$colors) === false) {
            static::$colors[$index] = new RGBColor($this, $color, $alpha);
        }

        return static::$colors[$index];
    }

    /**
     * {@inheritdoc}
     *
     */
    public function blend(ColorInterface $color1, ColorInterface $color2, $amount)
    {
        if (!$color1 instanceof RGBColor || !$color2 instanceof RGBColor) {
            throw new RuntimeException('RGB palette can only blend RGB colors');
        }
        $amount = (float) $amount;
        $amountComplement = 1 - $amount;
        $max = $this->getChannelsMaxValue();

        return $this->color(
            array(
                min(max((int) round($color2->getRed() * $amount + $color1->getRed() * $amountComplement), 0), $max),
                min(max((int) round($color2->getGreen() * $amount + $color1->getGreen() * $amountComplement), 0), $max),
                min(max((int) round($color2->getBlue() * $amount + $color1->getBlue() * $amountComplement), 0), $max),
            ),
            min(max((int) round($color2->getAlpha() * $amount + $color1->getAlpha() * $amountComplement), 0), 100)
        );
    }
}
