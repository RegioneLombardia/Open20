<?php

namespace open20\design\utility;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */
use Exception;
use open20\design\Module;
use Yii;
use yii\helpers\Html;

class CmsLanguageUtility extends LanguageUtility
{
    /**
     * @var null|array Singleton container when used for mobile and desktop in order to reduce db requests.
     */
    private static $_dataArray;

    public static function getAppLanguage()
    {
        $labelLang = strtoupper(Yii::$app->composition['langShortCode']);
        return $labelLang;
    }

    /**
     *
     * @return array
     */
    public static function getTranslationMenu()
    {
        try {
            $voceMenu    = [];
            $link        = '';
            $currentLang = Yii::$app->composition['langShortCode'];
            $languages   = self::getDataArray();

            if (!empty($languages)) {
                foreach ($languages as $langData) {
                    $item     = $langData['item'];
                    $lang     = $langData['lang'];
                    $isActive = $currentLang == $lang['short_code'];

                    if (get_class(\Yii::$app->controller->module) != 'luya\cms\frontend\Module') {
                        $baseUrl = str_replace('/'.$currentLang, '', \Yii::$app->request->url);

                        $link = '/'.$lang['short_code'].$baseUrl;
                    } else if ($item) {
                        $link = $item->itemArray['link'];
                    } else {
                        $link = Yii::$app->urlManager->prependBaseUrl($lang['short_code']);
                    }
                    $voceMenu[] = Html::a(
                            Html::tag('span', strtoupper($lang['short_code'])), null,
                            [
                            'href' => $link,
                            'data' => [
                                'params' => ['language' => $lang['short_code']],
                                'method' => 'post'
                            ],
                            'class' => 'list-item',
                            'title' => Module::t('amosdesign', 'Cambia lingua').' '. strtoupper($lang['short_code'])
                            ]
                    );
                }
            }

            return $voceMenu;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     *
     * @return type
     */
    private static function getDataArray()
    {
        if (self::$_dataArray === null) {
            $currentMenuItem = Yii::$app->menu->current;
            $array           = [];
            foreach (Yii::$app->adminLanguage->getLanguages() as $lang) {
                $array[] = [
                    'lang' => $lang,
                    'item' => Yii::$app->menu->find()->where(['nav_id' => $currentMenuItem->navId])->lang($lang['short_code'])->with('hidden')->one(),
                ];
            }

            self::$_dataArray = $array;
        }

        return self::$_dataArray;
    }
}