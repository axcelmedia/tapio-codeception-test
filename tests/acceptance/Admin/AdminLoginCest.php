<?php
use Tests\Support\AcceptanceTester;

class AdminLoginCest
{
    public function adminCanLoginAndSeeAdminPanel(AcceptanceTester $I)
    {
        $I->wantTo('Login as admin and verify admin elements');
        $I->loginAs('admin');

        // Verify admin-specific UI element
        $I->see('DB Backend');
    }
}
