<?php
/**
 */

namespace yii\authclient\widgets;

use yii\base\Widget;

/**
 * AuthChoiceItem is a base class for creating widgets, which can be used to render link
 * for auth client at [[AuthChoice]].
 *
 *
 * @since 2.0
 */
class AuthChoiceItem extends Widget
{
    /**
     * @var \yii\authclient\ClientInterface auth client instance.
     */
    public $client;
    /**
     * @var AuthChoice parent AuthChoice widget
     */
    public $authChoice;
}