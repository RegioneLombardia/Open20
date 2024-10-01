<?php

use open20\amos\core\migration\AmosMigration;
use open20\amos\sondaggi\models\Sondaggi;
use yii\rbac\Permission;

class m161209_102125_sondaggi_role_widget extends AmosMigration {

    /**
     * Use this instead of function up().
     */
    public function safeUp() {
        return $this->addAuthorizations();
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown() {
        return $this->removeAuthorizations();
    }

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations() {
        $this->authorizations = array_merge(
                $this->setWidgetsPermissions()
        );
    }

    /**
     * Plugin widgets permissions
     *
     * @return array
     */
    private function setWidgetsPermissions() {
        return [
                [
                'name' => open20\amos\sondaggi\widgets\icons\WidgetIconPubblicaSondaggi::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconPubblicaSondaggi',
                'ruleName' => null,
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ],
                [
                'name' => open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconCompilaSondaggi',
                'ruleName' => null,
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ],
                [
                'name' => open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconSondaggi',
                'ruleName' => null,
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ],
        ];
    }

}
