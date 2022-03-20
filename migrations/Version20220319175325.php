<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220319175325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_color DROP CONSTRAINT fk_c70a33b57ada1fb5');
        $this->addSql('DROP SEQUENCE color_id_seq CASCADE');
        $this->addSql('DROP TABLE product_color');
        $this->addSql('DROP TABLE color');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE color_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_color (product_id INT NOT NULL, color_id INT NOT NULL, PRIMARY KEY(product_id, color_id))');
        $this->addSql('CREATE INDEX idx_c70a33b54584665a ON product_color (product_id)');
        $this->addSql('CREATE INDEX idx_c70a33b57ada1fb5 ON product_color (color_id)');
        $this->addSql('CREATE TABLE color (id INT NOT NULL, name VARCHAR(100) NOT NULL, hex_color VARCHAR(6) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT fk_c70a33b54584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT fk_c70a33b57ada1fb5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
