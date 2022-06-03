<?php

namespace App\Command;

use App\Entity\Harbor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HarborCreateCommand extends Command
{
    protected static $defaultName = 'harbor:create';
    protected static $defaultDescription = 'Créer un nouvel objet port et l ajoute en base de donnée';

    private static $harborName;
    private static $harborCity;

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addOption('harborName', null, InputOption::VALUE_REQUIRED,'Nom du port')
            ->addOption('harborCity', null, InputOption::VALUE_REQUIRED,'Nom de la ville ou est le port')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('harborName')) {
            $harborName = $input->getOption('harborName');
        }

        if ($input->getOption('harborCity')) {
            $harborCity = $input->getOption('harborCity');
        }

        $newHarbor = new Harbor();
        $newHarbor->setName($harborName);
        $newHarbor->setCity($harborCity);

        $this->entityManager->persist($newHarbor);
        $this->entityManager->flush();

        $io->success('Création du port '.$harborName.' dans la ville '.$harborCity);
        
        return Command::SUCCESS;
    }
}
