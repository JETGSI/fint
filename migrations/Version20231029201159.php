<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029201159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE associative_experience (id INT AUTO_INCREMENT NOT NULL, curriculumvitae_id INT DEFAULT NULL, description LONGTEXT NOT NULL, organization VARCHAR(255) NOT NULL, startdate DATE NOT NULL, enddate DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_675BAB8513818212 (curriculumvitae_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curriculum_vitae (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, certificates LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1FC99844A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE educational_experience (id INT AUTO_INCREMENT NOT NULL, curriculumvitae_id INT NOT NULL, university VARCHAR(10) NOT NULL, description LONGTEXT NOT NULL, startdate DATE NOT NULL, enddate DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A5BDE64213818212 (curriculumvitae_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, service LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', logo_path VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_D19FA60A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_interview (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, type TINYINT(1) NOT NULL, location VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8ADD325A4AEAFEA (entreprise_id), INDEX IDX_8ADD325A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, application_deadline DATETIME NOT NULL, catalogue VARCHAR(255) NOT NULL, nb_views INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_288A3A4EA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_request (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, student_id INT DEFAULT NULL, date DATETIME NOT NULL, refsujet VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A1783804A4AEAFEA (entreprise_id), INDEX IDX_A1783804CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional_experience (id INT AUTO_INCREMENT NOT NULL, curriculumvitae_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, entreprise VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, startdate DATE NOT NULL, enddate DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_32FDB9BA13818212 (curriculumvitae_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, cv_id INT NOT NULL, nom VARCHAR(255) NOT NULL, descripton LONGTEXT NOT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2FB3D0EECFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, je TINYINT(1) NOT NULL, linkedin_link VARCHAR(255) DEFAULT NULL, telephone VARCHAR(18) NOT NULL, sharedata TINYINT(1) NOT NULL, link_cv VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE associative_experience ADD CONSTRAINT FK_675BAB8513818212 FOREIGN KEY (curriculumvitae_id) REFERENCES curriculum_vitae (id)');
        $this->addSql('ALTER TABLE curriculum_vitae ADD CONSTRAINT FK_1FC99844A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE educational_experience ADD CONSTRAINT FK_A5BDE64213818212 FOREIGN KEY (curriculumvitae_id) REFERENCES curriculum_vitae (id)');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE job_interview ADD CONSTRAINT FK_8ADD325A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE job_interview ADD CONSTRAINT FK_8ADD325A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE job_request ADD CONSTRAINT FK_A1783804A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE job_request ADD CONSTRAINT FK_A1783804CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE professional_experience ADD CONSTRAINT FK_32FDB9BA13818212 FOREIGN KEY (curriculumvitae_id) REFERENCES curriculum_vitae (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EECFE419E2 FOREIGN KEY (cv_id) REFERENCES curriculum_vitae (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE associative_experience DROP FOREIGN KEY FK_675BAB8513818212');
        $this->addSql('ALTER TABLE curriculum_vitae DROP FOREIGN KEY FK_1FC99844A76ED395');
        $this->addSql('ALTER TABLE educational_experience DROP FOREIGN KEY FK_A5BDE64213818212');
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60A76ED395');
        $this->addSql('ALTER TABLE job_interview DROP FOREIGN KEY FK_8ADD325A4AEAFEA');
        $this->addSql('ALTER TABLE job_interview DROP FOREIGN KEY FK_8ADD325A76ED395');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EA4AEAFEA');
        $this->addSql('ALTER TABLE job_request DROP FOREIGN KEY FK_A1783804A4AEAFEA');
        $this->addSql('ALTER TABLE job_request DROP FOREIGN KEY FK_A1783804CB944F1A');
        $this->addSql('ALTER TABLE professional_experience DROP FOREIGN KEY FK_32FDB9BA13818212');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EECFE419E2');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('DROP TABLE associative_experience');
        $this->addSql('DROP TABLE curriculum_vitae');
        $this->addSql('DROP TABLE educational_experience');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE job_interview');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE job_request');
        $this->addSql('DROP TABLE professional_experience');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE user');
    }
}
