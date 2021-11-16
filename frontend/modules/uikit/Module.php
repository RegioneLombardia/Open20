<?php

namespace app\modules\uikit;

use Yii;

class Module extends \luya\base\Module
{

    /**
     * @var array configs for store general field configs.
     */
    public static $configs = [];

    /**
     * @inheritdoc
     */
    public static function onLoad()
    {
        Yii::setAlias('@uikit', static::staticBasePath());

        self::registerTranslation('uikit*', static::staticBasePath() . '/messages', [
            'uikit' => 'uikit.php'
        ]);
    }

    /**
     * Translations
     *
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, array $params = [])
    {
        return parent::baseT('uikit', $message, $params);
    }
}