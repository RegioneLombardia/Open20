<?php
/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace amos\cmsbridge\models;

use yii\base\Model;

/**
 * Description of PostCmsCreatePage
 *
 */
class PostCmsCreatePage extends Model
{
    public $nav_id;
    public $use_draft;
    public $parent_nav_id;
    public $nav_container_id;
    public $title;
    public $description;
    public $is_draft;
    public $layout_id;
    public $alias;
    public $lang_id;
    public $from_draft_id;
    public $nav_item_type;
    public $cms_user_id;
    public $form_landing = null;
    public $event_data = null;
    public $with_login = 0;


    /**
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->form_landing = new PostCmsFormLanding();
        $this->event_data = new PostCmsEventData();
    }

    /**
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['use_draft', 'parent_nav_id', 'nav_container_id', 'is_draft', 'layout_id',
                'lang_id', 'from_draft_id', 'nav_item_type', 'cms_user_id', 'with_login'], 'integer'],
            [['title', 'description', 'alias'], 'string'],
        ];
    }
}