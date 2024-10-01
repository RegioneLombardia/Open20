<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models\base
 * @category   CategoryName
 */

namespace open2\amos\ticket\models\base;

use open2\amos\ticket\AmosTicket;

/**
 * This is the base-model class for table "ticket_categorie_users_mm".
 *
 * @property integer $id
 * @property integer $ticket_categoria_id
 * @property integer $user_profile_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\admin\models\ $user
 * @property \open2\amos\ticket\models\TicketCategorie $ticketCategoria
 */
class TicketCategorieUsersMm extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket_categorie_users_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_categoria_id', 'user_profile_id'], 'required'],
            [['ticket_categoria_id', 'user_profile_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['user_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\admin\models\UserProfile::className(), 'targetAttribute' => ['user_profile_id' => 'id']],
            [['ticket_categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketCategorie::className(), 'targetAttribute' => ['ticket_categoria_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosTicket::t('amosticket', 'Id'),
            'ticket_categoria_id' => AmosTicket::t('amosticket', 'Categoria Id'),
            'user_profile_id' => AmosTicket::t('amosticket', 'User ID'),
            'created_at' => AmosTicket::t('amosticket', 'Creato il'),
            'updated_at' => AmosTicket::t('amosticket', 'Aggiornato il'),
            'deleted_at' => AmosTicket::t('amosticket', 'Cancellato il'),
            'created_by' => AmosTicket::t('amosticket', 'Creato da'),
            'updated_by' => AmosTicket::t('amosticket', 'Aggiornato da'),
            'deleted_by' => AmosTicket::t('amosticket', 'Cancellato da'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(\open20\amos\admin\models\UserProfile::className(), ['id' => 'user_profile_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketCategoria()
    {
        return $this->hasOne(\open2\amos\ticket\models\TicketCategorie::className(), ['id' => 'ticket_categoria_id']);
    }
}
