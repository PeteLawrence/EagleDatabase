<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:create-admin')
            ->setDescription('Creates a new admin user.')
            ->setHelp('This command allows you to create a new admin user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email address of the admin user to create.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password to assign to the admin user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $admin = new \AppBundle\Entity\Person;

        //Create a hash of the password
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($admin, $input->getArgument('password'));

        $admin->setEmail($input->getArgument('email'));
        $admin->setPassword($encodedPassword);
        $admin->setAdmin(true);

        //Persist and flush to DB
        $em->persist($admin);
        $em->flush();

        $output->writeln('User has been created');
    }
}
