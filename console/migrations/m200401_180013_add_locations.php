<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200401_180013_add_locations extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->truncateTable('event_location');
        $this->truncateTable('event_location_entrances');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        $this->insert('event_location', ['id' => 1,'name' => "Palazzo lombardia, Milano", 'hidden' => false, 	'event_place_id' => 'ChIJnxcNEdLGhkcR1Wd9ntrUgco',	'created_at' => '2020-03-02 18:36:47', 'updated_at' => '2020-03-03 09:24:59', 'deleted_at' => NULL, 'created_by' =>1, 'updated_by' => 1, 'deleted_by' => null]);
        $this->insert('event_location', ['id' => 5,'name' => "Palazzo Pirelli, Milano", 'hidden' => false, 	'event_place_id' => 'ChIJiyEi7s_GhkcRWWyTagHUZAw',	'created_at' => '2020-03-02 18:36:47', 'updated_at' => '2020-03-03 09:24:59', 'deleted_at' => NULL, 'created_by' =>1, 'updated_by' => 1, 'deleted_by' => null]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Auditorium Testori - Piazza Città di Lombardia", ]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Belvedere 39° piano - Piazza Città di Lombardia - ingresso N1", ]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Sala Stampa – 11° piano - Piazza Città di Lombardia - ingresso N1", ]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Sala Falcone – 11° piano	Piazza Città di Lombardia	ingresso N1", ]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Sala opportunità – 13° piano	- Piazza Città di Lombardia	 - ingresso N1", ]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Sala M. Biagi - Via M. Gioia, 37 - ingresso N4", ]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Sala Solesin - Via M. Gioia, 37 - ingresso N4", ]);
        $this->insert('event_location_entrances',['event_location_id' => 1, 'name' => "Spazio Espositivo - Via Galvani, 27	- ingresso N2", ]);

        $this->insert('event_location_entrances',['event_location_id' => 5, 'name' => "Belvedere Enzo Jannacci - Via Fabio Filzi, 22", ]);
        $this->insert('event_location_entrances',['event_location_id' => 5, 'name' => "Sala Pirelli - Via Fabio Filzi, 22", ]);
        $this->insert('event_location_entrances',['event_location_id' => 5, 'name' => "Sala Gonfalone - Via Fabio Filzi, 22", ]);
        $this->insert('event_location_entrances',['event_location_id' => 5, 'name' => "Spazio Eventi	 - Via Fabio Filzi, 22", ]);
        $this->insert('event_location_entrances',['event_location_id' => 5, 'name' => "Sala Gio Ponti - Via Fabio Filzi, 22", ]);
        $this->insert('event_location_entrances',['event_location_id' => 5, 'name' => "Piano della memoria - Via Fabio Filzi, 22", ]);
        $this->insert('event_location_entrances',['event_location_id' => 5, 'name' => "Auditorium Gaber - Piazza Duca D’Aosta, 3", ]);

    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->delete('event_location', ['id' => 1,'name' => "Palazzo lombardia, Milano", 'hidden' => false, 	'event_place_id' => 'ChIJnxcNEdLGhkcR1Wd9ntrUgco',	'created_at' => '2020-03-02 18:36:47', 'updated_at' => '2020-03-03 09:24:59', 'deleted_at' => NULL, 'created_by' =>1, 'updated_by' => 1, 'deleted_by' => null]);
        $this->delete('event_location', ['id' => 5,'name' => "Palazzo Pirelli, Milano", 'hidden' => false, 	'event_place_id' => 'ChIJiyEi7s_GhkcRWWyTagHUZAw',	'created_at' => '2020-03-02 18:36:47', 'updated_at' => '2020-03-03 09:24:59', 'deleted_at' => NULL, 'created_by' =>1, 'updated_by' => 1, 'deleted_by' => null]);

        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Auditorium Testori - Piazza Città di Lombardia", ]);
        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Belvedere 39° piano - Piazza Città di Lombardia - ingresso N1", ]);
        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Sala Stampa – 11° piano - Piazza Città di Lombardia - ingresso N1", ]);
        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Sala Falcone – 11° piano	Piazza Città di Lombardia	ingresso N1", ]);
        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Sala opportunità – 13° piano	- Piazza Città di Lombardia	 - ingresso N1", ]);
        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Sala M. Biagi - Via M. Gioia, 37 - ingresso N4", ]);
        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Sala Solesin - Via M. Gioia, 37 - ingresso N4", ]);
        $this->delete('event_location_entrances',['event_location_id' => 1, 'name' => "Spazio Espositivo - Via Galvani, 27	- ingresso N2", ]);

        $this->delete('event_location_entrances',['event_location_id' => 5, 'name' => "Belvedere Enzo Jannacci - Via Fabio Filzi, 22", ]);
        $this->delete('event_location_entrances',['event_location_id' => 5, 'name' => "Sala Pirelli - Via Fabio Filzi, 22", ]);
        $this->delete('event_location_entrances',['event_location_id' => 5, 'name' => "Sala Gonfalone - Via Fabio Filzi, 22", ]);
        $this->delete('event_location_entrances',['event_location_id' => 5, 'name' => "Spazio Eventi	 - Via Fabio Filzi, 22", ]);
        $this->delete('event_location_entrances',['event_location_id' => 5, 'name' => "Sala Gio Ponti - Via Fabio Filzi, 22", ]);
        $this->delete('event_location_entrances',['event_location_id' => 5, 'name' => "Piano della memoria - Via Fabio Filzi, 22", ]);
        $this->delete('event_location_entrances',['event_location_id' => 5, 'name' => "Auditorium Gaber - Piazza Duca D’Aosta, 3", ]);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
