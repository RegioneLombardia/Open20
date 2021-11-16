<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image;

interface ProfileInterface
{
    /**
     * Returns the name of the profile.
     *
     * @return string
     */
    public function name();

    /**
     * Returns the profile data.
     *
     * @return string
     */
    public function data();
}
