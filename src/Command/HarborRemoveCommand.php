<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HarborRemoveCommand extends Command
{
    protected static $defaultName = 'harbor:remove';
    protected static $defaultDescription = 'Supprime un objet port de la base de donnée grâce à son ID';

    protected function configure(): void
    {
        //$this->addArgument('ID', InputArgument::REQUIRED, 'Harbor unique ID');
        $this->addOption('ID', null, InputOption::VALUE_REQUIRED, 'Harbor unique ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$harborID = $input->getArgument('ID');
        $harborID = $input->getOption('ID');
        $io->success("NOUS ALLONS SUPPRIMER L'ID ".$harborID);
        return Command::SUCCESS;

        if ($harborID) {
            $harbor = $this->harborRepository->find($harborID);
            if (!$harbor) {
                $io->error("Le port ayant l'ID ".$harborID." n'existe pas en base de donnée");
                return Command::INVALID;
            }
            try {
                $this->harborRepository->remove($harbor, true);
                $io->success('Le port à été supprimé de la base de donnée');
                return Command::SUCCESS;
            } catch (\Exception $e) {
                $io->error("Une erreur est survenue durant la suppression du port ayant l'ID ".$harborID);
                return Command::FAILURE;
            }

        } else {
            $io->error("Vous n'avez pas spécifié l'identifiant du port à supprimer avec l'argument --ID");
            return Command::INVALID;
        }
    }
}
