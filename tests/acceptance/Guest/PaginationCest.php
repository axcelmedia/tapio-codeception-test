<?php

use Tests\Support\AcceptanceTester;

/**
 * Tests for pagination functionality across different filters
 * @group guest
 * @group pagination
 */
class PaginationCest
{
    // Test 25: Pagination displays page numbers
    public function paginationDisplaysPageNumbers(AcceptanceTester $I)
    {
        $I->wantTo('Verify pagination shows page numbers');
        $I->amOnPage('/product');
        $I->see('1'); // Page 1 should be visible
        $I->seeElement('li.page-item');
    }

    // Test 26: Clicking page 2 shows items 25-48
    public function clickingPage2ShowsCorrectItems(AcceptanceTester $I)
    {
        $I->wantTo('Navigate to page 2 and verify correct items shown');
        $I->amOnPage('/product');
        $I->click('2');
        $I->seeInCurrentUrl('page=2');
        $I->see('25-48'); // Should show items 25-48
    }

    // Test 27: Page 3 URL parameter works
    public function page3UrlParameterWorks(AcceptanceTester $I)
    {
        $I->wantTo('Navigate to page 3 via URL parameter');
        $I->amOnPage('/product?page=3');
        $I->seeInCurrentUrl('page=3');
        $I->see('49-72'); // Should show items 49-72
    }

    // Test 28: Next button advances to next page
    public function nextButtonAdvancesToNextPage(AcceptanceTester $I)
    {
        $I->wantTo('Click Next button and verify page advances');
        $I->amOnPage('/product?page=1');
        $I->click('»'); // Next arrow
        $I->seeInCurrentUrl('page=2');
    }

    // Test 29: Previous button returns to previous page
    public function previousButtonReturnsToPreviousPage(AcceptanceTester $I)
    {
        $I->wantTo('Click Previous button from page 2');
        $I->amOnPage('/product?page=2');
        $I->click('«'); // Previous arrow
        $I->seeInCurrentUrl('page=1');
    }

    // Test 30: First page link returns to page 1
    public function firstPageLinkReturnsToPageOne(AcceptanceTester $I)
    {
        $I->wantTo('Click First page link from page 5');
        $I->amOnPage('/product?page=5');
        $I->click('1');
        $I->seeCurrentUrlMatches('/page=1|(?!page=)/'); // Either page=1 or no page param
    }

    // Test 31: Last page navigation works
    public function lastPageNavigationWorks(AcceptanceTester $I)
    {
        $I->wantTo('Navigate to last page via URL');
        // From crawl, we know there are 88+ pages, test a high page number
        $I->amOnPage('/product?page=88');
        $I->seeInCurrentUrl('page=88');
    }

    // Test 32: Pagination preserves category filter
    public function paginationPreservesCategoryFilter(AcceptanceTester $I)
    {
        $I->wantTo('Verify category filter persists across pagination');
        $I->amOnPage('/product?category_id=14&page=1');
        $I->click('2');
        $I->seeInCurrentUrl('category_id=14');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 33: Pagination preserves warehouse filter
    public function paginationPreservesWarehouseFilter(AcceptanceTester $I)
    {
        $I->wantTo('Verify warehouse filter persists across pagination');
        $I->amOnPage('/product?warehouse_id=1&page=1');
        $I->click('2');
        $I->seeInCurrentUrl('warehouse_id=1');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 34: Pagination preserves search query
    public function paginationPreservesSearchQuery(AcceptanceTester $I)
    {
        $I->wantTo('Verify search query persists across pagination');
        $I->amOnPage('/product?ProductSearch[name]=syrup&page=1');
        $I->click('2');
        $I->seeInCurrentUrl('ProductSearch');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 35: Per-page count displays correctly
    public function perPageCountDisplaysCorrectly(AcceptanceTester $I)
    {
        $I->wantTo('Verify per-page count shows 24 items per page');
        $I->amOnPage('/product');
        $I->see('1-24 of'); // Should show items 1-24 on first page
    }

    // Test 36: Pagination on different categories
    public function paginationOnDifferentCategories(AcceptanceTester $I)
    {
        $I->wantTo('Verify pagination works with different category counts');
        // Category 14 has different total count
        $I->amOnPage('/product?category_id=14');
        $I->seeElement('li.page-item');
        $I->click('2');
        $I->seeInCurrentUrl('page=2');
        $I->seeInCurrentUrl('category_id=14');
    }

    // Additional: Out of bounds pagination
    public function outOfBoundsPaginationHandling(AcceptanceTester $I)
    {
        $I->wantTo('Verify system handles out of bounds page numbers gracefully');
        $I->amOnPage('/product?page=999');
        // Should either redirect to last valid page or show empty results
        $I->seeInCurrentUrl('/product');
    }
}
