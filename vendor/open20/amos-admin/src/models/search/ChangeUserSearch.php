<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models\search
 * @category   CategoryName
 */

namespace open20\amos\admin\models\search;

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\user\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class ChangeUserSearch
 * @package open20\amos\admin\models\search
 */
class ChangeUserSearch extends UserProfile
{
    /**
     * @var string $email
     */
    public $email = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'nome',
                'cognome',
                'email',
                'codice_fiscale',
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * This is the base search.
     * @param array $params
     * @return ActiveQuery
     */
    public function baseSearch($params)
    {
        /** @var ActiveQuery $query */
        $query = $this->adminModule->createModel('UserProfile')->find()->innerJoinWith(['user']);

        // Init the default search values
        $this->initOrderVars();

        // Check params to get orders value
        $this->setOrderVars($params);

        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere(['like', UserProfile::tableName() . '.nome', $this->nome])
            ->andFilterWhere(['like', UserProfile::tableName() . '.cognome', $this->cognome])
            ->andFilterWhere(['codice_fiscale' => $this->codice_fiscale])
            ->andFilterWhere(['like', User::tableName() . '.email', $this->email]);
        return $query;
    }

    /**
     * Search all active users.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $logggedUserCf = \Yii::$app->user->identity->userProfile->codice_fiscale;
        $userProfileTable = UserProfile::tableName();
        $query = $this->baseSearch($params);
        if (!empty($logggedUserCf)) {
            $query->andWhere([$userProfileTable . '.attivo' => UserProfile::STATUS_ACTIVE]);
            $query->andWhere([$userProfileTable . '.codice_fiscale' => $logggedUserCf]);
            $query->andWhere(['<>', $userProfileTable . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
            $query->orderBy([$userProfileTable . '.created_at' => SORT_ASC]);
        } else {
            $query->where('0');
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }
}
