<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\models
 * @category   CategoryName
 */

namespace open20\amos\cwh\models;

/**
 * Class CwhRegolePubblicazione
 * This is the model class for table "cwh_regole_pubblicazione".
 * @package open20\amos\cwh\models
 */
class CwhRegolePubblicazione extends \open20\amos\cwh\models\base\CwhRegolePubblicazione
{
    const ALL_USERS = 1;
    const ALL_USERS_WITH_TAGS = 2;
    const ALL_USERS_IN_DOMAINS = 3;
    const ALL_USERS_IN_DOMAINS_WITH_TAGS = 4;

    public static function find()
    {
        return new \open20\amos\cwh\models\query\CwhRegolePubblicazioneQuery(get_called_class());
    }
}
