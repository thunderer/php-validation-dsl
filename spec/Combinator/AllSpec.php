<?php

declare(strict_types=1);

namespace Marcosh\PhpValidationDSLSpec\Combinator;

use Marcosh\PhpValidationDSL\Basic\IsBool;
use Marcosh\PhpValidationDSL\Basic\IsString;
use Marcosh\PhpValidationDSL\Basic\Regex;
use Marcosh\PhpValidationDSL\Combinator\All;
use Marcosh\PhpValidationDSL\Result\ValidationResult;

describe('All', function () {
    it('returns a valid result in every case if it does not contain any validator', function () {
        $all = All::validations([]);

        expect($all->validate('gigi'))->toEqual(ValidationResult::valid('gigi'));
    });

    it('returns a valid result if every validator succeeds', function () {
        $all = All::validations([
            new IsString(),
            Regex::withPattern('/^[\p{L} ]*$/u')
        ]);

        expect($all->validate('gigi'))->toEqual(ValidationResult::valid('gigi'));
    });

    it('returns an error result if one validator fails with all the errors combined', function () {
        $all = All::validations([
            new IsString(),
            new IsBool()
        ]);

        expect($all->validate(42))->toEqual(ValidationResult::errors([
            IsString::NOT_A_STRING,
            IsBool::NOT_A_BOOL
        ]));
    });
});