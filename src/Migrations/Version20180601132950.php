<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180601132950 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE eleves (id INT AUTO_INCREMENT NOT NULL, numero_equipe VARCHAR(4) NOT NULL, lettre_national VARCHAR(4) DEFAULT NULL, nom VARCHAR(63) NOT NULL, prenom VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE cadeaux');
        $this->addSql('DROP TABLE equipes');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cadeaux (id INT AUTO_INCREMENT NOT NULL, edition VARCHAR(2) NOT NULL COLLATE latin1_swedish_ci, cadeau VARCHAR(300) DEFAULT NULL COLLATE latin1_swedish_ci, donateur1 VARCHAR(100) DEFAULT NULL COLLATE latin1_swedish_ci, donateur2 VARCHAR(100) DEFAULT NULL COLLATE latin1_swedish_ci, cadeau_resume VARCHAR(50) DEFAULT NULL COLLATE latin1_swedish_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipes (id INT AUTO_INCREMENT NOT NULL, lettre_national VARCHAR(1) DEFAULT NULL COLLATE latin1_swedish_ci, numero_equipe SMALLINT UNSIGNED NOT NULL, titre VARCHAR(400) DEFAULT NULL COLLATE latin1_swedish_ci, nomcourt VARCHAR(100) DEFAULT NULL COLLATE latin1_swedish_ci, nom_lycee VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, denomination VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, ville VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, academie VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, prenom_prof1 VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, nom_prof1 VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, prenom_prof2 VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, nom_prof2 VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, UNIQUE INDEX id (id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, username VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE eleves');
    }
}
