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
 * Class ConversationWidget
 * @package open20\amos\chat\widgets
 */
class ConversationWidget extends ListView
{
    /**
     * @var string
     */
    public static $CONVERSATION_TEMPLATE = '@vendor/open20/amos-chat/src/widgets/views/conversation.php';

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
            $this->clientOptions['itemCssClass'] = 'conversation';
        }
        $this->tag = ArrayHelper::remove($this->options, 'tag', 'div');
        echo Html::beginTag($this->tag, $this->options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerJs();
        echo Html::endTag($this->tag);
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
        $view->registerJs("jQuery('#$id').amosChatConversations($user, $current, $options);");
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
                'isCurrent' => (!is_null($this->current) ? ($model['contact']['id'] == $this->current['contact']['id']) : false),
                'settings' => $this->clientOptions,
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $index, $this);
        }
        return $content;
    }

    /**
     * @param string $name
     * @return bool|string
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{items}':
                return $this->renderItems();
            default:
                return false;
        }
    }
}
