<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702192533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE log_portiques');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log_portiques (Name VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, id INT DEFAULT NULL, time DATETIME DEFAULT NULL, pin INT DEFAULT NULL, card_no VARCHAR(10) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, device_id INT DEFAULT NULL, device_sn VARCHAR(19) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, device_name VARCHAR(30) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, verified INT DEFAULT NULL, state INT DEFAULT NULL, event_point_id INT DEFAULT NULL, event_point_name VARCHAR(15) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, INDEX id (id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
    }
}
