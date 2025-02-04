<?php

declare(strict_types=1);

namespace Marcosh\PhpValidationDSL\Basic;

use Marcosh\PhpValidationDSL\Result\ValidationResult;
use Marcosh\PhpValidationDSL\Translator\Translator;
use function is_callable;

/**
 * @template T
 */
abstract class ComposingAssertion
{
    public const MESSAGE = 'composing-assertion.not-as-asserted';

    /**
     * @var callable|null with signature $data -> string[]
     */
    private $errorFormatter;

    public function __construct(?callable $errorFormatter = null)
    {
        $this->errorFormatter = $errorFormatter;
    }

    public static function withFormatter(callable $errorFormatter): self
    {
        return new static($errorFormatter);
    }

    public static function withTranslator(Translator $translator): self
    {
        return new static(
            /**
             * @param mixed $data
             * @psalm-param T $data
             * @return string[]
             * @psalm-return array{0:mixed}
             */
            function ($data) use ($translator): array {
                return [$translator->translate(static::MESSAGE)];
            }
        );
    }

    /**
     * @param mixed $data
     * @psalm-param T $data
     * @param array $context
     * @return ValidationResult
     */
    abstract public function validate($data, array $context = []): ValidationResult;

    /**
     * @param callable $assertion
     * @param mixed $data
     * @psalm-param T $data
     * @param array $context
     * @return ValidationResult
     */
    protected function validateAssertion(callable $assertion, $data, array $context = []): ValidationResult
    {
        return IsAsAsserted::withAssertionAndErrorFormatter(
            $assertion,
            is_callable($this->errorFormatter) ?
                $this->errorFormatter :
                /**
                 * @param mixed $data
                 * @psalm-param T $data
                 * @return string[]
                 * @psalm-return array{0:mixed}
                 */
                function ($data): array {
                    return [static::MESSAGE];
                }
        )->validate($data, $context);
    }
}
