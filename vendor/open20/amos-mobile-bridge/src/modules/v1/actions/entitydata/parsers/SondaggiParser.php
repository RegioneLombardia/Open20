<?php

namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers;

use open20\amos\admin\models\UserProfile;
use open20\amos\sondaggi\models\Sondaggi;
use Yii;
use yii\helpers\StringHelper;

class SondaggiParser {

    /**
     * 
     * @param Sondaggi $item
     * @return array
     */
    public static function parseItem($item) {
        //The base class name
        $baseClassName = StringHelper::basename(Sondaggi::className());

        //Read permission name
        $readPremission = strtoupper($baseClassName . '_READ');

        //Edit permission name
        $editPremission = strtoupper($baseClassName . '_UPDATE');

        //Can user view element
        $canView = Yii::$app->user->can($readPremission, ['model' => $item]);

        if ($canView) {
            //Define temp item
            $newItem = [];

            //Need id column
            $newItem['id'] = $item->id;

            //Get the list of description fields
            $newItem['representingColumn'] = $item->representingColumn();

            //Creator profile
            $owner = UserProfile::findOne(['id' => $item->created_by]);

            //Image
            $image = $item->file;

            //Fill fields from item usable in app
            $newItem['fields'] = [
                'title' => $item->titolo,
                'description' => $item->descrizione,
                'compilazioni_disponibili' => $item->compilazioni_disponibili,
                'created_at' => $item->created_at,
                'created_by' => $item->created_by,
                'owner' => [
                    'nome' => $owner->nome,
                    'cognome' => $owner->cognome,
                    'presentazione_breve' => $owner->presentazione_breve,
                    'avatarUrl' => $owner->avatarWebUrl,
                ],
                'sondaggioImageUrl' => $image ? Yii::$app->getUrlManager()->createAbsoluteUrl($image->getWebUrl()) : null,
            ];

            //Remove id as is not needed
            unset($newItem['fields']['id']);

            //Can edit
            $newItem['canEdit'] = Yii::$app->user->can($editPremission, ['model' => $item]);

            return $newItem;
        }

        return [];
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    public static function parsePageItem($item) {

        $newItem = [];

        //Creator profile
        $owner = UserProfile::findOne(['id' => $item->created_by]);

        //Image
        $image = $item->file;

        //Fill fields from item usable in app
        $newItem = [
            'id' => $item->id,
            'title' => $item->titolo,
            'description' => $item->descrizione,
            //'ordinamento' => $item->ordinamento,
            'created_at' => $item->created_at,
            'created_by' => $item->created_by,
            'owner' => [
                'nome' => $owner->nome,
                'cognome' => $owner->cognome,
                'presentazione_breve' => $owner->presentazione_breve,
                'avatarUrl' => $owner->avatarWebUrl,
            ],
            'pageImageUrl' => $image ? Yii::$app->getUrlManager()->createAbsoluteUrl($image->getWebUrl()) : null,
        ];

        return $newItem;
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    public static function parseQuestionItem($item) {

        //Define temp item
        $newItem = [];

        //Get the list of description fields
        //$newItem['representingColumn'] = $item->representingColumn();

        //Creator profile
        $owner = UserProfile::find()->andWhere(['user_id' => $item->created_by])->one();

        //Fill fields from item usable in app
        $newItem = [
            'id' => $item->id,
            'label' => $item->domanda,
            'domanda_condizionata' => $item->domanda_condizionata,
            'obbligatoria' => $item->obbligatoria,
            //'ordinamento' => $item->ordinamento,
            //'min_int_multipla' => $item->min_int_multipla,
            //'max_int_multipla' => $item->max_int_multipla,
            'type' => $item->sondaggiDomandeTipologie->tipologia,
            'created_at' => $item->created_at,
            'created_by' => $item->created_by,
            'owner' => [
                'nome' => $owner->nome,
                'cognome' => $owner->cognome,
                'presentazione_breve' => $owner->presentazione_breve,
                'avatarUrl' => $owner->avatarWebUrl,
            ],
        ];


        return $newItem;
    }
    
    /**
     * 
     * @param type $item
     * @return type
     */
    public static function parseDefaultResponseItem($item) {

        //Define temp item
        $newItem = [];

        //Get the list of description fields
        //$newItem['representingColumn'] = $item->representingColumn();

        //Creator profile
        $owner = UserProfile::findOne(['id' => $item->created_by]);

        //Fill fields from item usable in app
        $newItem = [
            'id' => $item->id,
            'label' => $item->risposta,
            //'ordinamento' => $item->ordinamento,
            'created_at' => $item->created_at,
            'created_by' => $item->created_by,
            'owner' => [
                'nome' => $owner->nome,
                'cognome' => $owner->cognome,
                'presentazione_breve' => $owner->presentazione_breve,
                'avatarUrl' => $owner->avatarWebUrl,
            ],
        ];


        return $newItem;
    }

}
