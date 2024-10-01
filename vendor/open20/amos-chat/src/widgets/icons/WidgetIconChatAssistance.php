<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\chat\models\Message;
use open20\amos\chat\AmosChat;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconChatAssistance
 * @package open20\amos\chat\widgets\icons
 */
class WidgetIconChatAssistance extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosChat::tHtml('amoschat', 'Assistenza'));
        $this->setDescription(AmosChat::t('amoschat', 'Hai bisogno di assistenza?'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('assistenza');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('comments-o');
        }

        $amosChat = AmosChat::getInstance();

        $this->setUrl(['/messages', 'contactId' => $amosChat->assistanceUserId]);
        $this->setCode('CHAT');
        $this->setModuleName('chat');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan($paramsClassSpan);

        if ($this->disableBulletCounters == false) {
            $counter = Message::find()
            ->andWhere([
                'is_new' => true,
                'receiver_id' => $user_id,
                'is_deleted_by_receiver' => false
            ])
            ->count();
            
            $this->setBulletCount($counter);
        }
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @return array
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
                parent::getOptions(),
                ['children' => []]
        );
    }

    /**
     * 
     * @return boolean
     */
    public function isVisible()
    {
        if ($return = \Yii::$app->getUser()->can($this->getWidgetPermission())) {
            if (\Yii::$app->getModule('chat')->assistanceUserId != Yii::$app->user->id) {
                return true;
            }
        }

        return false;
    }

}
