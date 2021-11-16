<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ListView;

/**
 * Class UserContactsWidget
 * @package open20\amos\chat\widgets
 */
class UserContactsWidget extends ListView
{
    /**
     * @var string
     */
    public static $CONTACT_TEMPLATE = '@vendor/open20/amos-chat/src/widgets/views/user_contact.php';

    /**
     * The current user
     * @var array
     */
    public $user;

    /**
     * The current conversation
     * @var array
     */
    public $current;

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var array
     */
    public $liveOptions = [];

    /**
     * If array, enable only the users in this list (community scope)
     * @var array
     */
    public $usersEnableByCommunityScope = false;

    /**
     * @var
     */
    private $tag;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if (!isset($this->clientOptions['itemCssClass'])) {
            $this->clientOptions['itemCssClass'] = 'user-contact';
        }
        $this->tag = ArrayHelper::remove($this->options, 'tag', 'div');
        //echo Html::beginTag($this->tag, $this->options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerJs();
        /*
        echo $this->renderItems();

        echo Html::endTag($this->tag);
        */
        parent::run();
    }

    /**
     *
     */
    public function registerJs()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->clientOptions);
        $user = Json::htmlEncode($this->user);
        $current = Json::htmlEncode($this->current);
        $view = $this->getView();
        $view->registerJs("jQuery('#$id').amosChatUserContacts($user, $current, $options);");
    }

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return mixed|string
     */
    public function renderItem($model, $key, $index)
    {
        if ($this->itemView === null) {
            $content = $key;
        } elseif (is_string($this->itemView)) {
            $content = $this->getView()->renderFile($this->itemView, array_merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'user' => $this->user,
                'settings' => $this->clientOptions,
                'disableByCommunityScope' => ($this->usersEnableByCommunityScope && !in_array($model->id, $this->usersEnableByCommunityScope) ? true : false)
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $index, $this);
        }
        return $content;
    }

    /**
     * @param string $name
     * @return bool|string

    public function renderSection($name)
    {
        switch ($name) {
            case '{items}':
                return $this->renderItems();
            default:
                return false;
        }
    }
     * */
}
