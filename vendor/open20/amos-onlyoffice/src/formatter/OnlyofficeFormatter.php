<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\onlyoffice\formatter
 * @category   CategoryName
 */

namespace open20\onlyoffice\formatter;

use open20\amos\core\formatter\Formatter;
use open20\amos\admin\models\UserProfile;

/**
 * Class OnlyofficeFormatter
 *
 * @package open20\onlyoffice\formatter
 */
class OnlyofficeFormatter extends Formatter
{
    public function asUserId($value)
    {
        if (empty($value))
        {
            return $this->nullDisplay;
        }
        $modelUserP = UserProfile::find()
            ->andWhere(['user_id' => $value])
            ->one();
        if (empty($modelUserP))
        {
            return 'Non trovato';
        }
        return $modelUserP->nomeCognome;
    }
}