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

use open20\amos\admin\AmosAdmin;
use open20\amos\core\interfaces\OrganizationsModuleInterface;
use open20\amos\core\record\Record;
use open2\amos\ticket\AmosTicket;

/**
 * Class Ticket
 *
 * This is the base-model class for table "ticket".
 *
 * @property integer $id
 * @property string $titolo
 * @property string $descrizione_breve
 * @property string $descrizione
 * @property string $status
 * @property integer $closed_by
 * @property string $closed_at
 * @property integer $ticket_categoria_id
 * @property integer $version
 * @property integer $forwarded_from_id
 * @property integer $forwarded_by
 * @property string $forwarded_at
 * @property string $forward_message
 * @property integer $forward_message_to_operator
 * @property integer $forward_notify
 * @property string $partnership_type
 * @property integer $partnership_id
 * @property string $organization_name
 * @property string $dossier_id
 * @property string $phone
 * @property string $guest_name
 * @property string $guest_surname
 * @property string $guest_email
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open2\amos\ticket\models\TicketCategorie $ticketCategoria
 * @property \open20\amos\organizzazioni\models\Profilo|\open20\amos\core\interfaces\OrganizationsModelInterface $partnership
 * @property \open2\amos\ticket\models\TicketCategorie $forwardedFromTicket
 * @property \open2\amos\ticket\models\TicketCategorie $nextTicket
 *
 * @package open2\amos\ticket\models\base
 */
class Ticket extends Record
{
    /**
     * @var AmosTicket $ticketModule
     */
    protected $ticketModule = null;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket';
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->ticketModule = AmosTicket::instance();
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        if ($this->ticketModule) {
            if (!empty($this->ticketModule->fieldsConfigurations['required'])) {
                $requiredFields = $this->ticketModule->fieldsConfigurations['required'];
            }
        }
        
        $rules = [
            [['descrizione', 'forward_message', 'organization_name'], 'string'],
            [[
                'closed_by',
                'ticket_categoria_id',
                'version',
                'created_by',
                'updated_by',
                'deleted_by',
                'forwarded_from_id',
                'forwarded_by',
                'forward_message_to_operator',
                'forward_notify',
                'partnership_id'
            ], 'integer'],
            [[
                'closed_at',
                'created_at',
                'updated_at',
                'deleted_at',
                'forwarded_at'
            ], 'safe'],
            [$requiredFields, 'required'],
//            [[
//                'ticket_categoria_id',
//                'titolo',
//                'descrizione'
//            ], 'required'],
            [[
                'status',
                'titolo',
                'guest_name',
                'guest_surname',
                'guest_email',
                'partnership_type'
            ], 'string', 'max' => 255],
            [[
                'dossier_id',
                'phone'
            ], 'string', 'max' => 50],
            [[
                'phone'
            ], 'number'],
            [['ticket_categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open2\amos\ticket\models\TicketCategorie::className(), 'targetAttribute' => ['ticket_categoria_id' => 'id']],
        ];
        
        if ($this->ticketCategoria && $this->ticketCategoria->enable_phone) {
            $rules[] = [['phone'], 'required'];
        }
        
        return $rules;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosTicket::t('amosticket', 'Ticket ID'),
            'titolo' => AmosTicket::t('amosticket', 'Titolo'),
            'descrizione' => AmosTicket::t('amosticket', 'Descrizione'),
            'status' => AmosTicket::t('amosticket', 'Status'),
            'closed_by' => AmosTicket::t('amosticket', 'Closed By'),
            'closed_at' => AmosTicket::t('amosticket', 'Closed At'),
            'ticket_categoria_id' => AmosTicket::t('amosticket', 'Categoria'),
            'version' => AmosTicket::t('amosticket', 'Version'),
            'created_at' => AmosTicket::t('amosticket', 'Aperto il'),
            'updated_at' => AmosTicket::t('amosticket', 'Updated At'),
            'deleted_at' => AmosTicket::t('amosticket', 'Deleted At'),
            'created_by' => AmosTicket::t('amosticket', 'Aperto Da'),
            'updated_by' => AmosTicket::t('amosticket', 'Updated By'),
            'deleted_by' => AmosTicket::t('amosticket', 'Deleted By'),
            'forwarded_from_id' => AmosTicket::t('amosticket', 'Ticket forwarded from'),
            'forwarded_by' => AmosTicket::t('amosticket', 'Forwarder By'),
            'forwarded_at' => AmosTicket::t('amosticket', 'Forwarder At'),
            'forward_message' => AmosTicket::t('amosticket', 'Forward Message'),
            'forward_message_to_operator' => AmosTicket::t('amosticket', 'Forward Message Visible to Operator'),
            'forward_notify' => AmosTicket::t('amosticket', '#forward_notify_field_label'),
            'partnership_type' => AmosTicket::t('amosticket', 'Tipo partnership'),
            'partnership_id' => AmosTicket::t('amosticket', 'Relativo alla partnership principale '),
            'organization_name' => AmosTicket::t('amosticket', 'Organization'),
            'dossier_id' => AmosTicket::t('amosticket', 'Dossier Id'),
            'phone' => AmosTicket::t('amosticket', 'Phone'),
            'guest_name' => AmosTicket::t('amosticket', 'Guest name'),
            'guest_surname' => AmosTicket::t('amosticket', 'Guest surname'),
            'guest_email' => AmosTicket::t('amosticket', 'Guest email'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketCategoria()
    {
        return $this->hasOne(\open2\amos\ticket\models\TicketCategorie::className(), ['id' => 'ticket_categoria_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getPartnership()
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        if (!is_null($adminModule)) {
            $organizationsModuleName = $adminModule->getOrganizationModuleName();
            $organizationsModule = \Yii::$app->getModule($organizationsModuleName);
            if (!is_null($organizationsModule) && ($organizationsModule instanceof OrganizationsModuleInterface)) {
                $organizationModelClassName = $organizationsModule->getOrganizationModelClass();
                return $this->hasOne($organizationModelClassName, ['id' => 'partnership_id']);
            }
        }
        return null;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForwardedFromTicket()
    {
        return $this->hasOne(\open2\amos\ticket\models\Ticket::className(), ['id' => 'forwarded_from_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNextTicket()
    {
        return $this->hasOne(\open2\amos\ticket\models\Ticket::className(), ['forwarded_from_id' => 'id']);
    }
    
    /**
     * Internal Ticket or External
     *
     * @return bool
     */
    public function isGuestTicket()
    {
        // il creatore è nullo
        if (is_null($this->created_by)) {
            return true;
        }
        
        // il creatore non è nullo, ma è comunqu empty... tipo zero
        if (!is_null($this->created_by) && empty($this->created_by)) {
            return true;
        }
        
        // il creatore è un utente guest di piattaforma
        $guestUserId = \Yii::$app->params['platformConfigurations']['guestUserId'] ?? null;
        if (!is_null($guestUserId) && ($this->created_by == $guestUserId)) {
            return true;
        }
        
        // tutte le condizioni per valutare se il ticket è esterno sono passate... allore è un ticket interno
        return false;
    }
}
