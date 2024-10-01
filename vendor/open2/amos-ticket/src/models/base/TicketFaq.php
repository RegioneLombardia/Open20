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
 * This is the base-model class for table "ticket_faq".
 *
 * @property integer $id
 * @property string $domanda
 * @property string $risposta
 * @property integer $ticket_categoria_id
 * @property integer $version
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open2\amos\ticket\models\TicketCategorie $ticketCategoria
 */
class TicketFaq extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket_faq';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domanda', 'risposta'], 'string'],
            [['ticket_categoria_id', 'domanda', 'risposta'], 'required'],
            [['ticket_categoria_id', 'version', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
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
            'domanda' => AmosTicket::t('amosticket', 'Domanda'),
            'risposta' => AmosTicket::t('amosticket', 'Risposta'),
            'ticket_categoria_id' => AmosTicket::t('amosticket', 'Categoria'),
            'created_at' => AmosTicket::t('amosticket', 'Creato il'),
            'updated_at' => AmosTicket::t('amosticket', 'Aggiornato il'),
            'deleted_at' => AmosTicket::t('amosticket', 'Cancellato il'),
            'created_by' => AmosTicket::t('amosticket', 'Creato da'),
            'updated_by' => AmosTicket::t('amosticket', 'Aggiornato da'),
            'deleted_by' => AmosTicket::t('amosticket', 'Cancellato da'),
            'version' => AmosTicket::t('amosticket', 'Versione numero'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketCategoria()
    {
        return $this->hasOne(\open2\amos\ticket\models\TicketCategorie::className(), ['id' => 'ticket_categoria_id']);
    }
}
