<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'app:user:create';

    /**
     * @var SymfonyStyle
     */
    private $io;
    private $entityManager;
    private $passwordHasher;
    private $validator;
    private $users;

    /**
     * UserCreateCommand constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $encoder
     * @param UserRepository $users
     */
    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher, Validator $validator, UserRepository $users)
    {
        parent::__construct();
        $this->entityManager = $em;
        $this->passwordHasher = $hasher;
        $this->validator = $validator;
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates users and stores them in the database')
            ->setHelp($this->getCommandHelp())
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addArgument('first-name', InputArgument::OPTIONAL, 'The first name of the new user')
            ->addArgument('last-name', InputArgument::OPTIONAL, 'The last name of the new user')
            ->addArgument('roles', InputArgument::OPTIONAL, 'The role of the new user')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator');
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (
            null !== $input->getArgument('password') &&
            null !== $input->getArgument('email') &&
            null !== $input->getArgument('first-name') &&
            null !== $input->getArgument('last-name') &&
            null !== $input->getArgument('roles')
        ) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:user:create email@example.com password ROLE_EXAMPLE,ROLE_EXAMPLE',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);
        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: ' . $email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }
        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: ' . str_repeat('*', mb_strlen($password)));
        } else {
            $password = $this->io->askHidden('Password (your type will be hidden)', [$this->validator, 'validatePassword']);
            $input->setArgument('password', $password);
        }
        // Ask for the first name if it's not defined
        $firstName = $input->getArgument('first-name');
        if (null !== $firstName) {
            $this->io->text(' > <info>First Name</info>: ' . $firstName);
        } else {
            $firstName = $this->io->ask('First Name', null, [$this->validator, 'validateFullName']);
            $input->setArgument('first-name', $firstName);
        }
        // Ask for the last name if it's not defined
        $lastName = $input->getArgument('last-name');
        if (null !== $lastName) {
            $this->io->text(' > <info>Last Name</info>: ' . $lastName);
        } else {
            $lastName = $this->io->ask('Last Name', null, [$this->validator, 'validateFullName']);
            $input->setArgument('last-name', $lastName);
        }
        // Ask for the role if it's not defined
        $roles = $input->getArgument('roles');
        if (null !== $roles) {
            $this->io->text(' > <info>Roles</info>: ' . $roles);
        } else {
            $roles = $this->io->ask('Role(s) between (use the same syntaxe) : ' . implode(',', User::ROLES_ALLOWED), null, [$this->validator, 'validateRoles']);
            $input->setArgument('roles', $roles);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');
        $plainPassword = $input->getArgument('password');
        $email = $input->getArgument('email');
        $firstName = $input->getArgument('first-name');
        $lastName = $input->getArgument('last-name');
        $roles = $input->getArgument('roles');
        $isAdmin = $input->getOption('admin');
        // make sure to validate the user data is correct
        $this->validateUserData($plainPassword, $email, $firstName, $lastName, $roles);
        // create the user and encode its password
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setRoles($isAdmin ? ['ROLE_ADMIN'] : explode(',', $roles));
        // See https://symfony.com/doc/current/book/security.html#security-encoding-password
        $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->io->success(sprintf('%s was successfully created: %s', $isAdmin ? 'Administrator user' : 'User', $user->getEmail()));
        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }
        return 0;
    }


    /**
     * @param $plainPassword
     * @param $email
     * @param $firstName
     * @param $lastName
     * @param $roles
     * @internal param $fullName
     */
    private function validateUserData($plainPassword, $email, $firstName, $lastName, $roles): void
    {
        // validate password and email if is not this input means interactive.
        $this->validator->validatePassword($plainPassword);
        $this->validator->validateEmail($email);
        $this->validator->validateFullName($firstName);
        $this->validator->validateFullName($lastName);
        // check if a user with the same email already exists.
        $existingEmail = $this->users->findOneBy(['email' => $email]);
        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
        $this->validator->validateRoles($roles);
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
                The <info>%command.name%</info> command creates new users and saves them in the database:
                  <info>php %command.full_name%</info> <comment>email password</comment>
                By default the command creates regular users. To create administrator users,
                add the <comment>--admin</comment> option:
                  <info>php %command.full_name%</info> email password<comment>--admin</comment>
                If you omit any of the three required arguments, the command will ask you to
                provide the missing values:
                  # command will ask you for the password
                  <info>php %command.full_name%</info> <comment>email</comment>
                  # command will ask you for all arguments
                  <info>php %command.full_name%</info>
                HELP;
    }
}