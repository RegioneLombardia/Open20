<?php

namespace luya\tag\tags;

use yii\helpers\Html;
use luya\tag\BaseTag;

/**
 * TagParser MailTag.
 *
 * The mail Tag allows you to create E-Mail links to an address. Example use `mail[info@luya.io]` or with an additional value info use `mail[info@luya.io](send mail)`
 *
 * @since 1.0.0
 */
class MailTag extends BaseTag
{
    /**
     * @var boolean Whether email obfuscation is enabled or not.
     * @since 1.0.15
     */
    public $obfuscate = true;

    /**
     * An example of how to use the MailTag.
     *
     * @return string The example string
     */
    public function example()
    {
        return 'mail[info@luya.io](Mail us!)';
    }
    
    /**
     * The readme instructions string for the MailTag.
     *
     * @return string The readme text.
     */
    public function readme()
    {
        return <<<EOT
The mail Tag allows you to create E-Mail links to an address. Example use `mail[info@luya.io]` or with an additional value info use `mail[info@luya.io](send mail)`.      
EOT;
    }
    
    /**
     * Generate the Mail Tag.
     *
     * @param string $value The Brackets value `[]`.
     * @param string $sub The optional Parentheses value `()`
     * @return string The parser tag.
     */
    public function parse($value, $sub)
    {
        $label = $sub ?: $value;

        // if obfuscation is enabled generate tag string due to yii tag method will encode attributes.
        if ($this->obfuscate) {
            if (!$sub) {
                $label = $this->obfuscate($label);
            }
            return '<a href="'.$this->obfuscate("mailto:{$value}").'" rel="nofollow">'.$label.'</a>';
        }

        return Html::mailto($label, $value, [
            'rel' => 'nofollow',
        ]);
    }

    /**
     * Obfucscate email adresse
     *
     * @param string $email
     * @return string
     */
    public function obfuscate($email)
    {
        $output = null;
        for ($i = 0; $i < strlen($email); $i++) {
            $output .= '&#'.ord($email[$i]).';';
        }
        
        return $output;
    }
}
