<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111103348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abstact_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(128) NOT NULL, last_name VARCHAR(128) NOT NULL, phone VARCHAR(16) NOT NULL, gender SMALLINT NOT NULL, is_valid TINYINT(1) DEFAULT \'0\' NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, profile_type VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_F72F8CE0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE administrator (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, profile_id INT NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9474526CCCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contributor (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editor (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, profile_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5A8A6C8D2B36786B (title), UNIQUE INDEX UNIQ_5A8A6C8DCCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriber (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, last_login DATETIME DEFAULT NULL, token VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, creator_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6495F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abstact_profile ADD CONSTRAINT FK_F72F8CE0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651BF396750 FOREIGN KEY (id) REFERENCES abstact_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C8BF396750 FOREIGN KEY (id) REFERENCES abstact_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CCCFA12B8 FOREIGN KEY (profile_id) REFERENCES abstact_profile (id)');
        $this->addSql('ALTER TABLE contributor ADD CONSTRAINT FK_DA6F9793BF396750 FOREIGN KEY (id) REFERENCES abstact_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE editor ADD CONSTRAINT FK_CCF1F1BABF396750 FOREIGN KEY (id) REFERENCES abstact_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DCCFA12B8 FOREIGN KEY (profile_id) REFERENCES abstact_profile (id)');
        $this->addSql('ALTER TABLE subscriber ADD CONSTRAINT FK_AD005B69BF396750 FOREIGN KEY (id) REFERENCES abstact_profile (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651BF396750');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C8BF396750');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CCCFA12B8');
        $this->addSql('ALTER TABLE contributor DROP FOREIGN KEY FK_DA6F9793BF396750');
        $this->addSql('ALTER TABLE editor DROP FOREIGN KEY FK_CCF1F1BABF396750');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DCCFA12B8');
        $this->addSql('ALTER TABLE subscriber DROP FOREIGN KEY FK_AD005B69BF396750');
        $this->addSql('ALTER TABLE abstact_profile DROP FOREIGN KEY FK_F72F8CE0A76ED395');
        $this->addSql('DROP TABLE abstact_profile');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE contributor');
        $this->addSql('DROP TABLE editor');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('DROP TABLE user');
    }
}
