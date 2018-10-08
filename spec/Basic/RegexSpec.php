<?php

declare(strict_types=1);

namespace Marcosh\PhpValidationDSLSpec\Basic;

use Marcosh\PhpValidationDSL\Basic\Regex;
use Marcosh\PhpValidationDSL\Result\ValidationResult;

describe('Regex', function () {
    $regex = Regex::withPattern('/^[\p{L} ]*$/u');

    it('returns a valid result if the pattern has a match', function () use ($regex) {
        expect($regex->validate('gigi'))->toEqual(ValidationResult::valid('gigi'));
    });

    it('returns an error result if the pattern does not have a match', function () use ($regex) {
        expect($regex->validate('gigi@zucon'))->toEqual(ValidationResult::errors([Regex::MATCH_FAILED]));
    });
});