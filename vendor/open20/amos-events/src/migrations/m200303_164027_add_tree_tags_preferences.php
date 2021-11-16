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
class m200303_164027_add_tree_tags_preferences extends Migration
{
    const ROLE_ROOT = 1;



    public $tagsPreferences = [
        'Mobilità e trasporti',
        'Allerte e info di pubblica utilità',
        'Istruzione formazione e lavoro',
        'Sanità e Welfare',
        'Bandi, contributi e concorsi',
        'Cultura, tempo libero e turismo',
        'Info blocco del traffico',
        'Tributi',
        'Veterinaria e animali',
        'Innovazione',
        'Opportunità per giovani',
        'Diritti e tutele',
        'Informazioni istituzionali',
        'Sociale',
        'Territorio',
        'Agricoltura',
        'Sport',
        'Sviluppo sostenibile',
        'Innovazione',

    ];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        // albero tag Preference centre
        $roleRootTag1 = new Tag();
        $roleRootTag1->nome = "Preference center";
//        $roleRootTag1->limit_selected_tag = 3;
        $roleRootTag1->codice = \open20\amos\events\models\Event::ROOT_TAG_PREFERENCE_CENTER;
        $roleRootTag1->makeRoot();
        $roleRootTag1->save(false);
        $i = 1;
        foreach ($this->tagsPreferences as $roleName) {
            $roleTag = $this->createTag($roleRootTag1, $roleName, $i, 'preference');
            if (is_null($roleTag)) {
                MigrationCommon::printConsoleMessage('Errore nella creazione del tag del ruolo ' . $roleName);
                return false;
            }
            $i++;
        }

        $roleRootTag1 = new Tag();
        $roleRootTag1->nome = "Events tag custom";
//        $roleRootTag1->limit_selected_tag = 3;
        $roleRootTag1->codice = \open20\amos\events\models\Event::ROOT_TAG_CUSTOM_EVENTS;
        $roleRootTag1->makeRoot();
        $roleRootTag1->save(false);


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
