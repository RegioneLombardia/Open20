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
class m220616_170327_add_tree_tags_goals extends Migration
{
    const ROLE_ROOT = 1;


    public $tagsOnu = [
        'Goal 1: Porre fine ad ogni forma di povertà nel mondo',
        'Goal 2: Porre fine alla fame, raggiungere la sicurezza alimentare, migliorare la nutrizione e promuovere un’agricoltura sostenibile',
        'Goal 3: Assicurare la salute e il benessere per tutti e per tutte le età',
        'Goal 4: Fornire un’educazione di qualità, equa ed inclusiva, e opportunità di apprendimento per tutti',
        'Goal 5: Raggiungere l’uguaglianza di genere ed emancipare tutte le donne e le ragazze',
        'Goal 6: Garantire a tutti la disponibilità e la gestione sostenibile dell’acqua e delle strutture igienico sanitarie',
        'Goal 7: Assicurare a tutti l’accesso a sistemi di energia economici, affidabili, sostenibili e moderni',
        'Goal 8: Incentivare una crescita economica, duratura, inclusiva e sostenibile, un’occupazione piena e produttiva ed un lavoro dignitoso per tutti',
        'Goal 9: Costruire una infrastruttura resiliente e promuovere l’innovazione ed una industrializzazione equa, responsabile e sostenibile',
        'Goal 10: Ridurre le disuguaglianze all\'interno e fra le Nazioni',
        'Goal 11: Rendere le città e gli insediamenti umani inclusivi, sicuri, duraturi e sostenibili',
        'Goal 12: Garantire modelli sostenibili di produzione e di consumo',
        'Goal 13: Adottare misure urgenti per combattere i cambiamenti climatici e le sue conseguenze',
        'Goal 14: Conservare e utilizzare in modo durevole gli oceani, i mari e le risorse marine per uno sviluppo sostenibile',
        'Goal 15: Proteggere, ripristinare e favorire un uso sostenibile dell’ecosistema terrestre, gestire sostenibilmente le foreste, contrastare la desertificazione, arrestare e far retrocedere il degrado del terreno, e fermare la perdita di diversità biologica',
        'Goal 16: Promuovere società pacifiche e più inclusive per uno sviluppo sostenibile; offrire l\'accesso alla giustizia per tutti e creare organismi efficaci, responsabili e inclusivi a tutti i livelli',
        'Goal 17: Rafforzare i mezzi di attuazione e rinnovare il partenariato mondiale per lo sviluppo sostenibile',
    ];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $rootGoals = Tag::find()->andWhere(['codice' => 'root_goals'])->one();
        if(empty($rootGoals)) {
            // albero tag ONU
            $roleRootTag1 = new Tag();
            $roleRootTag1->nome = "Goal dell’Agenda ONU";
            $roleRootTag1->limit_selected_tag = 3;
            $roleRootTag1->codice = 'root_goals';
            $roleRootTag1->makeRoot();
            $roleRootTag1->save(false);
            $i = 1;
            foreach ($this->tagsOnu as $roleName) {
                $roleTag = $this->createTag($roleRootTag1, $roleName, $i, 'goal');
                if (is_null($roleTag)) {
                    MigrationCommon::printConsoleMessage('Errore nella creazione del tag del ruolo ' . $roleName);
                    return false;
                }
                $i++;
            }
        }else {
            echo "Albero già creato.\n";
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
