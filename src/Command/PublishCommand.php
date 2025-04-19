<?php

namespace App\Command;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends Command
{
    protected static $defaultName = 'rabbitmq:publish';

    protected function configure()
    {
        $this
            ->setDescription('Отправить message в rabbitMQ')
            ->setHelp('Тестовая отправка сообщения в rabbitMQ');
    }

    /** php bin/console rabbitmq:publish
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        // Создаем очередь
        $channel->queue_declare('hello', false, true, false, false);
        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');
        echo " [x] Sent 'Hello World!'\n";

        $channel->close();
        $connection->close();


        return Command::SUCCESS;
    }
}
