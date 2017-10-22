<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171022110828 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'UPDATE available_services SET name=\'FLOAT_SINGLE_60\' WHERE id=1'
        );

        $this->addSql(
            'UPDATE available_services SET name=\'FLOAT_DOUBLE_60\' WHERE id=2'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql(
            'UPDATE available_services SET name=\'FLOAT_CLASSIC_60\' WHERE id=1'
        );

        $this->addSql(
            'UPDATE available_services SET name=\'FLOAT_FLOAT_60\' WHERE id=2'
        );
    }
}
