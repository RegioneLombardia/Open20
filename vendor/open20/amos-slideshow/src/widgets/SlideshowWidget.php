<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

namespace open20\amos\slideshow\widgets;

use open20\amos\slideshow\models\Slideshow;
use yii\base\Widget;
use yii\helpers\VarDumper;
use yii\web\View;
use open20\amos\slideshow\models\SlideshowRoute;
use open20\amos\slideshow\models\SlideshowUserflag;

/**
 * Class SlideshowWidget
 * @package open20\amos\slideshow\forms
 */
class SlideshowWidget extends Widget {

    /**
     * @var string $header Widget view
     */
    public $header;

    /**
     * @var string $footer Widget view
     */
    public $footer;

    /**
     * If set it displays the slideshow of this route
     * @var type
     */
    public $route;

    /**
     * @var Slideshow $slideshow Slideshow object
     */
    private $slideshow = null;

    /*
     * @var array $roleUser
     */
    private $roleUser = [];

    /**
     * @inheritdoc
     */
    public function run() {
        $this->setRoleUser();
        $route = (!empty($this->route) ? $this->route : "/" . \Yii::$app->request->getPathInfo());
        $idSlideshow = $this->hasSlideshow($route);

        if ($idSlideshow) {
            $SlideshowRoute = SlideshowRoute::findOne(['slideshow_id' => $idSlideshow]);
            $userId = (\Yii::$app->user->isGuest ? null : \Yii::$app->getUser()->getId());
            $userFlag = ($SlideshowRoute && !empty($userId)) ? SlideshowUserflag::findOne(['slideshow_route_id' => $SlideshowRoute->id, 'user_id' => $userId]) : NULL;
            $flag = (empty($userFlag)) ? FALSE : TRUE;

            $default_not_show_again = $this->slideshow->default_not_show_again;
            return $this->render('slideshow_widget', [
                        'slideshow' => $this->slideshow,
                        'header' => (isset($this->header)) ? $this->header : NULL,
                        'footer' => (isset($this->footer)) ? $this->footer : NULL,
                        'route' => $route,
                        'flag' => $flag,
                        'default_not_show_again' => $default_not_show_again,
            ]);
        }
    }

    private function hasSlideshow($route) {
        $slideshowRoutes = SlideshowRoute::find()->andWhere(['route' => $route])->andWhere(['IN', 'role', $this->roleUser])->all();
        foreach ($slideshowRoutes as $slideshowRoute) {
            if ($slideshowRoute) {
                $this->slideshow = $slideshowRoute->slideshow;
                $evaluated_method = true;
                if (!is_null($this->slideshow)) {
                    if (!empty($this->slideshow->eval_contoller_method)) {
                        $method = $this->slideshow->eval_contoller_method;
                        if (method_exists(\Yii::$app->controller, $method)) {
                            $evaluated_method = \Yii::$app->controller->$method();
                        }
                    }
                    if ($evaluated_method) {
                        return $this->slideshow->id;
                    }
                }
            }
        }
        return 0;
    }

    public function setRoleUser() {
        if (\Yii::$app->user->isGuest) {
            $this->roleUser[] = 'TUTTI';
        } else {
            $roles = \Yii::$app->authManager->getRolesByUser(\Yii::$app->getUser()->getId());
            $this->roleUser[] = 'TUTTI';
            foreach ((array) $roles as $key => $value) {
                $this->roleUser[] = $key;
            }
        }
    }

}
