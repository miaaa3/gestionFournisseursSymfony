<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615205340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD42ADD6D8C');
        $this->addSql('DROP INDEX IDX_8ECAEAD42ADD6D8C ON command');
        $this->addSql('ALTER TABLE command ADD supplierID INT DEFAULT NULL, CHANGE supplier_id supplier_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4E46B811B FOREIGN KEY (supplierID) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD4E46B811B ON command (supplierID)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4E46B811B');
        $this->addSql('DROP INDEX IDX_8ECAEAD4E46B811B ON command');
        $this->addSql('ALTER TABLE command DROP supplierID, CHANGE supplier_id supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD42ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD42ADD6D8C ON command (supplier_id)');
    }
}
