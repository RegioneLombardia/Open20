<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\controllers\base;

use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use open20\amos\comuni\models\IstatComuni;
use open20\amos\comuni\models\IstatComuniCap;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\comuni\AmosComuni;

class AjaxController extends Controller
{
    /**
     * This is public to be compliant with
     * Access level to open20\amos\comuni\controllers\base\AjaxController::$id 
     * must be public (as in class yii\web\Controller)
     * 
     * @var type
     */
    public $id = null;

    /**
     * 
     * @var type
     */
    protected $req;

    /**
     * 
     * @var type
     */
    protected $id_selected = null;

    /**
     * 
     */
    public function init()
    {
        parent::init();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $this->req = Yii::$app->getRequest()->post();
        if (Yii::$app->getRequest()->isGet) {
            $this->req = Yii::$app->getRequest()->get();
        }

        if (isset($this->req['depdrop_parents'])) {
            $this->id = end($this->req['depdrop_parents']);
            $this->id_selected = end($this->req['depdrop_all_params']);
        }
    }

    /**
     * 
     * @param type $soppresso
     * @return type
     */
    public function actionProvinceByNazione($soppresso = null)
    {
        $out = [];
        if (!empty($this->id)) {
            $query = IstatProvince::find();
            
            // TBD fix it this is a quick & raw sol
            if ($this->id == 1) {
                $this->id = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
            }
            
            $query->andWhere(['istat_regioni_id' => $this->id]);
            if (!is_null($soppresso)) {
                $query->andWhere(['soppressa' => $soppresso]);
            }
            
            $province = $query->orderBy('nome ASC')->asArray()->all();
            if ($this->id != null && count($province) > 0) {
                foreach ($province as $i => $provincia) {
                    $out[] = ['id' => $provincia['id'], 'name' => $provincia['nome']];
                }
            }
        }

        return ['output' => $out, 'selected' => $this->id_selected];
    }

    /**
     * 
     * @param type $soppresso
     * @return type
     */
    public function actionComuniByProvincia($soppresso = null)
    {
        $out = [];
        if (!empty($this->id)) {
            $query = IstatComuni::find()->andWhere(['istat_province_id' => $this->id]);
            if (!is_null($soppresso)) {
                $query->andWhere(['soppresso' => $soppresso]);
            }

            $comuni = $query->orderBy('nome ASC')->asArray()->all();
            if ($this->id != null && count($comuni) > 0) {
                foreach ($comuni as $i => $comune) {
                    $out[] = ['id' => $comune['id'], 'name' => $comune['nome']];
                }
            }
        }

        return ['output' => $out, 'selected' => $this->id_selected];
    }

    /**
     * 
     * @param type $search
     * @param type $id
     * @return type
     */
    public function actionComuni($search = null, $id = null)
    {
        $out = ['more' => false];
        if (!is_null($search)) {
            $query = new Query();
            $query->select('id, nome AS text')
                ->from(IstatComuni::tableName())
                ->where('nome LIKE "%' . $search . '%"');
            //->limit(20);
            $command = $query->createCommand();
            
            
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($this->id > 0) {
            $out['results'] = [
                'id' => $this->id,
                'text' => IstatComuni::findOne($id)->nome
            ];
        } else {
            $out['results'] = [
                'id' => 0,
                'text' => AmosComuni::t('amoscomuni', '#no_results')
            ];
        }
        
        return $out;
    }

    /**
     * 
     * @param type $search
     * @param type $id
     * @return type
     */
    public function actionProvince($search = null, $id = null)
    {
        $out = ['more' => false];
        if (!is_null($search)) {
            $query = new Query();
            $query->select('id, nome AS text')
                ->from(IstatProvince::tableName())
                ->where('nome LIKE "%' . $search . '%"')
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($this->id > 0) {
            $out['results'] = [
                'id' => $this->id,
                'text' => IstatProvince::findOne($id)->nome
            ];
        } else {
            $out['results'] = [
                'id' => 0,
                'text' => AmosComuni::t('amoscomuni', '#no_results')
            ];
        }
        
        return $out;
    }

    /**
     * 
     * @param type $sospeso
     * @return type
     */
    public function actionCapsByComune($sospeso = null)
    {
        $out = [];
        if (!empty($this->id)) {
            $query = IstatComuniCap::find()->andWhere(['comune_id' => $this->id]);
            if (!is_null($sospeso)) {
                $query->andWhere(['sospeso' => $sospeso]);
            }
            $caps = $query->orderBy('cap ASC')->asArray()->all();
            
            if ($this->id != null && count($caps) > 0) {
                foreach ($caps as $i => $cap) {
                    $out[] = ['id' => $cap['id'], 'name' => $cap['cap']];
                }
            }
        }

        return ['output' => $out, 'selected' => $this->id_selected];
    }
    
}
