<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\rules
 * @category   CategoryName
 */

namespace open20\amos\discussioni\rules;

use open20\amos\core\rules\UserCreatorContentOnDomain;

class UserIsNotCommunityReaderDiscussioniRule extends UserCreatorContentOnDomain
{
    public $name = 'userIsNotCommunityReaderDiscussioniRule';

    public function execute($user, $item, $params)
    {
        // RULE PER CREAZIONE DISCUSSIONI
        // Se è una discussione di piattaforma CREATORE_DISCUSSIONI può crearla di default,
        // altrimenti controlla con la rule UserCreatorContentOnDomain
        $cwhModule = \Yii::$app->getModule('cwh');
        if($cwhModule) {
            $scope = $cwhModule->getCwhScope();
            if (empty($scope)) {
                return true;
            } else {
                return parent::execute($user, $item, $params);
            }
        }
        return true;
    }
}