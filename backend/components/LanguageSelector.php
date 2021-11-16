<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace backend\components;

use yii\base\BootstrapInterface;

/**
 * Multi-language support
 * @package backend\components
 */
class LanguageSelector implements BootstrapInterface
{
    /**
     * Supported-languages array
     * @var array
     */
    public $supportedLanguages = [];

    /**
     * Bootstrap for multi-language support
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $preferredLanguage = isset($app->request->cookies['language']) ? (string)$app->request->cookies['language'] : null;
        // or in case of database:
        // $preferredLanguage = $app->user->language;

        if (empty($preferredLanguage)) {
            $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);
        }

        $app->language = $preferredLanguage;
    }
}