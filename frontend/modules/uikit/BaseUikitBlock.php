<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package    Open20Package
 */

namespace app\modules\uikit;

use trk\uikit\Uikit;
use Yii;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileClasses;
use open20\amos\admin\models\UserProfileClassesUserMm;
use yii\helpers\ArrayHelper;

/**
 * Class BaseUikitBlock
 * @package app\modules\uikit
 */
abstract class BaseUikitBlock extends \trk\uikit\BaseUikitBlock {

    const CONFIGS_EXT = ".json";
    const COMPONENTS_PATH = __DIR__ . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR;

    private $_vars = [];
    private $_cfgs = [];

    /**
     * @param string $component
     * @return array|mixed
     */
    protected function getComponentConfigs($component = "") {
        $component = $component ?: $this->component;
        $configs = $this->getJsonContent(self::COMPONENTS_PATH . $component . self::CONFIGS_EXT);
        $configs['vars'] = $this->setConfigFields(Uikit::element("vars", $configs, []));
        $configs["cfgs"] = $this->setConfigFields(Uikit::element("cfgs", $configs, []));
        return $configs;
    }

    public function getViewPath() {
        return __DIR__ . '/views';
    }

    public function getConfigVarsExport() {
        $config = $this->config();

        if (isset($config['vars'])) {
            foreach ($config['vars'] as $item) {
                $iteration = count($this->_vars) + 500;
                $this->_vars[$iteration] = (new BlockVar($item))->toArray();
            }
        }

        ksort($this->_vars);
        return array_values($this->_vars);
    }

    public function getConfigCfgsExport() {
        $config = $this->config();

        if (isset($config['cfgs'])) {
            foreach ($config['cfgs'] as $item) {
                $iteration = count($this->_cfgs) + 500;
                $this->_cfgs[$iteration] = (new BlockCfg($item))->toArray();
            }
        }
        ksort($this->_cfgs);
        return array_values($this->_cfgs);
    }

    public function visivility($n_class) {
        $canSeeBlock = true;
        
        if (!empty($n_class)) {
            $user_id = Yii::$app->user->id;
            $user_profile = UserProfile::findOne($user_id);
            
            $profiles = [];

            //if ((defined(UserProfileClasses::className().'::UTENTI_FINALI') && $user_profile->type == UserProfileClasses::UTENTI_FINALI) || is_null($user_profile->type)) {
                foreach ($n_class as $class) {
                    $profiles[] = $class['class'];
                }

                $check = UserProfileClassesUserMm::find()->andWhere(['user_id' => $user_id])->andWhere(['user_profile_classes_id' => $profiles])->count();
                if ($check == 0) {
                    $canSeeBlock = false;
                }
            //}
        }

        return $canSeeBlock;
    }

    public static function getClasses() {
        $data = [];
        
        $query = UserProfileClasses::find()->where(['enabled' => 1]);
        
        /*$schema = UserProfileClasses::getTableSchema();
        if(isset($schema->columns['type']) && defined(UserProfileClasses::className().'::UTENTI_FINALI'))
            $query->andWhere(['type' => UserProfileClasses::UTENTI_FINALI]);
        */
        $query_profiles = $query->all();
       
        $profiles = ArrayHelper::map($query_profiles, 'id', 'name');       
        foreach ($profiles as $k => $f) {
            $data[] = ['value' => $k, 'label' => $f];
        }
       
        return $data;
    }

}
