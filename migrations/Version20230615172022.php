<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615172022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BB458E68D FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BD17B6CD FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_70BE1A7BB458E68D ON command_line (command_id)');
        $this->addSql('CREATE INDEX IDX_70BE1A7BD17B6CD ON command_line (product_id)');
    }


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4E46B811B');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE command_line');
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7BB458E68D');
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7BD17B6CD');
        $this->addSql('DROP INDEX IDX_70BE1A7BB458E68D ON command_line');
        $this->addSql('DROP INDEX IDX_70BE1A7BD17B6CD ON command_line');
        $this->addSql('ALTER TABLE command_line DROP commandID, DROP productID');
    }
}
