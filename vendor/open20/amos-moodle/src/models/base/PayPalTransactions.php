<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models
 * @category   CategoryName
 */

namespace open20\amos\moodle\models\base;

use Yii;

/**
 * This is the base-model class for table "paypal_transactions".
 *
 * @property integer $id
 * @property string  $status
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $course_id
 * @property integer $student_id
 * @property double  $total
 * @property string  $transaction_code
 * @property string  $token
 * @property string  $type
 * @property string  $wallet_id
 * 
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $deleted_at
 * @property integer $deleted_by
 */
class PayPalTransactions extends \open20\amos\core\record\Record
{
    const TRANSACTIONS_WORKFLOW                      = 'TransactionsWorkflow';
    const TRANSACTIONS_WORKFLOW_STATUS_DA_EFFETTUARE = 'TransactionsWorkflow/DA_EFFETTUARE';
    const TRANSACTIONS_WORKFLOW_STATUS_EFFETTUATO    = 'TransactionsWorkflow/EFFETTUATO';
    const TRANSACTIONS_WORKFLOW_STATUS_MONEYOUT      = 'TransactionsWorkflow/MONEYOUT';
    const TRANSACTIONS_WORKFLOW_STATUS_CANCELLATO    = 'TransactionsWorkflow/CANCELLATO';
    const TRANSACTIONS_WORKFLOW_STATUS_FATTURA_BOZZA = 'TransactionsWorkflow/FATTURA_BOZZA';
    const TRANSACTIONS_WORKFLOW_STATUS_FATTURATO     = 'TransactionsWorkflow/FATTURATO';
    const TRANSACTIONS_WORKFLOW_STATUS_PAYPAL        = 'TransactionsWorkflow/PAYPAL';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paypal_transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['order_id', 'user_id', 'course_id', 'student_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['total'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status', 'wallet_id', 'type', ], 'string', 'max' => 128],
            [['transaction_code', 'token'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosMoodle::_t('ID'),
            'status' => AmosMoodle::_t('Status (workflow)'),
            'order_id' => AmosMoodle::_t('Order ID'),
            'user_id' => AmosMoodle::_t('User ID'),
            'course_id' => AmosMoodle::_t('Course ID'),
            'student_id' => AmosMoodle::_t('Student ID'),
            'total' => AmosMoodle::_t('Total'),
            'transaction_code' => AmosMoodle::_t('Transaction Code - PayPal ID'),
            'token' => AmosMoodle::_t('Token from PayPal after payment is ok'),
            'type' => AmosMoodle::_t('Type of Transaction'),            
            'wallet_id' => AmosMoodle::_t('Wallet ID'),

            'created_at' => AmosMoodle::_t('Created At'),
            'created_by' => AmosMoodle::_t('Created By'),
            'updated_at' => AmosMoodle::_t('Updated At'),
            'updated_by' => AmosMoodle::_t('Updated By'),
            'deleted_at' => AmosMoodle::_t('Deleted At'),
            'deleted_by' => AmosMoodle::_t('Deleted By'),
        ];
    }

}
