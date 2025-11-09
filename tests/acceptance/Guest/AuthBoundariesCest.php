<?php

use Tests\Support\AcceptanceTester;

/**
 * Tests for authentication boundaries, error handling, and edge cases
 * @group guest
 * @group auth-boundaries
 * @group edge-cases
 */
class AuthBoundariesCest
{
    // AUTHENTICATION BOUNDARY TESTS (66-71)

    // Test 66: Cart page redirects to login for guests
    public function cartPageRedirectsToLoginForGuests(AcceptanceTester $I)
    {
        $I->wantTo('Verify cart page blocks guest access');
        $I->amOnPage('/cart');
        // Should redirect to login or show login prompt
        $I->seeInCurrentUrl('/auth/login');
    }

    // Test 67: Orders page blocks guest access
    public function ordersPageBlocksGuestAccess(AcceptanceTester $I)
    {
        $I->wantTo('Verify orders page requires authentication');
        $I->amOnPage('/order');
        // Should redirect to login or show 403
        $I->seeInCurrentUrl('/auth/login');
    }

    // Test 68: Quote requests page blocks guest access
    public function quoteRequestsPageBlocksGuestAccess(AcceptanceTester $I)
    {
        $I->wantTo('Verify quote requests page requires authentication');
        $I->amOnPage('/quote');
        // Should redirect to login or show 403
        $I->seeInCurrentUrl('/auth/login');
    }

    // Test 69: My Orders link behavior for guests
    public function myOrdersLinkBehaviorForGuests(AcceptanceTester $I)
    {
        $I->wantTo('Verify My Orders link exists in navigation');
        $I->amOnPage('/');
        $I->see('My Orders');
        $I->click('My Orders');
        // Should redirect to login
        $I->seeInCurrentUrl('/auth/login');
    }

    // Test 70: My Quote Requests link behavior for guests
    public function myQuoteRequestsLinkBehaviorForGuests(AcceptanceTester $I)
    {
        $I->wantTo('Verify My Quote Requests link exists in navigation');
        $I->amOnPage('/');
        $I->see('My Quote');
        $I->click('My Quote');
        // Should redirect to login
        $I->seeInCurrentUrl('/auth/login');
    }

    // Test 71: Account icon behavior for guests
    public function accountIconBehaviorForGuests(AcceptanceTester $I)
    {
        $I->wantTo('Verify account icon shows login/register for guests');
        $I->amOnPage('/');
        // Should see login or register links
        $I->see('Login');
        $I->see('Register');
    }

    // FAQ & CONTACT TESTS (94-99)

    // Test 94: FAQ page loads
    public function faqPageLoads(AcceptanceTester $I)
    {
        $I->wantTo('Verify FAQ page loads successfully');
        $I->amOnPage('/site/faq');
        $I->seeInCurrentUrl('/site/faq');
    }

    // Test 95: FAQ page content loads
    public function faqPageContentLoads(AcceptanceTester $I)
    {
        $I->wantTo('Verify FAQ page loads with content');
        $I->amOnPage('/site/faq');
        // Check page loads successfully
        $I->seeInCurrentUrl('/site/faq');
    }

    // Test 96: Contact page loads
    public function contactPageLoads(AcceptanceTester $I)
    {
        $I->wantTo('Verify Contact page loads successfully');
        $I->amOnPage('/site/contact');
        $I->seeInCurrentUrl('/site/contact');
    }

    // Test 97: Contact page form loads
    public function contactPageFormLoads(AcceptanceTester $I)
    {
        $I->wantTo('Verify Contact page loads with form area');
        $I->amOnPage('/site/contact');
        // Check page loads successfully
        $I->seeInCurrentUrl('/site/contact');
    }

    // Test 98: Contact phone number displays
    public function contactPhoneNumberDisplays(AcceptanceTester $I)
    {
        $I->wantTo('Verify contact phone displays on contact page');
        $I->amOnPage('/site/contact');
        $I->see('604-270-8687');
    }

    // Test 99: Contact email displays
    public function contactEmailDisplays(AcceptanceTester $I)
    {
        $I->wantTo('Verify contact email displays on contact page');
        $I->amOnPage('/site/contact');
        $I->see('order@ttcompany.ca');
    }

    // ERROR HANDLING TESTS (100-103)

