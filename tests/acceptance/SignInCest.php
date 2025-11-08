<?php
use Tests\Support\AcceptanceTester;

class SignInCest
{
    public function signInSuccessfully(AcceptanceTester $I)
    {
        $I->wantTo('Sign in as regular user using reusable helper');
        $I->loginAs('test_user');
        // assert we are on a known landing
        $I->see('Sign Out');
    }
}
