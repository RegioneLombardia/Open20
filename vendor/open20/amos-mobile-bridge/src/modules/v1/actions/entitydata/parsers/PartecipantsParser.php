<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers;

use open20\amos\admin\models\UserProfile;

/**
 * Description of PartecipantsParser
 *
 */
class PartecipantsParser {

    public static function parseItem($item) {
        $newItem = [];

        //Creator profile
        $owner = UserProfile::findOne(['user_id' => $item->user_id]);

        //Fill fields from item usable in app
        $newItem['fields'] = [
            'id' => $item->user_id,
            'status' => $item->status,
            'role' => $item->role,
            'invited_at' => $item->invited_at,
            'invitation_accepted_at' => $item->invitation_accepted_at,
            'invitation_partner_of' => $item->invitation_partner_of,
            'nome' => $owner->nome,
            'cognome' => $owner->cognome,
            'presentazione_breve' => $owner->presentazione_breve,
            'avatarUrl' => $owner->avatarWebUrl,
        ];


        return $newItem;
    }

}
