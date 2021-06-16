<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616142124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, line1 LONGTEXT NOT NULL, line2 LONGTEXT DEFAULT NULL, city VARCHAR(255) NOT NULL, postal_code INT NOT NULL, country VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5CECC7BEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristic_detail (id INT AUTO_INCREMENT NOT NULL, caracteristic_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, info LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8CF261C981194CF4 (caracteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, shopping_session_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F0FE25277EF00B89 (shopping_session_id), UNIQUE INDEX UNIQ_F0FE25274584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, payment_detail_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_ED896F469BF92C93 (payment_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_detail_id INT DEFAULT NULL, quantity INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_52EA1F094584665A (product_id), INDEX IDX_52EA1F0964577843 (order_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, provider VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, account_number INT NOT NULL, expiry DATE NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6D28840DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_detail (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, provider VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, inventory_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, sku VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04AD9EEA759 (inventory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_caracteristic (product_id INT NOT NULL, caracteristic_id INT NOT NULL, INDEX IDX_41789FB64584665A (product_id), INDEX IDX_41789FB681194CF4 (caracteristic_id), PRIMARY KEY(product_id, caracteristic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, content LONGTEXT NOT NULL, rate INT NOT NULL, cretated_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_session (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CECE98A6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE caracteristic_detail ADD CONSTRAINT FK_8CF261C981194CF4 FOREIGN KEY (caracteristic_id) REFERENCES caracteristic (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25277EF00B89 FOREIGN KEY (shopping_session_id) REFERENCES shopping_session (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F469BF92C93 FOREIGN KEY (payment_detail_id) REFERENCES payment_detail (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0964577843 FOREIGN KEY (order_detail_id) REFERENCES order_detail (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE product_caracteristic ADD CONSTRAINT FK_41789FB64584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_caracteristic ADD CONSTRAINT FK_41789FB681194CF4 FOREIGN KEY (caracteristic_id) REFERENCES caracteristic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE shopping_session ADD CONSTRAINT FK_CECE98A6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD order_detail_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64964577843 FOREIGN KEY (order_detail_id) REFERENCES order_detail (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64964577843 ON user (order_detail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracteristic_detail DROP FOREIGN KEY FK_8CF261C981194CF4');
        $this->addSql('ALTER TABLE product_caracteristic DROP FOREIGN KEY FK_41789FB681194CF4');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9EEA759');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0964577843');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64964577843');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F469BF92C93');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE product_caracteristic DROP FOREIGN KEY FK_41789FB64584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64584665A');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25277EF00B89');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE caracteristic');
        $this->addSql('DROP TABLE caracteristic_detail');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_detail');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_caracteristic');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE shopping_session');
        $this->addSql('DROP INDEX UNIQ_8D93D64964577843 ON user');
        $this->addSql('ALTER TABLE user DROP order_detail_id, DROP created_at, DROP updated_at');
    }
}
