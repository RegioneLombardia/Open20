<?php

/**
 * Lombardia Informatica S.p.A.
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
            'id' => AmosMoodle::t('amosmoodle', 'ID'),
            'status' => AmosMoodle::t('amosmoodle', 'Status (workflow)'),
            'order_id' => AmosMoodle::t('amosmoodle', 'Order ID'),
            'user_id' => AmosMoodle::t('amosmoodle', 'User ID'),
            'course_id' => AmosMoodle::t('amosmoodle', 'Course ID'),
            'student_id' => AmosMoodle::t('amosmoodle', 'Student ID'),
            'total' => AmosMoodle::t('amosmoodle', 'Total'),
            'transaction_code' => AmosMoodle::t('amosmoodle', 'Transaction Code - PayPal ID'),
            'token' => AmosMoodle::t('amosmoodle', 'Token from PayPal after payment is ok'),
            'type' => AmosMoodle::t('amosmoodle', 'Type of Transaction'),            
            'wallet_id' => AmosMoodle::t('amosmoodle', 'Wallet ID'),

            'created_at' => AmosMoodle::t('amosmoodle', 'Created At'),
            'created_by' => AmosMoodle::t('amosmoodle', 'Created By'),
            'updated_at' => AmosMoodle::t('amosmoodle', 'Updated At'),
            'updated_by' => AmosMoodle::t('amosmoodle', 'Updated By'),
            'deleted_at' => AmosMoodle::t('amosmoodle', 'Deleted At'),
            'deleted_by' => AmosMoodle::t('amosmoodle', 'Deleted By'),
        ];
    }

}
