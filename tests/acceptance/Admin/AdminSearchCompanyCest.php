<?php
use Tests\Support\AcceptanceTester;

class AdminSearchCompanyCest
{
    public function adminCanSearchForCompany(AcceptanceTester $I)
    {
        $I->wantTo('Login as admin and search for a company');

        // Step 1: Login as admin
        $I->loginAs('admin');
        $I->see('DB Backend');

        // Step 2: Search for a company using helper
        $I->searchCompany('Restaurant');

        // Step 3: Verify results appear
        $I->see('Companies');
        $I->see('Restaurant');
    }
}
