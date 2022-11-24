<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124120040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stagiaire_institule_session (stagiaire_id INT NOT NULL, institule_session_id INT NOT NULL, INDEX IDX_95E9076DBBA93DD6 (stagiaire_id), INDEX IDX_95E9076D58539E10 (institule_session_id), PRIMARY KEY(stagiaire_id, institule_session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stagiaire_institule_session ADD CONSTRAINT FK_95E9076DBBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stagiaire_institule_session ADD CONSTRAINT FK_95E9076D58539E10 FOREIGN KEY (institule_session_id) REFERENCES institule_session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stagiaire_institule_session DROP FOREIGN KEY FK_95E9076DBBA93DD6');
        $this->addSql('ALTER TABLE stagiaire_institule_session DROP FOREIGN KEY FK_95E9076D58539E10');
        $this->addSql('DROP TABLE stagiaire_institule_session');
    }
}
