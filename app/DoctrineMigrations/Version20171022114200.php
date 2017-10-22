<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171022114200 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vouchers ADD service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vouchers ADD CONSTRAINT FK_93150748ED5CA9E6 FOREIGN KEY (service_id) REFERENCES available_services (id)');
        $this->addSql('CREATE INDEX IDX_93150748ED5CA9E6 ON vouchers (service_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vouchers DROP FOREIGN KEY FK_93150748ED5CA9E6');
        $this->addSql('DROP INDEX IDX_93150748ED5CA9E6 ON vouchers');
        $this->addSql('ALTER TABLE vouchers DROP service_id');
    }
}
