<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171021135724 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE voucher_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vouchers ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vouchers ADD CONSTRAINT FK_93150748C54C8C93 FOREIGN KEY (type_id) REFERENCES voucher_type (id)');
        $this->addSql('CREATE INDEX IDX_93150748C54C8C93 ON vouchers (type_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vouchers DROP FOREIGN KEY FK_93150748C54C8C93');
        $this->addSql('DROP TABLE voucher_type');
        $this->addSql('DROP INDEX IDX_93150748C54C8C93 ON vouchers');
        $this->addSql('ALTER TABLE vouchers DROP type_id');
    }
}
