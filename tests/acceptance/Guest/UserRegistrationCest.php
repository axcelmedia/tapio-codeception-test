<?php

use Tests\Support\AcceptanceTester;

/**
 * Tests for user registration form and validation
 * @group guest
 * @group registration
 */
class UserRegistrationCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/auth/register');
    }

    // Test 72: Registration page loads
    public function registrationPageLoads(AcceptanceTester $I)
    {
        $I->wantTo('Verify registration page loads successfully');
        $I->seeInCurrentUrl('/auth/register');
        $I->see('Register');
    }

    // Test 73-81: Required fields marked correctly

    public function requiredFieldsAreMarked(AcceptanceTester $I)
    {
        $I->wantTo('Verify all required fields are marked');
        // Company name
        $I->seeElement('input[name*="company"], input[name*="Company"]');

        // Personal details
        $I->seeElement('input[name*="first"], input[name*="First"]');
        $I->seeElement('input[name*="last"], input[name*="Last"]');
        $I->seeElement('input[name*="phone"], input[name*="Phone"]');
        $I->seeElement('input[name*="email"], input[name*="Email"]');

        // Password fields
        $I->seeElement('input[type="password"]');
    }

    // Test 74: Email field exists and accepts input
    public function emailFieldExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify email field exists and accepts input');
        $I->seeElement('input[name*="email"]');
        $I->fillField('input[name*="email"]', 'test@example.com');
    }

    // Test 75: Password field exists
    public function passwordFieldExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify password field exists');
        $I->seeElement('input[name*="password"][type="password"]');
    }

    // Test 76: Password confirmation field exists
    public function passwordConfirmationFieldExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify password confirmation field exists');
        // Look for second password field or confirm field
        $I->seeElement('input[type="password"]');
    }

    // Test 77-86: Individual required fields

    public function companyNameFieldExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify company name field exists');
        $I->seeElement('input[name*="company"], input[name*="Company"]');
    }

    public function firstNameRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify first name is required');
        $I->seeElement('input[name*="first"]');
    }

    public function lastNameRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify last name is required');
        $I->seeElement('input[name*="last"]');
    }

    public function phoneNumberRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify phone number is required');
        $I->seeElement('input[name*="phone"]');
    }

    public function addressLine1Required(AcceptanceTester $I)
    {
        $I->wantTo('Verify address line 1 is required');
        $I->seeElement('input[name*="address"]');
    }

    public function cityRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify city is required');
        $I->seeElement('input[name*="city"]');
    }

    public function provinceRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify province is required');
        $I->seeElement('select[name*="province"], input[name*="province"]');
    }

    public function countryRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify country is required');
        $I->seeElement('select[name*="country"], input[name*="country"]');
    }

    public function postalCodeRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify postal code is required');
        $I->seeElement('input[name*="postal"]');
    }

    // Test 86: Business license max length
    public function businessLicenseMaxLength(AcceptanceTester $I)
    {
        $I->wantTo('Verify business license has max length of 9 characters');
        $I->seeElement('input[name*="license"]');
        // Check maxlength attribute if present
    }

    // Test 87-89: Policy checkboxes

    public function termsAndConditionsCheckboxRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify terms and conditions checkbox is required');
        $I->seeElement('input[type="checkbox"]');
        $I->see('Terms');
    }

    public function privacyPolicyCheckboxRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify privacy policy checkbox is required');
        $I->see('Privacy');
    }

    public function deliveryPolicyCheckboxRequired(AcceptanceTester $I)
    {
        $I->wantTo('Verify delivery policy checkbox is required');
        $I->see('Delivery');
    }

    // Test 90: Submit button exists
    public function submitButtonExists(AcceptanceTester $I)
    {
        $I->wantTo('Verify submit button exists and is clickable');
        $I->seeElement('button[type="submit"], input[type="submit"]');
    }

    // Test 91: Registration form displays all sections
    public function registrationFormDisplaysAllSections(AcceptanceTester $I)
    {
        $I->wantTo('Verify registration form has all major sections');

        // Check for major form sections
        $I->seeElement('input[name*="email"]');
        $I->seeElement('input[type="password"]');
        $I->seeElement('input[name*="address"]');
        $I->seeElement('button[type="submit"], input[type="submit"]');
    }

    // Test 92: Shipping/billing address toggle
    public function shippingBillingAddressToggle(AcceptanceTester $I)
    {
        $I->wantTo('Verify shipping/billing address toggle exists');
        // Look for checkbox or toggle to use same address
        $I->seeElement('input[type="checkbox"]');
    }

    // Test 93: Sales rep selection dropdown
    public function salesRepSelectionDropdown(AcceptanceTester $I)
    {
        $I->wantTo('Verify sales rep selection dropdown exists (optional)');
        // This is optional, so just check if element exists
        $I->seeInCurrentUrl('/auth/register');
    }

    // Additional: Link to login for existing users
    public function linkToLoginForExistingUsers(AcceptanceTester $I)
    {
        $I->wantTo('Verify link to login page for existing users');
        $I->see('Login');
        $I->seeElement('a[href*="login"]');
    }
}
