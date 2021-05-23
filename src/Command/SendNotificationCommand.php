<?php
namespace Oka\NotifierBundle\Command;

use Oka\NotifierBundle\Notification\Address;
use Oka\NotifierBundle\Notification\Notification;
use Oka\NotifierBundle\Notification\Notifier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class SendNotificationCommand extends Command
{
	protected static $defaultName = 'oka:notifier:send-notification';
	
	private $notifier;
	
	public function __construct(Notifier $notifier)
	{
		parent::__construct();
		
		$this->notifier = $notifier;
	}
	
	protected function configure()
	{
		$this
			->setDescription('Sends notification.')
			->setHelp('This command allows you to send a notification.')
			->addOption('channels', 'c', InputOption::VALUE_IS_ARRAY|InputOption::VALUE_REQUIRED, 'The channels on which the notification was sent.')
			->addOption('sender', 's', InputOption::VALUE_REQUIRED, 'The address of the sender for whom the notification is sent.')
			->addOption('receiver', 'r', InputOption::VALUE_REQUIRED, 'The address of the receiver who will receive the notification.')
			->addOption('title', 't', InputOption::VALUE_OPTIONAL, 'The notification title to send.')
			->addArgument('message', InputArgument::REQUIRED, 'The notification message to send.');
	}
	
	protected function interact(InputInterface $input, OutputInterface $output)
	{
		if (!$input->getArgument('username')) {
			$question = new Question('Please choose a username:');
			$question->setValidator(function($username){
				if (true === empty($username)) {
					throw new \Exception('Username can not be empty');
				}
				
				return $username;
			});
				
				$answer = $this->getHelper('question')->ask($input, $output, $question);
				$input->setArgument('username', $answer);
		}
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->notifier->send(new Notification(
			$input->getOption('channels'), 
			Address::create($input->getOption('sender')),
			Address::create($input->getOption('receiver')),
			$input->getArgument('message')
		), true);
		
		return 0;
	}
}
