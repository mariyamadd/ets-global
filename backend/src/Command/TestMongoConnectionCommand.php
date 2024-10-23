<?php

namespace App\Command;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestMongoConnectionCommand extends Command
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        // Set the name of the command here
        $this->setName('app:test-mongo-connection')
             ->setDescription('Test MongoDB connection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // Test MongoDB connection
            $db = $this->documentManager->getDocumentDatabase(User::class);
            $collections = $db->listCollections();

            $output->writeln('MongoDB connected successfully.');
            $output->writeln('Collections:');
            foreach ($collections as $collection) {
                $output->writeln($collection->getName());
            }
        } catch (\Exception $e) {
            $output->writeln('Failed to connect to MongoDB: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
