<?php

declare(strict_types=1);

namespace Boyarin\Spam;

use AssistedMindfulness\NaiveBayes\Classifier;
use RuntimeException;

final class ClassifierLoader
{
    private ?Classifier $classifier = null;

    public function __construct(private readonly string $path) {}

    /**
     * Load and train the classifier once for the lifetime of the process.
     *
     * @throws RuntimeException
     */
    public function load(): Classifier
    {
        if ($this->classifier !== null) {
            return $this->classifier;
        }

        $classifier = new Classifier();
        $classifier->uneven();

        $this->train($classifier, 'spam.json', SpamDetector::SPAM);
        $this->train($classifier, 'ham.json', SpamDetector::HAM);

        return $this->classifier = $classifier;
    }

    /**
     * Train the classifier with every message in the given dataset.
     *
     * @throws RuntimeException
     */
    private function train(Classifier $classifier, string $file, string $label): void
    {
        $path = $this->path.'/'.$file;
        $messages = json_decode((string) @file_get_contents($path), true);

        throw_unless(
            is_array($messages),
            RuntimeException::class,
            "Unable to read classifier dataset: {$path}",
        );

        collect($messages)->each(
            static fn (mixed $message) => $classifier->learn((string) $message, $label),
        );
    }
}
