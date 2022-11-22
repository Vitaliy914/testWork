<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221120123534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baskets (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, products_categories_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_B3BA5A5A931BBDCF (products_categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_categories (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_in_baskets (id INT AUTO_INCREMENT NOT NULL, basket_id_id INT NOT NULL, product_id_id INT NOT NULL, INDEX IDX_4517FA1F293CD56D (basket_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A931BBDCF FOREIGN KEY (products_categories_id) REFERENCES products_categories (id)');
        $this->addSql('ALTER TABLE products_in_baskets ADD CONSTRAINT FK_4517FA1F293CD56D FOREIGN KEY (basket_id_id) REFERENCES baskets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A931BBDCF');
        $this->addSql('ALTER TABLE products_in_baskets DROP FOREIGN KEY FK_4517FA1F293CD56D');
        $this->addSql('DROP TABLE baskets');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE products_categories');
        $this->addSql('DROP TABLE products_in_baskets');
    }
}
