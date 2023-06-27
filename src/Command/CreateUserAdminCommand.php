<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user-admin',
    description: 'Creates the default Admin user',
)]
class CreateUserAdminCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface      $entityManager,
        private UserRepository              $userRepository,
        string                              $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = filter_var(
            $io->ask('Please enter the email for the admin user'),
            FILTER_VALIDATE_EMAIL
        );

        if ($this->userRepository->findBy(['email' => $email])) {
            $io->error('The email is already in use');
            return Command::FAILURE;
        }

        $clearPassword = $io->ask('Please enter the password for the admin user');

        if (!$email || !$clearPassword) {
            $io->error('Invalid email or password');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $clearPassword
            )
        );
        $user->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($user);

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $io->error('There was an error creating the user');
            $io->error('Error: ' . $e->getMessage());
            $io->text($e->getTraceAsString());
            return Command::FAILURE;
        }

        $io->success('Admin user created successfully');

        return Command::SUCCESS;
    }
}
