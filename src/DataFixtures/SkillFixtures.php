<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// Команда: php bin/console doctrine:fixtures:load --append
class SkillFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $skills = [
            'PHP', 'JavaScript', 'Python', 'Java', 'C#',
            'Symfony', 'Laravel', 'React', 'Vue.js', 'Angular',
            'Sql', 'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Elasticsearch', 'RabbitMQ',
            'Docker', 'Kubernetes', 'AWS', 'Git', 'Linux',
            'REST API', 'GRPC', 'GraphQL', 'Microservices', 'CI/CD', 'Testing',
            'Project Management', 'Agile', 'Scrum', 'Leadership', 'Communication'
        ];

        foreach ($skills as $skillName) {
            $skill = new Skill();
            $skill->setName($skillName);
            $skill->setDescr("Навык $skillName - описание ...");
            $manager->persist($skill);
        }

        $manager->flush();
    }
}
