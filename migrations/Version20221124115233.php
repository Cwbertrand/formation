<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124115233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE programme ADD institule_session_id INT DEFAULT NULL, ADD module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF58539E10 FOREIGN KEY (institule_session_id) REFERENCES institule_session (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FFAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_3DDCB9FF58539E10 ON programme (institule_session_id)');
        $this->addSql('CREATE INDEX IDX_3DDCB9FFAFC2B591 ON programme (module_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF58539E10');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FFAFC2B591');
        $this->addSql('DROP INDEX IDX_3DDCB9FF58539E10 ON programme');
        $this->addSql('DROP INDEX IDX_3DDCB9FFAFC2B591 ON programme');
        $this->addSql('ALTER TABLE programme DROP institule_session_id, DROP module_id');
    }
}
