<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace amos\sitemanagement\widgets\graphics;

use amos\sitemanagement\Module;
use open20\amos\core\widget\WidgetGraphic;


class WidgetCommunityImagesSlider extends WidgetGraphic
{
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('COMMUNITY_IMAGES_SLIDER');
        $this->setLabel(Module::t('amossitemanagement', 'Slider immagini'));
        $this->setDescription(Module::t('amossitemanagement', 'Consente di visualizzare lo slider nella sezione widget grafici'));
    }

    /**
     * 
     * @return type
     */
    public function getHtml()
    {
        $viewToRender = 'community_images_slider';

        $moduleCwh = \Yii::$app->getModule('cwh');
        $communityId = null;
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
            }
        }


        return $this->render($viewToRender, [
            'widget' => $this,
            'communityId' => $communityId,
        ]);
    }
}

