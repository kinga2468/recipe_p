<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190529195550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ingredients_measures (ingredient_id INT NOT NULL, measure_id INT NOT NULL, INDEX IDX_2159121C933FE08C (ingredient_id), INDEX IDX_2159121C5DA37D00 (measure_id), PRIMARY KEY(ingredient_id, measure_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredients_measures ADD CONSTRAINT FK_2159121C933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients_measures ADD CONSTRAINT FK_2159121C5DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE ingredient_measure');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ingredient_measure (ingredient_id INT NOT NULL, measure_id INT NOT NULL, amount INT DEFAULT NULL, INDEX IDX_813012E35DA37D00 (measure_id), INDEX IDX_813012E3933FE08C (ingredient_id), PRIMARY KEY(ingredient_id, measure_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ingredient_measure ADD CONSTRAINT FK_813012E35DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_measure ADD CONSTRAINT FK_813012E3933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE ingredients_measures');
    }
}
