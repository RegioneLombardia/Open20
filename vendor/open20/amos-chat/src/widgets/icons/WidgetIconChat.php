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

use open20\amos\chat\models\Message;
use open20\amos\chat\AmosChat;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconChat
 * @package open20\amos\chat\widgets\icons
 */
class WidgetIconChat extends WidgetIcon
{
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosChat::tHtml('amoschat', 'Messaggi privati'));
        $this->setDescription(AmosChat::t('amoschat', 'Visualizza i messaggi privati'));

        $this->setIcon('comments-o');
        $this->setUrl(['/messages']);
        $this->setCode('CHAT');
        $this->setModuleName('chat');
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
        
        if ($this->disableBulletCounters == false) {
            $counter = Message::find()->leftJoin('user_profile u','u.user_id = sender_id')->andWhere([
                'is_new' => true,
                'receiver_id' => Yii::$app->getUser()->getId(),
                'is_deleted_by_receiver' => false
            ])->andWhere(['u.attivo'=>1])->count();
            
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

}
