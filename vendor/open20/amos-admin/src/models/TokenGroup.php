<?php

namespace open20\amos\admin\models;

use open20\amos\core\user\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "token_group".
 */
class TokenGroup extends \open20\amos\admin\models\base\TokenGroup
{
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
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
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'Description',
                'label' => $labels['Description'],
                'type' => 'string'
            ],
            [
                'slug' => 'url_redirect',
                'label' => $labels['url_redirect'],
                'type' => 'string'
            ],
            [
                'slug' => 'target_class',
                'label' => $labels['target_class'],
                'type' => 'string'
            ],
            [
                'slug' => 'target_id',
                'label' => $labels['target_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'consumable',
                'label' => $labels['consumable'],
                'type' => 'integer'
            ],
            [
                'slug' => 'expire_date',
                'label' => $labels['expire_date'],
                'type' => 'datetime'
            ],
        ];
    }

    /**
     * @param $ids
     * @throws \yii\base\InvalidConfigException
     */
    public function generateTokenUsersByIds($ids){
        foreach ($ids as $id){
            $this->generateSingleTokenUser($id);
        }
    }


    /**
     * @param $id
     * @return TokenUsers|null
     * @throws \yii\base\InvalidConfigException
     */
    public function generateSingleTokenUser($id){
        $user = User::findOne($id);
        if($user){
            $tokenuser = TokenUsers::find()->andWhere(['token_group_id' => $this->id, 'user_id' => $id])->one();
            if(empty($tokenuser)) {
                $tokenuser = new TokenUsers();
                $tokenuser->token_group_id = $this->id;
                $tokenuser->user_id = $user->id;
                $tokenuser->generateToken($user->id, $this->id);
                if($tokenuser->save()){
                    return $tokenuser;
                }
            }else{
                return $tokenuser;
            }
        }
        return null;
    }


    /**
     * @param $classname
     * @param null $id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTokenGroup($string_code){
        $token = TokenGroup::find()
            ->andWhere(['string_code' => $string_code])->one();
        return $token;
    }


}
