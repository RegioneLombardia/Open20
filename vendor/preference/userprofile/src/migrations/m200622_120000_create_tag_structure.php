`<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\tag\models\Tag;
use yii\db\Migration;

/**
 * Class m200622_120000_create_tag_structure
 */
class m200622_120000_create_tag_structure extends Migration
{
    private $topicStructure = [
        'Cittadino' => [
            1 => 'Innovazione',
            2 => 'Ambiente e Sviluppo sostenibile',
            3 => 'Cultura, tempo libero e turismo',
            4 => 'Mobilità, trasporti e blocchi del traffico',
            5 => 'Informazioni istituzionali',
            6 => 'Istruzione, formazione e lavoro',
            7 => 'Bandi, contributi e concorsi',
            8 => 'Allerta e info di pubblica utilità',
            9 => 'Salute e prevenzione',
            10 => 'Sport',
            11 => 'Agricoltura e alimentazione',
            12 => 'Sociale',
        ],
        'Impresa' => [
            1 => 'Innovazione',
            2 => 'Ambiente e Sviluppo sostenibile',
            3 => 'Cultura, tempo libero e turismo',
            4 => 'Mobilità, trasporti e blocchi del traffico',
            5 => 'Informazioni istituzionali',
            6 => 'Istruzione, formazione e lavoro',
            7 => 'Bandi, contributi e concorsi',
            8 => 'Allerta e info di pubblica utilità',
            13 => 'Commercio',
            14 => 'Agricoltura',
        ],
        'Ente e Operatore' => [
            1 => 'Innovazione',
            2 => 'Ambiente e Sviluppo sostenibile',
            3 => 'Cultura, tempo libero e turismo',
            4 => 'Mobilità, trasporti e blocchi del traffico',
            5 => 'Informazioni istituzionali',
            6 => 'Istruzione, formazione e lavoro',
            7 => 'Bandi, contributi e concorsi',
            8 => 'Allerta e info di pubblica utilità',
            10 => 'Sport',
            12 => 'Sociale',
            15 => 'Sicurezza',
            16 => 'Territorio',
            17 => 'Sanità',
        ],
    ];
    /**
     * @return boolean
     */
    public function safeUp()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {

            foreach ($this->topicStructure as $target => $topics) {
                $codeRoot = str_replace(' ', '', strtolower($target));
                $tagRoot = new Tag([
                    'nome' => $target, 
                    'codice' => 'pctarget_' . $codeRoot,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => 1,
                    'removable' => false,
                    ]);
                $tagRoot->makeRoot();
                $tagRoot->save(false);

                foreach ($topics as $key => $topic) {
                    $tag = new Tag([
                        'nome' => $topic, 
                        'codice' => 'pctopic_' . $codeRoot . '_' . $key,
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => 1,
                        'removable' => false,
                        ]);
                    
                    $tag->appendTo($tagRoot);
                    $tag->save(false);
                }

            }

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return true;
    }

    /**
     * @return boolean
     */
    public function safeDown()
    {
        // $this->delete('tag', ['>', 'id', '3724']);
        return true;
    }

}
