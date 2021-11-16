<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

class m150728_000005_insert_italia_province extends \yii\db\Migration
{
/*
    const TABLE = '{{%italia_province}}';
 
    public function safeUp()
    {
        $this->insert(self::TABLE, ['id' => 2, 'nome' => "Macerata", 'regione_id' => 1, 'sigla' => "MC"]);
        $this->insert(self::TABLE, ['id' => 3, 'nome' => "Pesaro Urbino", 'regione_id' => 1, 'sigla' => "PU"]);
        $this->insert(self::TABLE, ['id' => 4, 'nome' => "Ascoli Piceno", 'regione_id' => 1, 'sigla' => "AP"]);
        $this->insert(self::TABLE, ['id' => 5, 'nome' => "Alessandria", 'regione_id' => 13, 'sigla' => "AL"]);
        $this->insert(self::TABLE, ['id' => 6, 'nome' => "Asti", 'regione_id' => 13, 'sigla' => "AT"]);
        $this->insert(self::TABLE, ['id' => 7, 'nome' => "Biella", 'regione_id' => 13, 'sigla' => "BI"]);
        $this->insert(self::TABLE, ['id' => 8, 'nome' => "Cuneo", 'regione_id' => 13, 'sigla' => "CN"]);
        $this->insert(self::TABLE, ['id' => 9, 'nome' => "Novara", 'regione_id' => 13, 'sigla' => "No"]);
        $this->insert(self::TABLE, ['id' => 10, 'nome' => "Vercelli", 'regione_id' => 13, 'sigla' => "VC"]);
        $this->insert(self::TABLE, ['id' => 11, 'nome' => "Torino", 'regione_id' => 13, 'sigla' => "TO"]);
        $this->insert(self::TABLE, ['id' => 12, 'nome' => "Agrigento", 'regione_id' => 11, 'sigla' => "AG"]);
        $this->insert(self::TABLE, ['id' => 13, 'nome' => "Caltanissetta", 'regione_id' => 11, 'sigla' => "CL"]);
        $this->insert(self::TABLE, ['id' => 14, 'nome' => "Catania", 'regione_id' => 11, 'sigla' => "CT"]);
        $this->insert(self::TABLE, ['id' => 15, 'nome' => "Enna", 'regione_id' => 11, 'sigla' => "EN"]);
        $this->insert(self::TABLE, ['id' => 16, 'nome' => "Messina", 'regione_id' => 11, 'sigla' => "ME"]);
        $this->insert(self::TABLE, ['id' => 17, 'nome' => "Palermo", 'regione_id' => 11, 'sigla' => "PA"]);
        $this->insert(self::TABLE, ['id' => 18, 'nome' => "Ragusa", 'regione_id' => 11, 'sigla' => "RG"]);
        $this->insert(self::TABLE, ['id' => 19, 'nome' => "Siracusa", 'regione_id' => 11, 'sigla' => "SR"]);
        $this->insert(self::TABLE, ['id' => 20, 'nome' => "Trapani", 'regione_id' => 11, 'sigla' => "TP"]);
        $this->insert(self::TABLE, ['id' => 21, 'nome' => "Catanzaro", 'regione_id' => 7, 'sigla' => "CZ"]);
        $this->insert(self::TABLE, ['id' => 22, 'nome' => "Cosenza", 'regione_id' => 7, 'sigla' => "CS"]);
        $this->insert(self::TABLE, ['id' => 23, 'nome' => "Crotone", 'regione_id' => 7, 'sigla' => "KR"]);
        $this->insert(self::TABLE, ['id' => 24, 'nome' => "Reggio Calabria", 'regione_id' => 7, 'sigla' => "RC"]);
        $this->insert(self::TABLE, ['id' => 25, 'nome' => "Vibo Valentia", 'regione_id' => 7, 'sigla' => "VV"]);
        $this->insert(self::TABLE, ['id' => 27, 'nome' => "Matera", 'regione_id' => 3, 'sigla' => "MT"]);
        $this->insert(self::TABLE, ['id' => 28, 'nome' => "Potenza", 'regione_id' => 3, 'sigla' => "PZ"]);
        $this->insert(self::TABLE, ['id' => 29, 'nome' => "Bari", 'regione_id' => 6, 'sigla' => "BA"]);
        $this->insert(self::TABLE, ['id' => 30, 'nome' => "Brindisi", 'regione_id' => 6, 'sigla' => "BR"]);
        $this->insert(self::TABLE, ['id' => 31, 'nome' => "Foggia", 'regione_id' => 6, 'sigla' => "FG"]);
        $this->insert(self::TABLE, ['id' => 32, 'nome' => "Lecce", 'regione_id' => 6, 'sigla' => "LE"]);
        $this->insert(self::TABLE, ['id' => 33, 'nome' => "Taranto", 'regione_id' => 6, 'sigla' => "TA"]);
        $this->insert(self::TABLE, ['id' => 34, 'nome' => "Avellino", 'regione_id' => 8, 'sigla' => "AV"]);
        $this->insert(self::TABLE, ['id' => 35, 'nome' => "Benevento", 'regione_id' => 8, 'sigla' => "BN"]);
        $this->insert(self::TABLE, ['id' => 36, 'nome' => "Caserta", 'regione_id' => 8, 'sigla' => "CE"]);
        $this->insert(self::TABLE, ['id' => 37, 'nome' => "Napoli", 'regione_id' => 8, 'sigla' => "NA"]);
        $this->insert(self::TABLE, ['id' => 38, 'nome' => "Salerno", 'regione_id' => 8, 'sigla' => "SA"]);
        $this->insert(self::TABLE, ['id' => 39, 'nome' => "Frosinone", 'regione_id' => 9, 'sigla' => "FR"]);
        $this->insert(self::TABLE, ['id' => 40, 'nome' => "Latina", 'regione_id' => 9, 'sigla' => "LT"]);
        $this->insert(self::TABLE, ['id' => 41, 'nome' => "Rieti", 'regione_id' => 9, 'sigla' => "RI"]);
        $this->insert(self::TABLE, ['id' => 42, 'nome' => "Roma", 'regione_id' => 9, 'sigla' => "RM"]);
        $this->insert(self::TABLE, ['id' => 43, 'nome' => "Viterbo", 'regione_id' => 9, 'sigla' => "VT"]);
        $this->insert(self::TABLE, ['id' => 44, 'nome' => "Chieti", 'regione_id' => 2, 'sigla' => "CH"]);
        $this->insert(self::TABLE, ['id' => 45, 'nome' => "L'Aquila", 'regione_id' => 2, 'sigla' => "AQ"]);
        $this->insert(self::TABLE, ['id' => 46, 'nome' => "Pescara", 'regione_id' => 2, 'sigla' => "PE"]);
        $this->insert(self::TABLE, ['id' => 47, 'nome' => "Teramo", 'regione_id' => 2, 'sigla' => "TE"]);
        $this->insert(self::TABLE, ['id' => 48, 'nome' => "Arezzo", 'regione_id' => 12, 'sigla' => "AR"]);
        $this->insert(self::TABLE, ['id' => 49, 'nome' => "Firenze", 'regione_id' => 12, 'sigla' => "FI"]);
        $this->insert(self::TABLE, ['id' => 50, 'nome' => "Grosseto", 'regione_id' => 12, 'sigla' => "GR"]);
        $this->insert(self::TABLE, ['id' => 51, 'nome' => "Livorno", 'regione_id' => 12, 'sigla' => "LI"]);
        $this->insert(self::TABLE, ['id' => 52, 'nome' => "Lucca", 'regione_id' => 12, 'sigla' => "LU"]);
        $this->insert(self::TABLE, ['id' => 53, 'nome' => "Massa Carrara", 'regione_id' => 12, 'sigla' => "MS"]);
        $this->insert(self::TABLE, ['id' => 54, 'nome' => "Pisa", 'regione_id' => 12, 'sigla' => "PI"]);
        $this->insert(self::TABLE, ['id' => 55, 'nome' => "Pistoia", 'regione_id' => 12, 'sigla' => "PT"]);
        $this->insert(self::TABLE, ['id' => 56, 'nome' => "Siena", 'regione_id' => 12, 'sigla' => "SI"]);
        $this->insert(self::TABLE, ['id' => 57, 'nome' => "Bologna", 'regione_id' => 14, 'sigla' => "BO"]);
        $this->insert(self::TABLE, ['id' => 58, 'nome' => "Ferrara", 'regione_id' => 14, 'sigla' => "FE"]);
        $this->insert(self::TABLE, ['id' => 59, 'nome' => "Forlì Cesena", 'regione_id' => 14, 'sigla' => "FC"]);
        $this->insert(self::TABLE, ['id' => 60, 'nome' => "Modena", 'regione_id' => 14, 'sigla' => "MO"]);
        $this->insert(self::TABLE, ['id' => 61, 'nome' => "Parma", 'regione_id' => 14, 'sigla' => "PR"]);
        $this->insert(self::TABLE, ['id' => 62, 'nome' => "Piacenza", 'regione_id' => 14, 'sigla' => "PC"]);
        $this->insert(self::TABLE, ['id' => 63, 'nome' => "Ravenna", 'regione_id' => 14, 'sigla' => "RA"]);
        $this->insert(self::TABLE, ['id' => 64, 'nome' => "Reggio Emilia", 'regione_id' => 14, 'sigla' => "RE"]);
        $this->insert(self::TABLE, ['id' => 65, 'nome' => "Rimini", 'regione_id' => 14, 'sigla' => "RN"]);
        $this->insert(self::TABLE, ['id' => 66, 'nome' => "Belluno", 'regione_id' => 17, 'sigla' => "BL"]);
        $this->insert(self::TABLE, ['id' => 67, 'nome' => "Padova", 'regione_id' => 17, 'sigla' => "PD"]);
        $this->insert(self::TABLE, ['id' => 68, 'nome' => "Rovigo", 'regione_id' => 17, 'sigla' => "RO"]);
        $this->insert(self::TABLE, ['id' => 69, 'nome' => "Treviso", 'regione_id' => 17, 'sigla' => "TV"]);
        $this->insert(self::TABLE, ['id' => 70, 'nome' => "Venezia", 'regione_id' => 17, 'sigla' => "VE"]);
        $this->insert(self::TABLE, ['id' => 71, 'nome' => "Verona", 'regione_id' => 17, 'sigla' => "VR"]);
        $this->insert(self::TABLE, ['id' => 72, 'nome' => "Vicenza", 'regione_id' => 17, 'sigla' => "VI"]);
        $this->insert(self::TABLE, ['id' => 73, 'nome' => "Gorizia", 'regione_id' => 15, 'sigla' => "GO"]);
        $this->insert(self::TABLE, ['id' => 74, 'nome' => "Pordenone", 'regione_id' => 15, 'sigla' => "PN"]);
        $this->insert(self::TABLE, ['id' => 75, 'nome' => "Udine", 'regione_id' => 15, 'sigla' => "UD"]);
        $this->insert(self::TABLE, ['id' => 76, 'nome' => "Trieste", 'regione_id' => 15, 'sigla' => "TS"]);
        $this->insert(self::TABLE, ['id' => 77, 'nome' => "Aosta", 'regione_id' => 16, 'sigla' => "AO"]);
        $this->insert(self::TABLE, ['id' => 78, 'nome' => "Cagliari", 'regione_id' => 10, 'sigla' => "CA"]);
        $this->insert(self::TABLE, ['id' => 79, 'nome' => "Nuoro", 'regione_id' => 10, 'sigla' => "NU"]);
        $this->insert(self::TABLE, ['id' => 80, 'nome' => "Oristano", 'regione_id' => 10, 'sigla' => "OR"]);
        $this->insert(self::TABLE, ['id' => 81, 'nome' => "Sassari", 'regione_id' => 10, 'sigla' => "SS"]);
        $this->insert(self::TABLE, ['id' => 82, 'nome' => "Genova", 'regione_id' => 18, 'sigla' => "GE"]);
        $this->insert(self::TABLE, ['id' => 83, 'nome' => "Imperia", 'regione_id' => 18, 'sigla' => "IM"]);
        $this->insert(self::TABLE, ['id' => 84, 'nome' => "Savona", 'regione_id' => 18, 'sigla' => "SV"]);
        $this->insert(self::TABLE, ['id' => 85, 'nome' => "La Spezia", 'regione_id' => 18, 'sigla' => "SP"]);
        $this->insert(self::TABLE, ['id' => 86, 'nome' => "Isernia", 'regione_id' => 4, 'sigla' => "IS"]);
        $this->insert(self::TABLE, ['id' => 87, 'nome' => "Campobasso", 'regione_id' => 4, 'sigla' => "CB"]);
        $this->insert(self::TABLE, ['id' => 88, 'nome' => "Perugia", 'regione_id' => 20, 'sigla' => "PG"]);
        $this->insert(self::TABLE, ['id' => 89, 'nome' => "Terni", 'regione_id' => 20, 'sigla' => "TR"]);
        $this->insert(self::TABLE, ['id' => 90, 'nome' => "Bergamo", 'regione_id' => 19, 'sigla' => "BG"]);
        $this->insert(self::TABLE, ['id' => 91, 'nome' => "Brescia", 'regione_id' => 19, 'sigla' => "BS"]);
        $this->insert(self::TABLE, ['id' => 92, 'nome' => "Como", 'regione_id' => 19, 'sigla' => "CO"]);
        $this->insert(self::TABLE, ['id' => 93, 'nome' => "Cremona", 'regione_id' => 19, 'sigla' => "CR"]);
        $this->insert(self::TABLE, ['id' => 94, 'nome' => "Lecco", 'regione_id' => 19, 'sigla' => "LC"]);
        $this->insert(self::TABLE, ['id' => 95, 'nome' => "Lodi", 'regione_id' => 19, 'sigla' => "LO"]);
        $this->insert(self::TABLE, ['id' => 96, 'nome' => "Mantova", 'regione_id' => 19, 'sigla' => "MN"]);
        $this->insert(self::TABLE, ['id' => 97, 'nome' => "Milano", 'regione_id' => 19, 'sigla' => "MI"]);
        $this->insert(self::TABLE, ['id' => 98, 'nome' => "Pavia", 'regione_id' => 19, 'sigla' => "PV"]);
        $this->insert(self::TABLE, ['id' => 99, 'nome' => "Sondrio", 'regione_id' => 19, 'sigla' => "SO"]);
        $this->insert(self::TABLE, ['id' => 100, 'nome' => "Varese", 'regione_id' => 19, 'sigla' => "VA"]);
        $this->insert(self::TABLE, ['id' => 101, 'nome' => "Trento", 'regione_id' => 5, 'sigla' => "TN"]);
        $this->insert(self::TABLE, ['id' => 102, 'nome' => "Bolzano", 'regione_id' => 5, 'sigla' => "BZ"]);
        $this->insert(self::TABLE, ['id' => 103, 'nome' => "Prato", 'regione_id' => 12, 'sigla' => "PO"]);
        $this->insert(self::TABLE, ['id' => 104, 'nome' => "Verbania", 'regione_id' => 13, 'sigla' => "VB"]);
        $this->insert(self::TABLE, ['id' => 105, 'nome' => "Carbonia Iglesias", 'regione_id' => 10, 'sigla' => "CI"]);
        $this->insert(self::TABLE, ['id' => 106, 'nome' => "Medio Campidano", 'regione_id' => 10, 'sigla' => "VS"]);
        $this->insert(self::TABLE, ['id' => 107, 'nome' => "Ogliastra", 'regione_id' => 10, 'sigla' => "OG"]);
        $this->insert(self::TABLE, ['id' => 108, 'nome' => "Olbia Tempio", 'regione_id' => 10, 'sigla' => "OT"]);      
    }

    public function safeDown()
    {
        $this->delete(self::TABLE, ['id' => 2]);
        $this->delete(self::TABLE, ['id' => 3]);
        $this->delete(self::TABLE, ['id' => 4]);
        $this->delete(self::TABLE, ['id' => 5]);
        $this->delete(self::TABLE, ['id' => 6]);
        $this->delete(self::TABLE, ['id' => 7]);
        $this->delete(self::TABLE, ['id' => 8]);
        $this->delete(self::TABLE, ['id' => 9]);
        $this->delete(self::TABLE, ['id' => 10]);
        $this->delete(self::TABLE, ['id' => 11]);
        $this->delete(self::TABLE, ['id' => 12]);
        $this->delete(self::TABLE, ['id' => 13]);
        $this->delete(self::TABLE, ['id' => 14]);
        $this->delete(self::TABLE, ['id' => 15]);
        $this->delete(self::TABLE, ['id' => 16]);
        $this->delete(self::TABLE, ['id' => 17]);
        $this->delete(self::TABLE, ['id' => 18]);
        $this->delete(self::TABLE, ['id' => 19]);
        $this->delete(self::TABLE, ['id' => 20]);
        $this->delete(self::TABLE, ['id' => 21]);
        $this->delete(self::TABLE, ['id' => 22]);
        $this->delete(self::TABLE, ['id' => 23]);
        $this->delete(self::TABLE, ['id' => 24]);
        $this->delete(self::TABLE, ['id' => 25]);
        $this->delete(self::TABLE, ['id' => 27]);
        $this->delete(self::TABLE, ['id' => 28]);
        $this->delete(self::TABLE, ['id' => 29]);
        $this->delete(self::TABLE, ['id' => 30]);
        $this->delete(self::TABLE, ['id' => 31]);
        $this->delete(self::TABLE, ['id' => 32]);
        $this->delete(self::TABLE, ['id' => 33]);
        $this->delete(self::TABLE, ['id' => 34]);
        $this->delete(self::TABLE, ['id' => 35]);
        $this->delete(self::TABLE, ['id' => 36]);
        $this->delete(self::TABLE, ['id' => 37]);
        $this->delete(self::TABLE, ['id' => 38]);
        $this->delete(self::TABLE, ['id' => 39]);
        $this->delete(self::TABLE, ['id' => 40]);
        $this->delete(self::TABLE, ['id' => 41]);
        $this->delete(self::TABLE, ['id' => 42]);
        $this->delete(self::TABLE, ['id' => 43]);
        $this->delete(self::TABLE, ['id' => 44]);
        $this->delete(self::TABLE, ['id' => 45]);
        $this->delete(self::TABLE, ['id' => 46]);
        $this->delete(self::TABLE, ['id' => 47]);
        $this->delete(self::TABLE, ['id' => 48]);
        $this->delete(self::TABLE, ['id' => 49]);
        $this->delete(self::TABLE, ['id' => 50]);
        $this->delete(self::TABLE, ['id' => 51]);
        $this->delete(self::TABLE, ['id' => 52]);
        $this->delete(self::TABLE, ['id' => 53]);
        $this->delete(self::TABLE, ['id' => 54]);
        $this->delete(self::TABLE, ['id' => 55]);
        $this->delete(self::TABLE, ['id' => 56]);
        $this->delete(self::TABLE, ['id' => 57]);
        $this->delete(self::TABLE, ['id' => 58]);
        $this->delete(self::TABLE, ['id' => 59]);
        $this->delete(self::TABLE, ['id' => 60]);
        $this->delete(self::TABLE, ['id' => 61]);
        $this->delete(self::TABLE, ['id' => 62]);
        $this->delete(self::TABLE, ['id' => 63]);
        $this->delete(self::TABLE, ['id' => 64]);
        $this->delete(self::TABLE, ['id' => 65]);
        $this->delete(self::TABLE, ['id' => 66]);
        $this->delete(self::TABLE, ['id' => 67]);
        $this->delete(self::TABLE, ['id' => 68]);
        $this->delete(self::TABLE, ['id' => 69]);
        $this->delete(self::TABLE, ['id' => 70]);
        $this->delete(self::TABLE, ['id' => 71]);
        $this->delete(self::TABLE, ['id' => 72]);
        $this->delete(self::TABLE, ['id' => 73]);
        $this->delete(self::TABLE, ['id' => 74]);
        $this->delete(self::TABLE, ['id' => 75]);
        $this->delete(self::TABLE, ['id' => 76]);
        $this->delete(self::TABLE, ['id' => 77]);
        $this->delete(self::TABLE, ['id' => 78]);
        $this->delete(self::TABLE, ['id' => 79]);
        $this->delete(self::TABLE, ['id' => 80]);
        $this->delete(self::TABLE, ['id' => 81]);
        $this->delete(self::TABLE, ['id' => 82]);
        $this->delete(self::TABLE, ['id' => 83]);
        $this->delete(self::TABLE, ['id' => 84]);
        $this->delete(self::TABLE, ['id' => 85]);
        $this->delete(self::TABLE, ['id' => 86]);
        $this->delete(self::TABLE, ['id' => 87]);
        $this->delete(self::TABLE, ['id' => 88]);
        $this->delete(self::TABLE, ['id' => 89]);
        $this->delete(self::TABLE, ['id' => 90]);
        $this->delete(self::TABLE, ['id' => 91]);
        $this->delete(self::TABLE, ['id' => 92]);
        $this->delete(self::TABLE, ['id' => 93]);
        $this->delete(self::TABLE, ['id' => 94]);
        $this->delete(self::TABLE, ['id' => 95]);
        $this->delete(self::TABLE, ['id' => 96]);
        $this->delete(self::TABLE, ['id' => 97]);
        $this->delete(self::TABLE, ['id' => 98]);
        $this->delete(self::TABLE, ['id' => 99]);
        $this->delete(self::TABLE, ['id' => 100]);
        $this->delete(self::TABLE, ['id' => 101]);
        $this->delete(self::TABLE, ['id' => 102]);
        $this->delete(self::TABLE, ['id' => 103]);
        $this->delete(self::TABLE, ['id' => 104]);
        $this->delete(self::TABLE, ['id' => 105]);
        $this->delete(self::TABLE, ['id' => 106]);
        $this->delete(self::TABLE, ['id' => 107]);
        $this->delete(self::TABLE, ['id' => 108]);
    }
*/
}