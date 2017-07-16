<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170716132603 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE users_shops');
        $this->addSql('ALTER TABLE users ADD shop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E94D16C4DD FOREIGN KEY (shop_id) REFERENCES shops (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E94D16C4DD ON users (shop_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users_shops (user_id INT NOT NULL, shop_id INT NOT NULL, INDEX IDX_C40DC3CAA76ED395 (user_id), INDEX IDX_C40DC3CA4D16C4DD (shop_id), PRIMARY KEY(user_id, shop_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_shops ADD CONSTRAINT FK_C40DC3CA4D16C4DD FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_shops ADD CONSTRAINT FK_C40DC3CAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E94D16C4DD');
        $this->addSql('DROP INDEX IDX_1483A5E94D16C4DD ON users');
        $this->addSql('ALTER TABLE users DROP shop_id');
    }
}
