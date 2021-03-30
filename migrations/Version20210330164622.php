<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210330164622 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SEQUENCE app_reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_reservation (id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO app_reservation VALUES (nextval('app_reservation_id_seq'), 'test reservation', 125)");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP SEQUENCE app_reservation_id_seq CASCADE');
        $this->addSql('DROP TABLE app_reservation');
    }
}
