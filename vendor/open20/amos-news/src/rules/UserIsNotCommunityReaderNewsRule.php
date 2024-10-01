<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\rules
 * @category   CategoryName
 */

namespace open20\amos\news\rules;

use open20\amos\core\rules\UserCreatorContentOnDomain;

class UserIsNotCommunityReaderNewsRule extends UserCreatorContentOnDomain
{
    public $name = 'userIsNotCommunityReaderNewsRule';
    
    public function execute($user, $item, $params)
    {
        // RULE PER CREAZIONE NEWS
        // Se è una news di piattaforma CREATORE_NEWS può crearla di default,
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