<?php

namespace amos\newsletter\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\newsletter\models\Newsletter;

/**
 * NewsletterSearch represents the model behind the search form about `amos\newsletter\models\Newsletter`.
 */
class NewsletterSearch extends Newsletter
{

//private $container; 

    public function __construct(array $config = [])
    {
        $isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'newsletter_template_id', 'welcome_type', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['subject', 'text', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['NewsletterTemplate', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Newsletter::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('newsletterTemplate');

        $dataProvider->setSort([
            'attributes' => [
                'newsletter_template_id' => [
                    'asc' => ['newsletter.newsletter_template_id' => SORT_ASC],
                    'desc' => ['newsletter.newsletter_template_id' => SORT_DESC],
                ],
                'subject' => [
                    'asc' => ['newsletter.subject' => SORT_ASC],
                    'desc' => ['newsletter.subject' => SORT_DESC],
                ],
                'text' => [
                    'asc' => ['newsletter.text' => SORT_ASC],
                    'desc' => ['newsletter.text' => SORT_DESC],
                ],
                'welcome_type' => [
                    'asc' => ['newsletter.welcome_type' => SORT_ASC],
                    'desc' => ['newsletter.welcome_type' => SORT_DESC],
                ],
                'newsletterTemplate' => [
                    'asc' => ['newsletter_template.id' => SORT_ASC],
                    'desc' => ['newsletter_template.id' => SORT_DESC],
                ],]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'newsletter_template_id' => $this->newsletter_template_id,
            'welcome_type' => $this->welcome_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'text', $this->text]);
        $query->andFilterWhere(['like', new \yii\db\Expression('newsletter_template.id'), $this->NewsletterTemplate]);

        return $dataProvider;
    }
}
