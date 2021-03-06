<?php
/* SVN FILE: $Id$ */
/**
 * SassRenderer class file.
 * @package      PHamlP
 * @subpackage  Sass.renderers
 */

#require_once 'SassCompactRenderer.php';
#require_once 'SassCompressedRenderer.php';
#require_once 'SassExpandedRenderer.php';
#require_once 'SassNestedRenderer.php';

/**
 * SassRenderer class.
 * @package      PHamlP
 * @subpackage  Sass.renderers
 */
class SassRenderer
{
  /**#@+
   * Output Styles
   */
  const STYLE_COMPRESSED = 'compressed';
  const STYLE_COMPACT    = 'compact';
  const STYLE_EXPANDED    = 'expanded';
  const STYLE_NESTED      = 'nested';
  /**#@-*/

  const INDENT = '  ';

  /**
   * Returns the renderer for the required render style.
   * @param string $style render style
   * @return SassRenderer
   */
  public static function getRenderer($style)
  {
    switch ($style) {
      case self::STYLE_COMPACT:
        return new SassCompactRenderer();
      case self::STYLE_COMPRESSED:
        return new SassCompressedRenderer();
      case self::STYLE_EXPANDED:
        return new SassExpandedRenderer();
      case self::STYLE_NESTED:
        return new SassNestedRenderer();
    } // switch
  }
}
