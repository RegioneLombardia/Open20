<?php

namespace backend\modules\rbacviewer\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;

/**
 * Class WidgetIconRbacViewer
 * @package backend\modules\rbacviewer\widgets\icons
 */
class WidgetIconRbacViewer extends WidgetIcon
{

    public function init()
    {
        parent::init();
        $this->setLabel(Yii::t('rbacviewer', 'Visualizzazione dei ruoli e permessi'));
        $this->setDescription(Yii::t('rbacviewer', 'Visualizzazione dei ruoli, permessi e regole'));
        $this->setIcon('compass');
        $this->setIconFramework(AmosIcons::DASH);
        $this->setUrl(Yii::$app->urlManager->createUrl(['/rbacviewer/']));
        $this->setCode('RBACVIEWER');
        $this->setModuleName('rbacviewer');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-third'
        ]));
    }

}
