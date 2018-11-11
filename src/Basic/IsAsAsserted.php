<?php

declare(strict_types=1);

namespace Marcosh\PhpValidationDSL\Basic;

use Marcosh\PhpValidationDSL\Result\ValidationResult;
use Marcosh\PhpValidationDSL\Validation;
use function is_callable;

final class IsAsAsserted implements Validation
{
    public const NOT_AS_ASSERTED = 'is-as-asserted.not-as-asserted';

    /**
     * @var callable $data -> bool
     */
    private $assertion;

    /**
     * @var callable $data -> string[]
     */
    private $errorFormatter;

    private function __construct(callable $assertion, ?callable $errorFormatter = null)
    {
        $this->assertion = $assertion;
        $this->errorFormatter = is_callable($errorFormatter) ?
            $errorFormatter :
            function ($data) {
                return [self::NOT_AS_ASSERTED];
            };
    }

    public static function withAssertion(callable $assertion): self
    {
        return new self($assertion);
    }

    public static function withAssertionAndErrorFormatter(callable $assertion, callable $errorFormatter): self
    {
        return new self($assertion, $errorFormatter);
    }

    public function validate($data, array $context = []): ValidationResult
    {
        if (! ($this->assertion)($data)) {
            return ValidationResult::errors(($this->errorFormatter)($data));
        }

        return ValidationResult::valid($data);
    }
}
