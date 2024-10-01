<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

namespace open20\amos\slideshow\models;

/**
 * Class Slideshow
 * @package open20\amos\slideshow\models
 */
class Slideshow extends \open20\amos\slideshow\models\base\Slideshow
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name',
        ];
    }

    public function hasSlideshow($route)
    {
        $roleUser = $this->getRoleUser();
        $slideshowRoute = SlideshowRoute::find()->andWhere(['route' => $route])->andWhere(['IN', 'role', $roleUser])->one();
        if ($slideshowRoute && $slideshowRoute->slideshow) {
            $idSlideshow = $slideshowRoute->slideshow->id;
            $slideshow = Slideshow::findOne($idSlideshow);
            if ($idSlideshow) {
                return $idSlideshow;
            }
        }
        return 0;
    }


    public function getRoleUser()
    {
        $roles = \Yii::$app->authManager->getRolesByUser(\Yii::$app->getUser()->getId());
        $roleUser = [];
        $roleUser[] = 'TUTTI';
        foreach ((array)$roles as $key => $value) {
            $roleUser[] = $key;
        }
        return $roleUser;
    }
}
