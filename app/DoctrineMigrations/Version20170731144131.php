<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170731144131 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE voucher_code_information (shop_id INT NOT NULL, voucher_code INT NOT NULL, PRIMARY KEY(shop_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

        $this->addSql(
            'INSERT IGNORE INTO voucher_code_information(shop_id, voucher_code) VALUES(?,?)',
            [1, 10000]
        );
        $this->addSql(
            'INSERT IGNORE INTO voucher_code_information(shop_id, voucher_code) VALUES(?,?)',
            [2, 20000]
        );
        $this->addSql(
            'INSERT IGNORE INTO voucher_code_information(shop_id, voucher_code) VALUES(?,?)',
            [3, 30000]
        );
        $this->addSql(
            'INSERT IGNORE INTO voucher_code_information(shop_id, voucher_code) VALUES(?,?)',
            [0, 40000]
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE voucher_code_information');
    }
}
