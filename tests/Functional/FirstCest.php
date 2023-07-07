<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;

class FirstCest
{
    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome to Symfony 6.3.1', 'h1');
    }
}
