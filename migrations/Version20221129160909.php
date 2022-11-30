<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221129160909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE institule_session ADD formateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE institule_session ADD CONSTRAINT FK_1BC38A90155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('CREATE INDEX IDX_1BC38A90155D8F51 ON institule_session (formateur_id)');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF58539E10');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FFAFC2B591');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF58539E10 FOREIGN KEY (institule_session_id) REFERENCES institule_session (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FFAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE stagiaire_institule_session DROP FOREIGN KEY FK_95E9076D58539E10');
        $this->addSql('ALTER TABLE stagiaire_institule_session DROP FOREIGN KEY FK_95E9076DBBA93DD6');
        $this->addSql('ALTER TABLE stagiaire_institule_session ADD CONSTRAINT FK_95E9076D58539E10 FOREIGN KEY (institule_session_id) REFERENCES institule_session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stagiaire_institule_session ADD CONSTRAINT FK_95E9076DBBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE institule_session DROP FOREIGN KEY FK_1BC38A90155D8F51');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('ALTER TABLE stagiaire_institule_session DROP FOREIGN KEY FK_95E9076DBBA93DD6');
        $this->addSql('ALTER TABLE stagiaire_institule_session DROP FOREIGN KEY FK_95E9076D58539E10');
        $this->addSql('ALTER TABLE stagiaire_institule_session ADD CONSTRAINT FK_95E9076DBBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stagiaire_institule_session ADD CONSTRAINT FK_95E9076D58539E10 FOREIGN KEY (institule_session_id) REFERENCES institule_session (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF58539E10');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FFAFC2B591');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF58539E10 FOREIGN KEY (institule_session_id) REFERENCES institule_session (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FFAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX IDX_1BC38A90155D8F51 ON institule_session');
        $this->addSql('ALTER TABLE institule_session DROP formateur_id');
    }
}