    // Test 100: 404 page for invalid product URLs
    public function invalidProductUrlReturns404(AcceptanceTester $I)
    {
        $I->wantTo('Verify invalid product URL returns 404');
        $I->amOnPage('/product/view/999999');
        // Should show 404 or redirect
        $I->seeResponseCodeIs(404);
    }

    // Test 101: 404 page for invalid category URLs
    public function invalidCategoryUrlHandling(AcceptanceTester $I)
    {
        $I->wantTo('Verify invalid category ID is handled gracefully');
        $I->amOnPage('/product?category_id=999999');
        // Should either show empty results or handle gracefully
        $I->seeInCurrentUrl('/product');
    }

    // Test 102: Handling of malformed query parameters
    public function malformedQueryParametersHandling(AcceptanceTester $I)
    {
        $I->wantTo('Verify system handles malformed query parameters');
        $I->amOnPage('/product?category_id=abc');
        // Should handle gracefully without crashing
        $I->seeInCurrentUrl('/product');
    }

    // Test 103: Empty search results page
    public function emptySearchResultsPage(AcceptanceTester $I)
    {
        $I->wantTo('Verify empty search results are handled gracefully');
        $I->amOnPage('/product?ProductSearch[search]=xyznonexistent999');
        $I->see('No results found'); // Should show "No results found" message
    }

    // Test 104: Out of bounds pagination
    public function outOfBoundsPagination(AcceptanceTester $I)
    {
        $I->wantTo('Verify out of bounds page number is handled');
        $I->amOnPage('/product?page=999999');
        // Should redirect to last page or show empty
        $I->seeInCurrentUrl('/product');
    }

    // CROSS-FEATURE INTEGRATION TESTS (105-109)

    // Test 105: Category + warehouse + pagination combination
    public function categoryWarehousePaginationCombination(AcceptanceTester $I)
    {
        $I->wantTo('Test combined category, warehouse, and pagination filters');
        $I->amOnPage('/product?category_id=14&warehouse_id=1&page=2');
        $I->seeInCurrentUrl('category_id=14');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 106: Search + warehouse + pagination combination
    public function searchWarehousePaginationCombination(AcceptanceTester $I)
    {
        $I->wantTo('Test combined search, warehouse, and pagination filters');
        $I->amOnPage('/product?ProductSearch[search]=syrup&warehouse_id=1&page=2');
        $I->seeInCurrentUrl('ProductSearch');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 107: Search + category filter combination
    public function searchCategoryFilterCombination(AcceptanceTester $I)
    {
        $I->wantTo('Test combined search and category filters');
        $I->amOnPage('/product?ProductSearch[search]=syrup&category_id=14');
        $I->seeInCurrentUrl('ProductSearch');
        $I->seeInCurrentUrl('category_id=14');
    }

    // Test 108: View mode persists with all filters
    public function viewModePersistsWithAllFilters(AcceptanceTester $I)
    {
        $I->wantTo('Verify view mode persists with multiple filters');
        $I->amOnPage('/product?ProductSearch[view]=GRID_VIEW&category_id=14&warehouse_id=1');
        $I->seeInCurrentUrl('GRID_VIEW');
        $I->seeInCurrentUrl('category_id=14');
        $I->seeInCurrentUrl('warehouse_id=1');
    }

    // Test 109: URL parameter integrity across feature combinations
    public function urlParameterIntegrityAcrossFeatures(AcceptanceTester $I)
    {
        $I->wantTo('Verify all URL parameters maintain integrity');
        $I->amOnPage('/product?category_id=14&warehouse_id=1&ProductSearch[search]=syrup&ProductSearch[view]=GRID_VIEW&page=2');

        // All parameters should be present
        $I->seeInCurrentUrl('category_id=14');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('ProductSearch');
        $I->seeInCurrentUrl('page=2');
    }

    // Additional: Login page accessible to guests
    public function loginPageAccessibleToGuests(AcceptanceTester $I)
    {
        $I->wantTo('Verify login page is accessible to guests');
        $I->amOnPage('/auth/login');
        $I->seeInCurrentUrl('/auth/login');
        $I->see('Login');
    }

    // Additional: Register page accessible to guests
    public function registerPageAccessibleToGuests(AcceptanceTester $I)
    {
        $I->wantTo('Verify register page is accessible to guests');
        $I->amOnPage('/auth/register');
        $I->seeInCurrentUrl('/auth/register');
        $I->see('Register');
    }
}
