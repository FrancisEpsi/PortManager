<?php

namespace App\Command;

use App\Entity\Harbor;
use App\Repository\HarborRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\Table;
use Exception;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HarborEditCommand extends Command
{
    protected static $defaultName = 'harbor:edit';
    protected static $defaultDescription = 'Modifier un objet port déjà existant';

    private $harborID=null;

    private $harborRepository;
    private $entityManager;
    public function __construct(HarborRepository $harborRepository, EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        $this->harborRepository = $harborRepository;
        $this->entityManager = $entityManager;
    }
    protected function configure(): void
    {
        $this
            ->addOption('ID', null, InputOption::VALUE_OPTIONAL, "L'identifiant unique du port")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('ID')) {
            $this->harborID=$input->getOption('ID');
        }

        $toEdit = null;
        if (!$this->harborID == null) {
            $toEdit = $this->harborRepository->find($this->harborID);
        } else {
            $io->error("Vous n'avez pas fourni assez d'options pour la commande. Veuillez fournir l'option --ID.");
            return Command::INVALID;
        }

        if ($toEdit == null) {
            $io->error("Le port ayant l'ID ".$this->harborID." n'a pas été trouvé !");
            return Command::INVALID;
        }

        $newName=$io->ask("Nom du port : ", $toEdit->getName());
        $newCity=$io->ask("Nom de la ville : ", $toEdit->getCity());

        $toEdit->setName($newName);
        $toEdit->setCity($newCity);

            $this->entityManager->persist($toEdit);
            $this->entityManager->flush();

        $io->success('Modification effectuée');
        return Command::SUCCESS;
    }
}
