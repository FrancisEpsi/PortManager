<?php

namespace App\Command;

use App\Entity\Harbor;
use App\Repository\HarborRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\Table;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;




class HarborListCommand extends Command
{
    protected static $defaultName = 'harbor:list';
    protected static $defaultDescription = 'Récupère la liste des ports en base de donnée';

    private $harborRepository;
    public function __construct(HarborRepository $harborRepository, string $name = null)
    {
        parent::__construct($name);
        $this->harborRepository = $harborRepository;
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $harbors = $this->harborRepository->findAll();

        $table = new Table($output);
        $table->setHeaders(["Id", "Name", "City"]);
        foreach($harbors as $harbor) {
            $table->addRow([$harbor->getId(),$harbor->getName(), $harbor->getCity()]);
        }
        $table->render();
        $io->success('Tous les ports ont été affichés !');

        return Command::SUCCESS;
    }
}
