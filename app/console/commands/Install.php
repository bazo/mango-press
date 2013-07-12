<?php

namespace Console\Command;

use Symfony\Component\Console;

/**
 * Install command
 * @author Martin Bažík <martin@bazo.sk>
 */
class Install extends Console\Command\Command
{

	/** @var \Doctrine\ODM\MongoDB\DocumentManager */
	private $documentManager;
	
	public function injectDocumentManager(\Doctrine\ODM\MongoDB\DocumentManager $documentManager)
	{
		$this->documentManager = $documentManager;
		return $this;
	}


	
	protected function configure()
	{
		$this->setName('app:install')
			 ->setDescription('Installs application');
	}


	protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
	{
		$output->writeln('<info>Installing Commander</info>');
		$application = $this->getApplication();

		$commands = [
			$application->get('odm:schema:drop'),
			$application->get('odm:schema:create'),
			$application->get('odm:generate:hydrators'),
			$application->get('odm:generate:proxies'),
		];

		foreach ($commands as $command) {
			$command->run($input, $output);
		}

		$settings = new \Settings;
		$settings->setBlogName('MangoPress powered blog');
		
		$this->documentManager->persist($settings);
		$this->documentManager->flush();
		
		$output->writeln('<info>Finished</info>');
	}


}

