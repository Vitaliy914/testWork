<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221120175412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baskets ADD status INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_in_baskets ADD CONSTRAINT FK_4517FA1FDE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baskets DROP status');
        $this->addSql('ALTER TABLE products_in_baskets DROP FOREIGN KEY FK_4517FA1FDE18E50B');
    }
}
