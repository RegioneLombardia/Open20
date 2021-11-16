<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\widgets\graphics;

use open20\amos\chat\AmosChat;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\widget\WidgetGraphic;
use yii\helpers\Url;
use Yii;

/**
 * Class WidgetGraphicChatAssistance
 * @package open20\amos\chat\widgets\graphics
 */
class WidgetGraphicChatAssistance extends WidgetGraphic
{

    public $titleWidget = '';
    public $urlImage = '';
    public $assistanceUserId = '';
    public $buttonText = '';
    public $welcome_message = '';

    public $assistanceWidgetId = '';
    public $assistanceUserCommunityMan = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $isScope = false;
        $this->titleWidget = AmosChat::t('amoschat','#widget_graph_chat_assist_title');
        $this->buttonText = AmosChat::t('amos_chat', '#widget_graph_chat_assist_btn_text');

        $moduleCwh = \Yii::$app->getModule('cwh');
        if(isset($moduleCwh)){
            $scope = $moduleCwh->getCwhScope();
            if(!empty($scope) && isset($scope['community'])){

                $moduleChat = \Yii::$app->getModule('chat');
                if(!empty($moduleChat)){
                    if(!empty($moduleChat->assistenzaChatCommunity)){
                        foreach ($moduleChat->assistenzaChatCommunity as $communityId=>$conf){
                            $this->assistanceWidgetId = $communityId;
                            $explode = explode('-',$communityId);
                            $id = end($explode);
                            if($id == $scope['community']){
                                if(!empty($conf['titleWidget'])) {
                                    $this->titleWidget = $conf['titleWidget'];
                                }
                                if(!empty($conf['urlImage'])) {
                                    $this->urlImage = $conf['urlImage'];
                                }
                                if(!empty($conf['btnText'])) {
                                    $this->buttonText = $conf['btnText'];
                                }

                                if(!empty($conf['welcome_message'])) {
                                    $this->welcome_message = $conf['welcome_message'];
                                }

                                $userIdCM = null;
                                if(!empty($conf['assistance_community_manager']) && $conf['assistance_community_manager'] == true) {
                                    $userMm = CommunityUserMm::findOne(['community_id' => $id,  'role' => CommunityUserMm::ROLE_COMMUNITY_MANAGER]);
                                    if($userMm){
                                        $userIdCM = $userMm->user_id;
                                    }
                                }

                                if(!empty($conf['assistanceUserId'])){
                                    $this->assistanceUserId = $conf['assistanceUserId'];
                                }
                                if(!empty($userIdCM)){
                                    $this->assistanceUserId = $userIdCM;
                                }

                                $isScope = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
        $this->setCode('CHAT_ASSISTANCE_GRAPHIC');
        $this->setLabel($this->titleWidget);
        $this->setDescription($this->titleWidget);
        return $isScope;
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        if ((!empty($this->assistanceUserId)) && ($this->assistanceUserId != Yii::$app->user->id)) {
            $url = Url::to(['/messages', 'contactId' => $this->assistanceUserId]);
            return $this->render('chat-assistance', [
                'url' => $url,
                'widget' => $this,
            ]);
        }
    }

}
