<?php

declare(strict_types=1);

use AssistedMindfulness\NaiveBayes\Classifier;
use Boyarin\Config;
use Boyarin\Console\AddHamCommand;
use Boyarin\Console\AddSpamCommand;
use Boyarin\Console\ConsoleContainer;
use Boyarin\Console\RegisterWebhookCommand;
use Boyarin\Console\ServeCommand;
use Boyarin\Console\StartCommand;
use Boyarin\Spam\ClassifierLoader;
use Boyarin\Spam\SpamChecker;
use Boyarin\Spam\SpamDetector;
use Boyarin\Spam\StopWords;
use Boyarin\Spam\TrustedUsers;
use Boyarin\Telegram\TelegramClient;
use Boyarin\Telegram\TelegramGateway;
use Boyarin\Telegram\UpdateHandler;
use Boyarin\Telegram\UpdateProcessor;
use Boyarin\Telegram\WebhookRegistrar;
use Boyarin\Telegram\WebhookServer;
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require __DIR__.'/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();
$config = Config::fromEnvironment(__DIR__);

$container = new ConsoleContainer();
Container::setInstance($container);
$container->instance(Config::class, $config);
$container->instance(Dispatcher::class, new Dispatcher($container));
$container->singleton(LoggerInterface::class, static function (): LoggerInterface {
    $logger = new Logger('boyarin');
    $logger->pushHandler(new StreamHandler('php://stderr', Level::Info));

    return $logger;
});
$container->singleton(ClassifierLoader::class, static fn () => new ClassifierLoader(
    $config->classifiersPath,
));
$container->singleton(Classifier::class, static fn (Container $container) =>
    $container->make(ClassifierLoader::class)->load());
$container->singleton(StopWords::class, static fn () => new StopWords(
    $config->stopWordsPath,
));
$container->singleton(SpamDetector::class);
$container->bind(SpamChecker::class, SpamDetector::class);
$container->singleton(TrustedUsers::class, static fn (Container $container) => new TrustedUsers(
    $config->trustedUsersPath,
    $container->make(LoggerInterface::class),
));
$container->singleton(TelegramClient::class);
$container->bind(TelegramGateway::class, TelegramClient::class);
$container->singleton(UpdateProcessor::class);
$container->bind(UpdateHandler::class, UpdateProcessor::class);
$container->singleton(WebhookRegistrar::class);
$container->singleton(WebhookServer::class);
$container->bind(AddHamCommand::class);
$container->bind(AddSpamCommand::class);
$container->bind(RegisterWebhookCommand::class);
$container->bind(ServeCommand::class);
$container->bind(StartCommand::class);

return $container;
