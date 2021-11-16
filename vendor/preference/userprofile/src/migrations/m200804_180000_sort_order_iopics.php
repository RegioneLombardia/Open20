<?php

use yii\db\Migration;

/**
 * Class m200804_180000_sort_order_iopics
 */
class m200804_180000_sort_order_iopics extends Migration
{

    public function safeUp()
    {

        $this->execute('SET SQL_SAFE_UPDATES = 0;');

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 10
        WHERE `codice` = 'pctopic_cittadino_8';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 20
        WHERE `codice` = 'pctopic_cittadino_2';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 30
        WHERE `codice` = 'pctopic_cittadino_7';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 40
        WHERE `codice` = 'pctopic_cittadino_3';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 50
        WHERE `codice` = 'pctopic_cittadino_5';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 60
        WHERE `codice` = 'pctopic_cittadino_1';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 70
        WHERE `codice` = 'pctopic_cittadino_6';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 80
        WHERE `codice` = 'pctopic_cittadino_4';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 90
        WHERE `codice` = 'pctopic_cittadino_11';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 100
        WHERE `codice` = 'pctopic_cittadino_9';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 110
        WHERE `codice` = 'pctopic_cittadino_12';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 120
        WHERE `codice` = 'pctopic_cittadino_10';
        ");


        /*************** IMPRESA ***************/

        
        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 10
        WHERE `codice` = 'pctopic_impresa_8';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 20
        WHERE `codice` = 'pctopic_impresa_2';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 30
        WHERE `codice` = 'pctopic_impresa_7';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 40
        WHERE `codice` = 'pctopic_impresa_3';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 50
        WHERE `codice` = 'pctopic_impresa_5';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 60
        WHERE `codice` = 'pctopic_impresa_1';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 70
        WHERE `codice` = 'pctopic_impresa_6';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 80
        WHERE `codice` = 'pctopic_impresa_4';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 90
        WHERE `codice` = 'pctopic_impresa_14';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 100
        WHERE `codice` = 'pctopic_impresa_13';
        ");


        /*************** ENTI E OPERATORI ***************/

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 10
        WHERE `codice` = 'pctopic_enteeoperatore_8';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 20
        WHERE `codice` = 'pctopic_enteeoperatore_2';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 30
        WHERE `codice` = 'pctopic_enteeoperatore_7';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 40
        WHERE `codice` = 'pctopic_enteeoperatore_3';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 50
        WHERE `codice` = 'pctopic_enteeoperatore_5';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 60
        WHERE `codice` = 'pctopic_enteeoperatore_1';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 70
        WHERE `codice` = 'pctopic_enteeoperatore_6';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 80
        WHERE `codice` = 'pctopic_enteeoperatore_4';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 90
        WHERE `codice` = 'pctopic_enteeoperatore_17';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 100
        WHERE `codice` = 'pctopic_enteeoperatore_15';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 110
        WHERE `codice` = 'pctopic_enteeoperatore_12';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 120
        WHERE `codice` = 'pctopic_enteeoperatore_10';
        ");

        $this->execute("
        UPDATE `tag`
        SET `pc_topic_order` = 130
        WHERE `codice` = 'pctopic_enteeoperatore_16';
        ");


        $this->execute('SET SQL_SAFE_UPDATES = 1;');

        return true;
    }

    public function safeDown()
    {

        return true;
    }
}
