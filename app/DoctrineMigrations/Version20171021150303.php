<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171021150303 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vouchers CHANGE expiration_date expiration_date DATETIME DEFAULT NULL, CHANGE partial_payment partial_payment DOUBLE PRECISION DEFAULT NULL, CHANGE voucher_code voucher_code VARCHAR(255) DEFAULT NULL, CHANGE method_of_payment method_of_payment VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vouchers CHANGE voucher_code voucher_code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE expiration_date expiration_date DATETIME NOT NULL, CHANGE partial_payment partial_payment DOUBLE PRECISION NOT NULL, CHANGE method_of_payment method_of_payment VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
