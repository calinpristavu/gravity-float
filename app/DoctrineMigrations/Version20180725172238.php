<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180725172238 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [22, 'COSMETIC_CLASSIC', 79]
        );
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [23, 'COSMETIC_CLASSIC_PLUS', 115]
        );
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [24, 'COSMETIC_MICRO_DERMA', 85]
        );
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [25, 'COSMETIC_MICRO_DERMA_PLUS', 125]
        );
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [26, 'COSMETIC_MICRO_NEEDLING', 160]
        );
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [27, 'COSMETIC_MICRO_NEEDLING_PLUS', 200]
        );
        $this->addSql(
            'INSERT IGNORE INTO available_services(id, name, price) VALUES(?,?,?)',
            [28, 'COSMETIC_COUPEROSE', 95]
        );
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM available_services WHERE id IN (22, 23, 24, 25, 26, 27, 28)');
    }
}
