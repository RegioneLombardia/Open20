<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

namespace app\modules\cms\base;


use luya\web\Composition;

class UrlManager extends \luya\web\UrlManager
{

    public function routeHasLanguageCompositionPrefix($route, $language)
    {
        $route = ltrim($route, "/");
        $parts = explode("/", $route);
        if (isset($parts[0]) && $parts[0] == $language) {
            return true;
        }

        return false;
    }

    public function removeLanguageCompositionPrefix($route, $language)
    {
        $parts = explode("/", $route);
        if (isset($parts[0]) && $parts[0] == $language) {
            array_shift($parts);
        }

        return implode("/", $parts);
    }

    public function createAbsoluteUrl($params, $scheme = null)
    {

        return $this->internalCreateAbsoluteUrl($params, $scheme);
    }

    /**
     * Yii2 createUrl base implementation extends the prepand of the comosition
     *
     * @param string|array $params An array with params or not (e.g. `['module/controller/action', 'param1' => 'value1']`)
     * @param null|Composition $composition Composition instance to change the route behavior
     * @return string
     */
    public function internalCreateUrl($params, $composition = null)
    {

        $params = (array) $params;

        $composition = empty($composition) ? $this->getComposition() : $composition;

        $originalParams = $params;

        // prepand the original route, whether is hidden or not!
        // https://github.com/luyadev/luya/issues/1146
        if ($this->routeHasLanguageCompositionPrefix($params[0],
                $this->composition->langShortCode) === false) {
            $params[0] = $composition->prependTo($params[0],
                $composition->createRoute());
        }

        $response = \yii\web\UrlManager::createUrl($params);

        // Check if the parsed route with the prepand composition has been found or not.
        if (strpos($response, rtrim($params[0], '/')) !== false) {
            // we got back the same url from the createUrl, no match against composition route.
            $response = \yii\web\UrlManager::createUrl($originalParams);
        }

        $response = $this->removeBaseUrl($response);
        if ($this->routeHasLanguageCompositionPrefix($params[0],
                $this->composition->langShortCode) === false) {
            $response = $composition->prependTo($response);
        }
        return $this->prependBaseUrl($response);
    }
}