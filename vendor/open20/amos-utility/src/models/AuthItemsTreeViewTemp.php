<?php
namespace open20\amos\utility\models;

use yii\db\ActiveRecord;

/**
 * This is the base-model class for table "z_test".
 *
 * @property integer $id
 * @property integer $parent
 * @property string $item
 * @property integer $type
 */

class AuthItemsTreeViewTemp extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%auth_items_tree_view_temp}}';
    }

    public function __toString()
    {
        return '';
    }

}