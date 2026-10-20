<?php

declare(strict_types=1);

namespace Boyarin\Console;

use Boyarin\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonException;
use RuntimeException;

abstract class AddClassifierExampleCommand extends Command
{
    public function __construct(private readonly Config $config)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $message = Str::of(implode(' ', (array) $this->argument('message')))
            ->squish()
            ->toString();

        try {
            $messages = $this->messages();

            if ($messages->containsStrict($message)) {
                $this->components->warn("This {$this->classifier()} message already exists.");

                return self::SUCCESS;
            }

            $this->append($message, $messages->isNotEmpty());
        } catch (JsonException|RuntimeException $error) {
            $this->components->error($error->getMessage());

            return self::FAILURE;
        }

        $this->components->info(ucfirst($this->classifier()).' message added.');

        return self::SUCCESS;
    }

    /**
     * Get the classifier dataset name.
     */
    abstract protected function classifier(): string;

    /**
     * Read the classifier dataset.
     *
     * @return Collection<int, string>
     *
     * @throws JsonException
     * @throws RuntimeException
     */
    private function messages(): Collection
    {
        $path = $this->path();
        $contents = file_get_contents($path);

        throw_if($contents === false, RuntimeException::class, "Unable to read [{$path}].");

        $messages = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

        throw_unless(
            is_array($messages)
                && array_is_list($messages)
                && collect($messages)->every(static fn (mixed $message): bool => is_string($message)),
            RuntimeException::class,
            "Classifier dataset [{$path}] must be a JSON array of strings.",
        );

        return collect($messages);
    }

    /**
     * Append a message without rewriting the existing dataset.
     *
     * @throws JsonException
     * @throws RuntimeException
     */
    private function append(string $message, bool $hasMessages): void
    {
        $path = $this->path();
        $contents = file_get_contents($path);

        throw_if($contents === false, RuntimeException::class, "Unable to read [{$path}].");

        $closingBracket = strrpos($contents, ']');

        throw_if($closingBracket === false, RuntimeException::class, "Classifier dataset [{$path}] is invalid.");

        $contents = rtrim(substr($contents, 0, $closingBracket))
            .($hasMessages ? ',' : '')
            .PHP_EOL.'    '
            .json_encode(
                $message,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
            )
            .PHP_EOL.']'.PHP_EOL;

        throw_if(
            file_put_contents($path, $contents, LOCK_EX) === false,
            RuntimeException::class,
            "Unable to write [{$path}].",
        );
    }

    /**
     * Get the classifier dataset path.
     */
    private function path(): string
    {
        return $this->config->classifiersPath.'/'.$this->classifier().'.json';
    }
}
