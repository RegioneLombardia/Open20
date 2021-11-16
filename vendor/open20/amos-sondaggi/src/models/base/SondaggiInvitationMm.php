<?php
/**
 * Lombardia Informatica S.p.A.
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
 * @property integer $invitation_class
 * @property integer $to_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\sondaggi\models\base
 */
abstract class SondaggiInvitationMm extends \open20\amos\core\record\Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sondaggi_invitation_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sondaggi_id', 'invitation_class', 'invitation_id'], 'integer'],
            [['sondaggi_id', 'invitation_class', 'invitation_id'], 'required'],
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
            'invitation_class' => AmosSondaggi::t('amossondaggi', 'Classe To'),
            'invitation_id' => AmosSondaggi::t('amossondaggi', 'ID To')
        ];
    }
}
