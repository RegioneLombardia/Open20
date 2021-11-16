<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models\search
 * @category   CategoryName
 */

namespace backend\modules\eventsadmin\models\search;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\SearchResult;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

/**
 * Class UserProfileSearch
 *
 * UserProfileSearch represents the model behind the search form about `common\models\UserProfile`.
 *
 * @property string $email
 *
 * @package open20\amos\admin\models\search
 */
class UserProfileSearch extends \open20\amos\admin\models\search\UserProfileSearch
{
    /**
     * @var string $username
     */
    public $username = '';
    /**
     * @var
     */
    public $dg_id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'privacy'], 'integer'],
            [[
                'nome',
                'cognome',
                'username',
                'email',
                'sesso',
                'codice_fiscale',
                'prevalent_partnership_id',
                'facilitatore_id',
                'status',
                'isFacilitator',
                'isOperatingReferent',
                'userProfileStatus',
                'validato_almeno_una_volta',
                'dg_id'
                ], 'safe'],
        ];
    }


    /**
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere([
            UserProfile::tableName().'.status' => $this->userProfileStatus,
            UserProfile::tableName().'.validato_almeno_una_volta' => $this->validato_almeno_una_volta,
        ]);

        $query->andFilterWhere(['like', UserProfile::tableName().'.nome', $this->nome])
            ->andFilterWhere(['like', UserProfile::tableName().'.cognome', $this->cognome])
            ->andFilterWhere(['like', User::tableName().'.username', $this->username])
            ->andFilterWhere(['codice_fiscale' => $this->codice_fiscale])
            ->andFilterWhere(['like', User::tableName().'.email', $this->email]);

        if(!empty($this->dg_id)){
            $query->leftJoin('event_group_referent_mm', 'event_group_referent_mm.user_id = user_profile.user_id');
            $query->andFilterWhere(['event_group_referent_mm.event_group_referent_id' => $this->dg_id]);
        }

        if (
            $this->adminModule->confManager->isVisibleBox('box_prevalent_partnership',
                ConfigurationManager::VIEW_TYPE_FORM) &&
            $this->adminModule->confManager->isVisibleField('prevalent_partnership_id',
                ConfigurationManager::VIEW_TYPE_FORM)
        ) {
            $this->userProfileSelectFieldsQuery($query, 'prevalent_partnership_id');
        }
        $this->userProfileSelectFieldsQuery($query, 'facilitatore_id');

        // If value is "-1" it mean the user is searching whether the sex value is not selected.
        if ($this->sesso == -1) {
            $query->andWhere(['or', [UserProfile::tableName().'.sesso' => null], [UserProfile::tableName().'.sesso' => '']]);
        } else {
            $query->andFilterWhere([
                UserProfile::tableName().'.sesso' => $this->sesso
            ]);
        }

        $this->userProfileRolesQuery($query, 'isFacilitator', 'FACILITATOR');
        $organizationModuleName = $this->adminModule->getOrganizationModuleName();
        if (($organizationModuleName == 'organizations') && !is_null(Yii::$app->getModule($organizationModuleName))) {
            $this->userProfileRolesQuery($query, 'isOperatingReferent', 'OPERATING_REFERENT');
        }

        return $query;
    }

}