<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models
 * @category   CategoryName
 */

namespace open20\amos\moodle\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "paypal_transactions".
 * 
 * @property string $status
 * @property integer $order_id
 * @property integer $user_id
 * @property double $total
 * @property string $transaction_code
 * @property string $type
 * @property string $wallet_id

 */
class PayPalTransactions extends base\PayPalTransactions
{

    public function representingColumn()
    {
        return [];
    }

    public function attributeHints()
    {
        return [];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), []);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), []);
    }

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'order_id',
                'label' => $labels['order_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'total',
                'label' => $labels['total'],
                'type' => 'float'
            ],
            [
                'slug' => 'transaction_code',
                'label' => $labels['transaction_code'],
                'type' => 'string'
            ],
        ];
    }

}
