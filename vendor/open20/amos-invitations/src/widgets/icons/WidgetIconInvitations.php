<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations
 * @category   CategoryName
 */

namespace open20\amos\invitations\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconInvitations
 * @package open20\amos\invitations\widgets\icons
 */
class WidgetIconInvitations extends WidgetIcon
{
    public function init()
    {
        parent::init();
        
        $this->setLabel(\Yii::t('amosinvitations', 'Invitations'));
        $this->setDescription(Yii::t('amosinvitations', 'To manage invitations to the platform'));
        
        $this->setIcon('notifications');
        $this->setIconFramework('am');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/invitations/invitation/index']));
        $this->setModuleName('amos_invitations');
        $this->setNamespace(__CLASS__);
        
        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-primary'
                ]
            )
        );
    }
}
