<?php

namespace App\Command;

use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test',
)]
class TestCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (null === $data = $this->em->getRepository(Test::class)->findOneBy(['code' => 'test'])) {
            $data = new Test();
            $data->setCode('test');
            $data->setData(['name' => 'test', 'description' => 'lorem ipsum']);
            $this->em->persist($data);
            $this->em->flush();
        }

        $name = $this->em->getRepository(Test::class)->extractName('test');

        $output->writeln([
            "name: {$name}",
            "sql: " . $this->em->getConnection()->getParams()['serverVersion']
        ]);
        
        return Command::SUCCESS;
    }
}
