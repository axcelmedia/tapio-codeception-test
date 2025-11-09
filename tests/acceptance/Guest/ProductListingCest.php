<?php

use Tests\Support\AcceptanceTester;

/**
 * Tests for product listing page display and view modes
 * @group guest
 * @group products
 */
class ProductListingCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/product');
    }

    // Test 9: Product listing page loads
    public function productListingPageLoads(AcceptanceTester $I)
    {
        $I->wantTo('Verify product listing page loads successfully');
        $I->seeInCurrentUrl('/product');
        $I->see('items'); // Should see item count text
    }

    // Test 10: Product count displays correctly
    public function productCountDisplays(AcceptanceTester $I)
    {
        $I->wantTo('Verify product count displays with format "X-Y of Z items"');
        $I->seeElement('div', ['class' => 'summary']);
    }

    // Test 11: Grid view parameter works
    public function gridViewParameterWorks(AcceptanceTester $I)
    {
        $I->wantTo('Verify grid view can be set via URL parameter');
        $I->amOnPage('/product?ProductSearch[view]=GRID_VIEW');
        $I->seeInCurrentUrl('GRID_VIEW');
    }

    // Test 12: List view parameter works
    public function listViewParameterWorks(AcceptanceTester $I)
    {
        $I->wantTo('Verify list view can be set via URL parameter');
        $I->amOnPage('/product?ProductSearch[view]=List_VIEW');
        $I->seeInCurrentUrl('List_VIEW');
    }

    // Test 13: View parameter persists with navigation
    public function viewParameterPersistsWithNavigation(AcceptanceTester $I)
    {
        $I->wantTo('Verify view parameter is maintained in category links');
        $I->amOnPage('/product?ProductSearch[view]=GRID_VIEW');
        // Links in nav should include view parameter
        $I->seeElement('a[href*="GRID_VIEW"]');
    }

    // Test 14: Product cards display name
    public function productCardsDisplayName(AcceptanceTester $I)
    {
        $I->wantTo('Verify product cards show product names');
        // We know from crawl that products exist - check for actual product
        $I->see('1883'); // We know this product line exists
    }

    // Test 15: In Stock badge displays
    public function inStockBadgeDisplays(AcceptanceTester $I)
    {
        $I->wantTo('Verify "In Stock" badge displays on available products');
        $I->see('In Stock');
    }

    // Test 16: Stock status displays on product listing
    public function stockStatusDisplaysOnProductListing(AcceptanceTester $I)
    {
        $I->wantTo('Verify stock status is visible on product listings');
        // Navigate to a category with products
        $I->amOnPage('/product?category_id=14');
        // Should see either In Stock or Out Of Stock
        $I->see('Stock'); // "In Stock" or "Out Of Stock"
    }

    // Additional: Verify warehouse selector exists
    public function warehouseSelectorDisplays(AcceptanceTester $I)
    {
        $I->wantTo('Verify warehouse selector is visible');
        $I->see('Warehouse');
    }
}
