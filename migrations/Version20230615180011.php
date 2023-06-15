<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615180011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8ECAEAD4E46B811B ON command');
        $this->addSql('ALTER TABLE command CHANGE supplierID supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD42ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD42ADD6D8C ON command (supplier_id)');
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7BD17B6CD');
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7BB458E68D');
        $this->addSql('DROP INDEX IDX_70BE1A7BB458E68D ON command_line');
        $this->addSql('DROP INDEX IDX_70BE1A7BD17B6CD ON command_line');
        $this->addSql('ALTER TABLE command_line ADD commandID INT DEFAULT NULL, ADD productID INT DEFAULT NULL');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BD17B6CD FOREIGN KEY (productID) REFERENCES product (id)');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BB458E68D FOREIGN KEY (commandID) REFERENCES command (id)');
        $this->addSql('CREATE INDEX IDX_70BE1A7BB458E68D ON command_line (commandID)');
        $this->addSql('CREATE INDEX IDX_70BE1A7BD17B6CD ON command_line (productID)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
       
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7BB458E68D');
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7BD17B6CD');
        $this->addSql('DROP INDEX IDX_70BE1A7BB458E68D ON command_line');
        $this->addSql('DROP INDEX IDX_70BE1A7BD17B6CD ON command_line');
        $this->addSql('ALTER TABLE command_line DROP commandID, DROP productID');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BB458E68D FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BD17B6CD FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_70BE1A7BB458E68D ON command_line (command_id)');
        $this->addSql('CREATE INDEX IDX_70BE1A7BD17B6CD ON command_line (product_id)');
    }
}
