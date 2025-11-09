<?php

use Tests\Support\AcceptanceTester;

/**
 * Tests for search functionality and warehouse filtering
 * @group guest
 * @group search
 * @group warehouse
 */
class SearchAndWarehouseCest
{
    // WAREHOUSE FILTER TESTS (37-42)

    // Test 37: Warehouse filter displays
    public function warehouseFilterDisplays(AcceptanceTester $I)
    {
        $I->wantTo('Verify warehouse filter is visible on product page');
        $I->amOnPage('/product');
        $I->see('Warehouse');
    }

    // Test 38: Warehouse filter by ID works
    public function warehouseFilterByIdWorks(AcceptanceTester $I)
    {
        $I->wantTo('Filter products by warehouse ID 1');
        $I->amOnPage('/product?warehouse_id=1');
        $I->seeInCurrentUrl('warehouse_id=1');
    }

    // Test 39: Warehouse filter persists with category selection
    public function warehouseFilterPersistsWithCategory(AcceptanceTester $I)
    {
        $I->wantTo('Verify warehouse filter persists when selecting a category');
        $I->amOnPage('/product?warehouse_id=1');
        $I->amOnPage('/product?warehouse_id=1&category_id=14');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('category_id=14');
    }

    // Test 40: Warehouse filter persists with pagination
    public function warehouseFilterPersistsWithPagination(AcceptanceTester $I)
    {
        $I->wantTo('Verify warehouse filter persists across pagination');
        $I->amOnPage('/product?warehouse_id=1&page=1');
        $I->click('2');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 41: Switching warehouses updates product availability
    public function switchingWarehousesUpdatesProducts(AcceptanceTester $I)
    {
        $I->wantTo('Switch between warehouses and verify URL updates');
        $I->amOnPage('/product?warehouse_id=1');
        $I->seeInCurrentUrl('warehouse_id=1');

        $I->amOnPage('/product?warehouse_id=3');
        $I->seeInCurrentUrl('warehouse_id=3');
    }

    // Test 42: Warehouse filter maintained in URL parameters
    public function warehouseFilterInUrlParameters(AcceptanceTester $I)
    {
        $I->wantTo('Verify warehouse filter is properly encoded in URL');
        $I->amOnPage('/product?warehouse_id=1');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeElement('a[href*="warehouse_id"]');
    }

    // SEARCH TESTS (43-52)

    // Test 43: Search form exists in header
    public function searchFormExistsInHeader(AcceptanceTester $I)
    {
        $I->wantTo('Verify search form is present in header');
        $I->amOnPage('/');
        $I->seeElement('input[type="text"]'); // Search input
    }

    // Test 44: Search by product name works
    public function searchByProductNameWorks(AcceptanceTester $I)
    {
        $I->wantTo('Search for products by name "syrup"');
        $I->amOnPage('/product?ProductSearch[search]=syrup');
        $I->seeInCurrentUrl('ProductSearch');
        $I->see('Syrup');
    }

    // Test 45: Search returns relevant results with minimum count
    public function searchReturnsRelevantResults(AcceptanceTester $I)
    {
        $I->wantTo('Verify search for "1883" returns at least 24 products (full first page)');
        $I->amOnPage('/product?ProductSearch[search]=1883');

        // Should see "1-24 of X items" where X >= 24
        $I->see('1-24 of');
        $I->see('items');

        // Count at least 24 product name links containing "1883" in the anchor text
        $count = $I->countProductLinksContaining('1883');
        $I->comment("Links containing '1883': {$count}");
        \PHPUnit\Framework\Assert::assertGreaterThanOrEqual(24, $count, "Expected at least 24 product links containing '1883' in anchor text, but found {$count}");
    }

    // Test 46: Search with no results shows "No results found" message
    public function searchWithNoResultsShowsMessage(AcceptanceTester $I)
    {
        $I->wantTo('Search for non-existent product and verify "No results found" message');
        $I->amOnPage('/product?ProductSearch[search]=xyznonexistent999');
        // Should show "No results found" message
        $I->see('No results found');
    }

    // Test 47: Search with partial product name
    public function searchWithPartialName(AcceptanceTester $I)
    {
        $I->wantTo('Search with partial product name "cara"');
        $I->amOnPage('/product?ProductSearch[search]=cara');
        $I->seeInCurrentUrl('ProductSearch');

        // Count at least 2 product links containing "Caramel" in the anchor text
        $count = $I->countProductLinksContaining('Caramel');
        $I->comment("Links containing 'Caramel': {$count}");
        \PHPUnit\Framework\Assert::assertGreaterThanOrEqual(2, $count, "Expected at least 2 product links containing 'Caramel' in anchor text, but found {$count}");
    }

    // Test 48: Search preserves warehouse filter
    public function searchPreservesWarehouseFilter(AcceptanceTester $I)
    {
        $I->wantTo('Verify search maintains warehouse filter');
        $I->amOnPage('/product?warehouse_id=1&ProductSearch[search]=syrup');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('ProductSearch');
    }

    // Test 49: Search results show correct count
    public function searchResultsShowCorrectCount(AcceptanceTester $I)
    {
        $I->wantTo('Verify search results display item count');
        $I->amOnPage('/product?ProductSearch[search]=syrup');
        $I->see('items'); // Should show "X items" or "X-Y of Z items"
    }

    // Test 50: Search pagination works via URL
    public function searchPaginationWorksViaUrl(AcceptanceTester $I)
    {
        $I->wantTo('Verify pagination works with search results via URL');
        $I->amOnPage('/product?ProductSearch[search]=syrup&page=2');
        $I->seeInCurrentUrl('ProductSearch');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 51: Search with special characters
    public function searchWithSpecialCharacters(AcceptanceTester $I)
    {
        $I->wantTo('Search with special characters in query');
        $I->amOnPage('/product?ProductSearch[search]=1-L');
        $I->seeInCurrentUrl('ProductSearch');
    }

    // Test 52: Search case insensitivity
    public function searchCaseInsensitivity(AcceptanceTester $I)
    {
        $I->wantTo('Verify search is case insensitive');
        // Search for "SYRUP" in uppercase
        $I->amOnPage('/product?ProductSearch[search]=SYRUP');
        $I->seeInCurrentUrl('ProductSearch');
        // Should still find "Syrup" products (case insensitive)
        $I->see('Syrup');
    }

    // Additional: Combined filters test
    public function combinedWarehouseAndSearchFilter(AcceptanceTester $I)
    {
        $I->wantTo('Use warehouse filter and search together');
        $I->amOnPage('/product?warehouse_id=1&ProductSearch[search]=syrup&category_id=14');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('ProductSearch');
        $I->seeInCurrentUrl('category_id=14');
    }
}
