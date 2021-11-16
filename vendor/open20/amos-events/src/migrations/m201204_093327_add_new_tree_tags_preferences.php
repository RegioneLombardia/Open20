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
class m201204_093327_add_new_tree_tags_preferences extends Migration
{
    const ROLE_ROOT = 1;



    public $tagsPreferences = [
        'Agricoltura',
        'Ambiente e territorio',
        'Cultura e tempo libero',
        'Famiglia e società',
        'Istituzionale',
        'Istruzione e lavoro',
        'Salute e welfare',
        'Sport',
        'Trasporto e mobilità',
        'Turismo',
    ];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        // albero tag Preference centre
        $roleRootTag1 = Tag::find()->andWhere(['codice' => \open20\amos\events\models\Event::ROOT_TAG_PREFERENCE_CENTER])->one();
        Tag::deleteAll(['root' => $roleRootTag1->id, 'lvl' => 1]);

        $i = 1;
        foreach ($this->tagsPreferences as $roleName) {
            $roleTag = $this->createTag($roleRootTag1, $roleName, $i, 'preference');
            if (is_null($roleTag)) {
                MigrationCommon::printConsoleMessage('Errore nella creazione del tag del ruolo ' . $roleName);
                return false;
            }
            $i++;
        }


        $events = \open20\amos\events\models\Event::find()->all();
        $taguno = Tag::find()->andWhere(['codice' => 'preference_1'])->one();
        foreach ($events as $event){
           $tagMm = new \open20\amos\tag\models\EntitysTagsMm();
           $tagMm->tag_id = $taguno->id;
           $tagMm->root_id = $roleRootTag1->id;
           $tagMm->classname = 'open20\amos\events\models\Event';
           $tagMm->record_id = $event->id;
           $tagMm->save(false);
        }
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
        echo "m180904_084129_add_cl_roles_tags cannot be reverted.\n";

        return true;
    }
}
