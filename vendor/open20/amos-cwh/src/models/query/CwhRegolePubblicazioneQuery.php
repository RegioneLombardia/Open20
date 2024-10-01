<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\models\query;


use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use yii\db\ActiveQuery;

class CwhRegolePubblicazioneQuery extends ActiveQuery
{

    /**
     * exclude Rule 1 (Public to all users) if the logged user has not the role to allow using rule 1
     * @return self $this
     */
    public function filterByRole()
    {

        $roleToCheck = AmosCwh::getInstance()->regolaPubblicazioneFilterRole;
        if (!\Yii::$app->getUser()->can($roleToCheck)) {
            $this->andWhere([
                'not', ['id' => CwhRegolePubblicazione::ALL_USERS]
            ]);
        }

        return $this;

    }

    /**
     * exclude Rules 2 and 4 (to all users based on tags and to all user based on network and tags)
     * @return self $this
     */
    public function excludeTag()
    {
        $this->andWhere(['not in', 'id', [CwhRegolePubblicazione::ALL_USERS_WITH_TAGS, CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS]]);
        return $this;
    }

    /**
     * If working under a specific network scope (eg. community dashboard)
     * exclude Rules 1 and 2 (to all users and based on tags and to all user based on tags)
     * selectable only rule 3 (all users in domain) and 4 if tag module is present(all users in domain with matching tags)
     * @return self $this
     */
    public function onlyNetwork()
    {
        $this->andWhere(['not in', 'id', [CwhRegolePubblicazione::ALL_USERS, CwhRegolePubblicazione::ALL_USERS_WITH_TAGS]]);
        return $this;
    }

}
