<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170718095200 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, voucher_bought_id INT DEFAULT NULL, product VARCHAR(50) NOT NULL, payment_date DATETIME NOT NULL, INDEX IDX_65D29B328C03F15C (employee_id), INDEX IDX_65D29B327613444C (voucher_bought_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B328C03F15C FOREIGN KEY (employee_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B327613444C FOREIGN KEY (voucher_bought_id) REFERENCES vouchers (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE payments');
    }
}
