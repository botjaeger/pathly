<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001032003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE career (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_career_weight (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, career_id INT NOT NULL, weight INT NOT NULL, INDEX IDX_9D68018E1E27F6BF (question_id), INDEX IDX_9D68018EB58CDA09 (career_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_career_weight ADD CONSTRAINT FK_9D68018E1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_career_weight ADD CONSTRAINT FK_9D68018EB58CDA09 FOREIGN KEY (career_id) REFERENCES career (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_career_weight DROP FOREIGN KEY FK_9D68018E1E27F6BF');
        $this->addSql('ALTER TABLE question_career_weight DROP FOREIGN KEY FK_9D68018EB58CDA09');
        $this->addSql('DROP TABLE career');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_career_weight');
    }
}
