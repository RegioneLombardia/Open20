<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\models
 * @category   CategoryName
 */

namespace open20\amos\invitations\models;

use open20\amos\invitations\Module;
use yii\base\Model;

/**
 * Class GoogleInvitationForm
 * @package open20\amos\invitations\models
 */
class GoogleInvitationForm extends Model
{
    public $selection = [];

    public $message = '';

    public $search;

    public function rules()
    {
        return [
            [['selection', 'message'], 'required'],
            ['selection', 'safe'],
            [['message', 'search'], 'string'],
            ['message', \open20\amos\core\validators\StringHtmlValidator::className(), 'max' => 2500],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message' => Module::t('amosinvitations', 'Message'),
            'selection' => Module::t('amosinvitations', '#selected_contacts'),
            'search' => Module::t('amosinvitations', 'Search name or email'),
        ];
    }

}
