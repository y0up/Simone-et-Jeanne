<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210808162520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_ED896F46A76ED395 ON order_detail (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64964577843');
        $this->addSql('DROP INDEX UNIQ_8D93D64964577843 ON user');
        $this->addSql('ALTER TABLE user DROP order_detail_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46A76ED395');
        $this->addSql('DROP INDEX IDX_ED896F46A76ED395 ON order_detail');
        $this->addSql('ALTER TABLE order_detail DROP user_id');
        $this->addSql('ALTER TABLE user ADD order_detail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64964577843 FOREIGN KEY (order_detail_id) REFERENCES order_detail (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64964577843 ON user (order_detail_id)');
    }
}
