<?php

/**
 * fast-image-size image type interface
 * @package fast-image-size
 *
 */

namespace FastImageSize\Type;

interface TypeInterface
{
	/** @var int 4-byte long size */
	const LONG_SIZE = 4;

	/** @var int 2-byte short size */
	const SHORT_SIZE = 2;

	/**
	 * Get size of supplied image
	 *
	 * @param string $filename File name of image
	 *
	 * @return null
	 */
	public function getSize($filename);
}
