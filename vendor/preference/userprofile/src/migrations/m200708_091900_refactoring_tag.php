<?php

use preference\userprofile\models\Tag;
use yii\db\Migration;

/**
 * 
 * Rifacciamo tutti i tag preference centre... 
 * 
 * Class m200708_091900_refactoring_tag 
 */
class m200708_091900_refactoring_tag extends Migration
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

    public function safeUp()
    {
        
        $this->execute('SET SQL_SAFE_UPDATES = 0;');

        $this->execute("
            DELETE FROM tag WHERE (codice like 'pctarget%') or (codice like 'pctopic%');
        ");

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

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=10
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '1');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=20
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '2');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=30
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '3');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=40
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '4');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=50
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '5');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=60
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '6');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=70
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '7');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=80
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '8');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=90
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '9');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=100
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '10');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=110
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '11');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=120
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '12');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=130
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '13');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=140
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '14');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=150
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '15');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=160
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '16');
       ");

        $this->execute("
        UPDATE `tag`
            SET `pc_topic_order`=170
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '17');
       ");



       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Progetti, eventi e incontri promossi da Regione Lombardia per supportare l\'innovazione.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '1');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Iniziative, progetti e incentivi  attivati da Regione Lombardia per tutelare la qualità dell\'aria, dell\'ambiente, la biodiversità, la sostenibilità'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '2');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Idee e proposte per il tempo libero: dalla cultura (mostre, musei, concerti) al territorio (agriturismi, rifugi, itinerari turistici).'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '3');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Aggiornamenti su cambi di viabilità, tariffe e abbonamenti agevolati per i mezzi pubblici, incentivi per la mobilità sostenibile.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '4');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Comunicazioni, provvedimenti, ordinanze e avvisi ufficiali di Regione Lombardia.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '5');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Offerta formativa in Lombardia, agevolazioni e opportunità di inserimento nel mondo de lavoro per giovani e adulti.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '6');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Notizie sui bandi promossi da Regione Lombardia e finanziati con fondi regionali, nazionali ed europei. Avvisi sui concorsi e borse di studio.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '7');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Informazioni e segnalazioni di pubblica utilità, sia in prevenzione (es. allerta meteo) che in emergenza (es. sanitaria, idrogeologica).'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '8');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '9');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Grandi eventi, servizi, impianti, incentivi per la pratica dello sport in Lombardia.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '10');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Eventi e iniziative di promozione dei prodotti agroalimentari lombardi e di educazione alimentare.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '11');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Iniziative attivate da Regione Lombardia per sostenere le persone in difficoltà e le famiglie in condizioni di vulnerabilità economica e sociale.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '12');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Informazioni, aggiornamenti normativi, progetti e finanziamenti per gli operatori commerciali.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '13');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Informazioni, servizi, avvisi, bandi e agevolazioni per gli operatori del settore agricolo e agroalimentare.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '14');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Aggiornamenti sulle iniziative promosse a favore di enti e operatori per polizia locale, sicurezza stradale e sicurezza urbana.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '15');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Aggiornamenti in materia di Piani territoriali, paesaggio, difesa del suolo, assetti idrogeologici, valutazioni ambientali.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '16');
      ");

       $this->execute("
       UPDATE `tag`
           SET `descrizione`='Notizie, bandi e informazioni per gli enti e gli operatori del settore sanitario.'
           WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '17');
      ");

      // values update
      $this->update('tag', ['pc_target_order' => 10], ['codice' => 'pctarget_cittadino']);
      $this->update('tag', ['pc_target_order' => 20], ['codice' => 'pctarget_impresa']);
      $this->update('tag', ['pc_target_order' => 30], ['codice' => 'pctarget_enteeoperatore']);

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=10
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '1');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=20
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '2');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=30
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '3');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=40
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '4');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=50
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '5');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=60
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '6');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=70
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '7');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=80
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '8');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=90
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '9');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=100
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '10');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=110
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '11');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=120
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '12');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=130
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '13');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=140
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '14');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=150
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '15');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=160
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '16');
     ");

      $this->execute("
      UPDATE `tag`
          SET `pc_topic_order`=170
          WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '17');
     ");


       $this->execute('SET SQL_SAFE_UPDATES = 1;');

        return true;
    }

    public function safeDown()
    {
        // DO NOTHING
      
        return true;
    }

}
