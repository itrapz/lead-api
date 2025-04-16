<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'app:create-user')]
class CreateUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $userReflection = new \ReflectionClass($user);

        $userReflection->getProperty('firstName')->setValue($user, 'TestFirstName');
        $userReflection->getProperty('lastName')->setValue($user, 'TestLastName');
        $userReflection->getProperty('email')->setValue($user, 'test@test.com');
        $userReflection->getProperty('phone')->setValue($user, '9295032300');
        $userReflection->getProperty('dateOfBirth')->setValue($user, (new DateTimeImmutable())->modify('- 20 years'));
        $userReflection->getProperty('username')->setValue($user, 'testuser');
        $userReflection->getProperty('apiToken')->setValue($user, 'testtoken123');

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('User created: testuser with token testtoken123');

        return Command::SUCCESS;
    }
}