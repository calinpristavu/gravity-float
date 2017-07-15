<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170715103325 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(40) NOT NULL, can_create_vouchers TINYINT(1) NOT NULL, phone VARCHAR(15) NOT NULL, UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_1483A5E9C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_shops (user_id INT NOT NULL, shop_id INT NOT NULL, INDEX IDX_C40DC3CAA76ED395 (user_id), INDEX IDX_C40DC3CA4D16C4DD (shop_id), PRIMARY KEY(user_id, shop_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shops (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vouchers (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, shop_where_created_id INT DEFAULT NULL, designated_customer_id INT DEFAULT NULL, creation_date DATETIME NOT NULL, expiration_date DATETIME NOT NULL, original_value DOUBLE PRECISION NOT NULL, partial_payment DOUBLE PRECISION NOT NULL, number_of_users VARCHAR(255) NOT NULL, online_voucher TINYINT(1) NOT NULL, methods_of_payment LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', usages LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_93150748F675F31B (author_id), INDEX IDX_9315074856C5E2AF (shop_where_created_id), INDEX IDX_93150748D2134560 (designated_customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_shops ADD CONSTRAINT FK_C40DC3CAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_shops ADD CONSTRAINT FK_C40DC3CA4D16C4DD FOREIGN KEY (shop_id) REFERENCES shops (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vouchers ADD CONSTRAINT FK_93150748F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE vouchers ADD CONSTRAINT FK_9315074856C5E2AF FOREIGN KEY (shop_where_created_id) REFERENCES shops (id)');
        $this->addSql('ALTER TABLE vouchers ADD CONSTRAINT FK_93150748D2134560 FOREIGN KEY (designated_customer_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE vouchers ADD voucher_code VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_931507487678B020 ON vouchers (voucher_code)');

        $this->addSql(
            'INSERT IGNORE INTO shops(id, name, address) VALUES(?,?,?)',
            [1, 'Shop A', 'Description for Shop A']
        );
        $this->addSql(
            'INSERT IGNORE INTO shops(id, name, address) VALUES(?,?,?)',
            [2, 'Shop B', 'Description for Shop B']
        );
        $this->addSql(
            'INSERT IGNORE INTO shops(id, name, address) VALUES(?,?,?)',
            [3, 'Shop C', 'Description for Shop C']
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_shops DROP FOREIGN KEY FK_C40DC3CAA76ED395');
        $this->addSql('ALTER TABLE vouchers DROP FOREIGN KEY FK_93150748F675F31B');
        $this->addSql('ALTER TABLE vouchers DROP FOREIGN KEY FK_93150748D2134560');
        $this->addSql('ALTER TABLE users_shops DROP FOREIGN KEY FK_C40DC3CA4D16C4DD');
        $this->addSql('ALTER TABLE vouchers DROP FOREIGN KEY FK_9315074856C5E2AF');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_shops');
        $this->addSql('DROP TABLE shops');
        $this->addSql('DROP TABLE vouchers');
    }
}
