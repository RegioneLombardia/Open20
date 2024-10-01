<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\tag\models\Tag;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * 
 */
class TagSearch extends Tag implements CmsModelInterface
{
    /**
     * 
     * @param type $id
     * @return boolean
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }

    /**
     * 
     * @param type $params
     * @param type $limit
     * @return type
     */
    public function cmsSearch($params, $limit)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $dataProvider = $this->search($params);
        $query = $dataProvider->query;
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        $query->orderBy('nome');

        return $dataProvider;
    }

    /**
     * 
     * @return array
     */
    public function cmsSearchFields()
    {
        return [
            new CmsField('nome', 'TEXT'),
            new CmsField('descrizione', 'TEXT')
        ];
    }

    /**
     * 
     * @return array
     */
    public function cmsViewFields()
    {
        return [
            new CmsField('nome', 'TEXT', 'amostag', 'nome'),
            new CmsField('descrizione', 'TEXT', 'amostag', 'descrizione'),
        ];
    }

    /**
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Tag::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'root' => $this->root,
            'lft' => $this->lft,
            'rgt' => $this->rgt,
            'lvl' => $this->lvl,
            'limit_selected_tag' => $this->limit_selected_tag,
            'icon_type' => $this->icon_type,
            'active' => $this->active,
            'selected' => $this->selected,
            'disabled' => $this->disabled,
            'readonly' => $this->readonly,
            'visible' => $this->visible,
            'collapsed' => $this->collapsed,
            'movable_u' => $this->movable_u,
            'movable_d' => $this->movable_d,
            'movable_l' => $this->movable_l,
            'movable_r' => $this->movable_r,
            'removable' => $this->removable,
            'removable_all' => $this->removable_all,
            'frequency' => $this->frequency,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'nome_en', $this->nome_en])
            ->andFilterWhere(['like', 'codice', $this->codice])
            ->andFilterWhere(['like', 'codice_en', $this->codice_en])
            ->andFilterWhere(['like', 'descrizione', $this->descrizione])
            ->andFilterWhere(['like', 'descrizione_en', $this->descrizione_en])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }

}