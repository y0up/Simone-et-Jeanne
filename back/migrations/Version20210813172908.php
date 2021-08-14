<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210813172908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_caracteristic_detail (product_id INT NOT NULL, caracteristic_detail_id INT NOT NULL, INDEX IDX_92AAD24A4584665A (product_id), INDEX IDX_92AAD24AD76AA1A5 (caracteristic_detail_id), PRIMARY KEY(product_id, caracteristic_detail_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_caracteristic_detail ADD CONSTRAINT FK_92AAD24A4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_caracteristic_detail ADD CONSTRAINT FK_92AAD24AD76AA1A5 FOREIGN KEY (caracteristic_detail_id) REFERENCES caracteristic_detail (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_caracteristic_detail');
    }
}
