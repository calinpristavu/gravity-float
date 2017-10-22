<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171022103444 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [1, 'FLOAT_CLASSIC_60', 69]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [2, 'FLOAT_FOOT_60', 99]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [3, 'MASSAGE_CLASSIC_30', 35]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [4, 'MASSAGE_CLASSIC_45', 50]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [5, 'MASSAGE_CLASSIC_60', 65]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [6, 'MASSAGE_CLASSIC_90', 95]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [7, 'MASSAGE_FOOT_30', 35]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [8, 'MASSAGE_DEEP_60', 80]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [9, 'MASSAGE_DEEP_90', 110]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [10, 'FLOAT_60_MASSAGE_CLASSIC_30_SINGLE', 104]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [11, 'FLOAT_60_MASSAGE_CLASSIC_30_DOUBLE', 169]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [12, 'FLOAT_60_MASSAGE_CLASSIC_45_SINGLE', 119]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [13, 'FLOAT_60_MASSAGE_CLASSIC_45_DOUBLE', 199]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [14, 'FLOAT_60_MASSAGE_CLASSIC_60_SINGLE', 134]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [15, 'FLOAT_60_MASSAGE_CLASSIC_60_DOUBLE', 229]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [16, 'FLOAT_60_MASSAGE_DEEP_60_SINGLE', 149]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [17, 'FLOAT_60_MASSAGE_DEEP_60_DOUBLE', 259]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [18, 'FLOAT_60_MASSAGE_CLASSIC_90_SINGLE', 164]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [19, 'FLOAT_60_MASSAGE_CLASSIC_90_DOUBLE', 289]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [20, 'FLOAT_60_MASSAGE_DEEP_90_SINGLE', 179]
        );

        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [21, 'FLOAT_60_MASSAGE_DEEP_90_DOUBLE', 319]
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM available_services');
    }
}
