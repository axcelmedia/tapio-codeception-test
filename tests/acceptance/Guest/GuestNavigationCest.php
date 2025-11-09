<?php

use Tests\Support\AcceptanceTester;

/**
 * Tests for guest user navigation and homepage elements
 * @group guest
 * @group navigation
 */
class GuestNavigationCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/');
    }

    // Test 1: Homepage loads successfully
    public function homepageLoadsSuccessfully(AcceptanceTester $I)
    {
        $I->wantTo('Verify homepage loads with correct title');
        $I->seeInTitle('Home');
    }

    // Test 1b: Guest should NOT see "DB Backend" (admin interface)
    public function guestShouldNotSeeDbBackend(AcceptanceTester $I)
    {
        $I->wantTo('Verify guest users do not see DB Backend text');
        $I->dontSee('DB Backend');
    }

    // Test 2: Main navigation - Food Packaging
    public function foodPackagingNavLinkExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify Food Packaging nav link exists');
        $I->see('Food Packaging');
    }

    // Test 3: Main navigation - Beverage Supply
    public function beverageSupplyNavLinkExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify Beverage Supply nav link exists');
        $I->see('Beverage Supply');
    }

    // Test 4: Main navigation - Other
    public function otherNavLinkExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify Other category nav link exists');
        $I->see('Other');
    }

    // Test 5: Footer - FAQ link
    public function faqFooterLinkWorks(AcceptanceTester $I)
    {
        $I->wantTo('Verify FAQ link in footer works');
        $I->click('FAQ');
        $I->seeCurrentUrlEquals('/site/faq');
    }

    // Test 6: Footer - Contact link
    public function contactFooterLinkWorks(AcceptanceTester $I)
    {
        $I->wantTo('Verify Contact link in footer works');
        $I->click('Contact');
        $I->seeCurrentUrlEquals('/site/contact');
    }

    // Test 7: Header displays phone number
    public function headerDisplaysPhoneNumber(AcceptanceTester $I)
    {
        $I->wantTo('Verify header displays correct phone number');
        $I->see('604-270-8687');
    }

    // Test 8: Header displays email
    public function headerDisplaysEmail(AcceptanceTester $I)
    {
        $I->wantTo('Verify header displays correct email address');
        $I->see('order@ttcompany.ca');
    }

    // Additional: Footer attribution
    public function footerShowsRestoboxAttribution(AcceptanceTester $I)
    {
        $I->wantTo('Verify footer shows Powered by restobox.com');
        $I->see('restobox.com');
    }
}
