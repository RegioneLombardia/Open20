<?php

namespace app\modules\uikit\menu;

use luya\cms\models\Nav;

class MenuItem {

    private $nav = [];
    private $hasSons = false;
    private $sons = [];

    public function __construct(Nav $nav) {
        $this->nav = $nav->toArray();
        $this->nav['title'] = $nav->activeLanguageItem->title;
    }

    public function loadSons() {
        if (count($this->nav)) {
            $navs = $this->getSons();
            if (count($navs)) {
                $this->hasSons = true;
                foreach ($navs as $nav) {
                    $item = new MenuItem($nav);
                    $item->loadSons();
                    $this->sons[] = $item;
                }
            }
        }
    }

    /**
     * 
     */
    private function getSons() {
        Nav::find()->andWhere(['parent_nav_id' => $this->nav['id']])->all();
    }

    /**
     * 
     * @return string
     */
    public function renderOpen() {
        $html = "";
        if ($this->hasSons) {
            $html = '<li class="nav-item dropdown megamenu">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" id="megamenu' . $this->nav['id'] . '">
            <span>' . $this->nav['title'] . '</span>
            <svg class="icon icon-xs">
              <use href="/bootstrap-italia/1.x/dist/svg/sprite.svg#it-expand"></use>
            </svg>
          </a>
          <div class="dropdown-menu" role="region" aria-labelledby="' . $this->nav['id'] . '">
            <div class="row">
              <div class="col-12 col-lg-4">
                <div class="link-list-wrapper">
                    <ul class="link-list">';
            foreach ($this->sons as $son) {
                $html .= $son->renderOpen();
                $html .= $son->renderClose();
            }
        } else {
            $html = '<li><a class="list-item" href="#"><span>' . $this->nav['title'] . '</span>';
        }
        return $html;
    }

    /**
     * 
     * @return string
     */
    public function renderClose() {
        $html = "";
        if ($this->hasSons) {
            $html .= '</ul>
                    </div>
                </div>
          </div>
        </li>';
        } else {
            $html = '</a>
                    </li>';
        }
        return $html;
    }

}
