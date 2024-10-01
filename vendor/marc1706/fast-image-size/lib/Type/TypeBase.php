<?php

/**
 * fast-image-size image type base
 * @package fast-image-size
 *
 */

namespace FastImageSize\Type;

use \FastImageSize\FastImageSize;

abstract class TypeBase implements TypeInterface
{
	/** @var FastImageSize */
	protected $fastImageSize;

	/**
	 * Base constructor for image types
	 *
	 * @param FastImageSize $fastImageSize
	 */
	public function __construct(FastImageSize $fastImageSize)
	{
		$this->fastImageSize = $fastImageSize;
	}
}
