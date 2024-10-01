<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models\base
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models\base;

use open20\amos\sondaggi\AmosSondaggi;

/**
 * Class Sondaggi
 *
 * This is the base-model class for table "sondaggi".
 *
 * @property integer $id
 * @property integer $sondaggi_id
 * @property integer $user_id
 * @property integer $to_id
 * @property integer $invitation_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\sondaggi\models\base
 */
abstract class SondaggiUsersInvitationMm extends \open20\amos\core\record\Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sondaggi_users_invitation_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sondaggi_id', 'user_id', 'to_id'], 'integer'],
            [['sondaggi_id', 'user_id', 'to_id'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosSondaggi::t('amossondaggi', 'ID'),
            'sondaggi_id' => AmosSondaggi::t('amossondaggi', 'Poll ID'),
            'user_id' => AmosSondaggi::t('amossondaggi', 'User ID'),
            'to_id' => AmosSondaggi::t('amossondaggi', 'ID To')
        ];
    }
}
