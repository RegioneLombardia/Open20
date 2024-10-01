<?php

namespace app\modules\cms\admin\apis;

use luya\cms\base\BlockInterface;
use Yii;
use luya\cms\models\BlockGroup;
use app\modules\cms\components\AdminUser;

/**
 * Admin Api delievers common api tasks like blocks and layouts.
 *
 * @since 1.0.0
 */
class AdminController extends \luya\cms\admin\apis\AdminController
{   
    /**
     * Get all blocks which can be dropped into a page grouped by group.
     *
     * @return array An array with list of groups with an array key "blocks" containing the blocks.
     */
    public function actionDataBlocks()
    {    
       
        $favs = Yii::$app->adminuser->identity->setting->get("blockfav", []);
               
        $isAdmin = AdminUser::isAdmin();
        
        $groups = [];
        $uikitModule = Yii::$app->getModule('uikit');         
        foreach (BlockGroup::find()->with(['blocks'])->all() as $blockGroup) {
            
            $blocks = [];

            if($blockGroup->identifier == 'disabled'){
                continue;
            }
            
            if($blockGroup->identifier == 'development-group' && !Yii::$app->user->can('ADMIN') && (($uikitModule->disableDevelopmentGroup) || (!$isAdmin) )){
                 
                continue;
            }

            if(($blockGroup->identifier == 'legacy-group' && ($uikitModule->disableLegacyGroup) ||  !$isAdmin)){
                
                continue;
            }
              
            
            foreach ($blockGroup->blocks as $block) {
                      
                $groupPosition = null;
                
                if ($block->is_disabled) {
                    continue;
                }
                // create the block object
                /** @var BlockInterface $obj */
                $obj = $block->getObject(0, 'admin');

                // check if in hidden blocks
                if (!$obj || in_array(get_class($obj), $this->module->hiddenBlocks)) {
                    continue;
                }
                  
                $groupObject = Yii::createObject($obj->blockGroup());
                
                if ($groupPosition == null) {                    
                    $groupPosition = $groupObject->getPosition();  
                }
                
                //se moduli news, community, ecc non presenti li skippo
                if($groupObject->identifier() == 'contenuto-dinamico-group'){
                    
                    $ModuleObj = Yii::createObject($block->class);
                    if(method_exists($ModuleObj,'getModuleIdInstalled')){
                        $module = \Yii::$app->getModule($ModuleObj->getModuleIdInstalled());
                        if(!$module){
                            continue;
                        }
                        
                    }
                    
                }

                         
                $blocks[] = [
                    'id' => $block->id,
                    'name' => $obj->name(),
                    'icon' => $obj->icon(),
                    'preview_enabled' => $obj->renderAdminPreview() ? true : false,
                    'full_name' => ($obj->icon() === null) ? $obj->name() : '<i class="material-icons">'.$obj->icon().'</i> <span>'.$obj->name().'</span>',
                    'favorized' => array_key_exists($block->id, $favs),
                    'newblock' => 1,
                    'groupPosition'=> $groupPosition,
                ];
            }

           
            if (empty($blocks)) {
                continue;
            }
            
            // extend the group element b
            $group = $blockGroup->toArray([]);
            $group['name'] = $blockGroup->groupLabel;
            $group['is_fav'] = 0;
            $group['toggle_open'] = (int) Yii::$app->adminuser->identity->setting->get("togglegroup.{$group['id']}", 1);

            $groups[] = [
                'groupPosition' => $blocks[0]['groupPosition'],
                'group' => $group,
                'blocks' => $blocks,
            ];
        }
    
        if (!empty($favs)) {
            $favblocks = [];
            foreach ($favs as $fav) {
                $favblocks[] = $fav;
            }
            
            array_unshift($groups, [
                'group' => [
                    'toggle_open' => (int) Yii::$app->adminuser->identity->setting->get("togglegroup.99999", 1),
                    'id' => '99999',
                    'is_fav' => 1,
                    'name' => Yii::t('backendobjects','Preferiti'), // translation stored in admin module
                    'identifier' => 'favs',
                    'position' => 0,
                ],
                'groupPosition' => 0,
                'blocks' => $favblocks,
            ]);
        }


       
        return $groups;
    }
    
    public function actionGetPosts()
    {

        $keys = Yii::$app->request->post('keys');
    
        $data = [];

        if(!empty($keys)){
            $client = new \yii\httpclient\Client();
            $response = $client->createRequest()            
                ->setMethod('GET')
                ->addHeaders(['content-type' => 'application/json','Accept' => 'application/json',])
                ->setUrl(\Yii::$app->params['platform']['frontendUrl'].'/socialwall/socialwall/preview-socialwall?socialwallTokensIds=1,2,3&keywords='.$keys.'&render=false')
                //->setData(['socialwallTokensIds' => '1,2,3'])
                ->send();

            if ($response->isOk) {
                $r = json_decode($response->content);
           die(var_dump($response->content));
                foreach($r as $type=>$social){

                    foreach($social as $row){ 
                        
                        $content = $label = $image = $user = $user_icon = $date = $link = $user_link = ''; 
                        $tot_share = $tot_comments = $tot_like = 0;
                        
                        switch($type){

                            case 'twitter':
                                
                                $label = $row->text;
                                $image = '';
                                $user = $row->userinfo->name;
                                $user_icon = $row->userinfo->profile_image_url;
                                $user_link = 'https://twitter.com/'.$row->userinfo->username;
                                $link = 'https://twitter.com/'.$row->userinfo->username.'/status/'.$row->id;                               
                                $date = date('Y-m-d H:i:s',strtotime($row->created_at));
                                $content = $row->text;
                                $tot_share = $row->public_metrics->retweet_count;
                                $tot_comments = $row->public_metrics->reply_count;
                                $tot_like = $row->public_metrics->like_count;
                                
                            break;

                            case 'instagram':
                                
                                $label = $row->caption;
                                $image = $row->media_url;
                                $user = $row->username;
                                $user_icon = '';
                                $user_link = 'https://www.instagram.com/'.$row->username;                             
                                $link = $row->permalink;
                                $date = date('Y-m-d H:i:s',strtotime($row->timestamp));
                                $content = $row->caption;
           
                            break;

                        }
    
                        $exist = array_search($row->id, array_column($data, 'id'));                                                 
                        if($exist === false)
                            $data[] = [
                                'id'=>$row->id,
                                'value' => $content, 
                                'label' => $label,
                                'social' => $type,
                                'image'=> $image,
                                'user'=> $user,
                                'user_icon' => $user_icon,
                                'date' => $date,
                                'link' => $link,
                                'user_link' => $user_link,
                                'tot_share' => $tot_share,
                                'tot_comments' => $tot_comments,
                                'tot_like' => $tot_like,
                                
                                
                            ];
  
                    }
                }
            }
        }
 
        return $data;
    }
    
    public function actionGetSocial()
    {
        $data = [];
        $social = \open20\socialwall\utility\Social::getSocialwallTokensEnabled();
        
        foreach($social as $id => $val)
            $data[] = ['id'=>$id,'value'=>$val];
       
        return $data;
    }
}
