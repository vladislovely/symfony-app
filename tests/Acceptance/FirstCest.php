<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class FirstCest
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome to Symfony 6.3.1');
    }
}
