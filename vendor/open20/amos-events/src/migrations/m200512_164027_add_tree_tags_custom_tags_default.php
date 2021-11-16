<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    cruscotto-lavoro\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\tag\models\Tag;
use yii\db\Migration;

/**
 * Class m180904_084129_add_cl_roles_tags
 */
class m200512_164027_add_tree_tags_custom_tags_default extends Migration
{
    const ROLE_ROOT = 1;



    public $tagsDefault = [
        'Natale',
        'Festa della Lombardia',
        'Premio',
        'Mostre – Esposizioni',
        'Aperture Palazzo Lombardia',
        'Aperture Palazzo Pirelli',
        'Festa Nonni',
        'Festa della Donna',
        'Stop Aids',
        'Stop Violenza Donne',
        'Screening',
        'Flash mob',
        'Sport',
        'Cultura',
        'Enogastronomia'
    ];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $rootTag = Tag::find()->andWhere(['codice' => \open20\amos\events\models\Event::ROOT_TAG_CUSTOM_EVENTS])->one();
        Tag::deleteAll(['like', 'codice' , 'custom_tags_default']);

        $i = 1;
        foreach ($this->tagsDefault as $roleName) {
            $roleTag = $this->createTag($rootTag, $roleName, $i, 'custom_tags_default');
            if (is_null($roleTag)) {
                MigrationCommon::printConsoleMessage('Errore nella creazione del tag del ruolo ' . $roleName);
                return false;
            }
            $i++;
        }
        $this->addColumn('event','use_default_custom_tags' ,$this->integer(1)->defaultValue(0)->after('community_id'));

        return true;
    }

    /**
     * @param Tag $parent
     * @param string $nome
     * @return Tag|null
     */
    private function createTag($parent, $nome, $i=0, $prefix = '')
    {
        $codice = '';
        if(!empty($prefix)){
            $codice = $prefix.'_'.$i;
        }

        $node = new Tag();
        $node->nome = $nome;
        $node->codice = $codice;
        $node->descrizione = '';
        $node->icon = '';
        $node->icon_type = 1;
        $node->active = 1;
        $node->activeOrig = $node->active;
        $node->selected = 0;
        $node->disabled = 0;
        $node->readonly = 0;
        $node->visible = 1;
        $node->collapsed = 0;
        $node->movable_u = 1;
        $node->movable_d = 1;
        $node->movable_l = 1;
        $node->movable_r = 1;
        $node->removable = 1;
        $node->removable_all = 0;
        $node->appendTo($parent);

        if (!$node->save()) {
            return null;
        }

        return $node;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        Tag::deleteAll(['like', 'codice' , 'custom_tags_default']);
        $this->dropColumn('event','use_default_custom_tags');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');


    }
}
