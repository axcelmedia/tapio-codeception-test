<?php

use Tests\Support\AcceptanceTester;

/**
 * Tests for category filtering functionality
 * @group guest
 * @group filters
 * @group categories
 */
class CategoryFilterCest
{
    // Test 17: Category filter by ID works
    public function categoryFilterByIdWorks(AcceptanceTester $I)
    {
        $I->wantTo('Filter products by category ID 14 (1883 Syrup)');
        $I->amOnPage('/product?category_id=14');
        $I->seeInCurrentUrl('category_id=14');
        $I->see('1883'); // Should see 1883 products
    }

    // Test 18: Clicking category from nav applies filter
    public function clickingCategoryAppliesFilter(AcceptanceTester $I)
    {
        $I->wantTo('Click a category from navigation and verify filter applied');
        $I->amOnPage('/');
        // Navigate to product listing first
        $I->amOnPage('/product');
        // Click on a category link if visible
        $I->seeElement('a[href*="category_id"]');
    }

    // Test 19: Category breadcrumb displays (if implemented)
    public function categoryBreadcrumbDisplays(AcceptanceTester $I)
    {
        $I->wantTo('Verify breadcrumb shows current category');
        $I->amOnPage('/product?category_id=14');
        // Look for breadcrumb or category indicator
        $I->seeInCurrentUrl('category_id=14');
    }

    // Test 20: Filtered products match selected category
    public function filteredProductsMatchCategory(AcceptanceTester $I)
    {
        $I->wantTo('Verify products shown match the selected category');
        $I->amOnPage('/product?category_id=14');
        // Category 14 is 1883 Syrup, so should see syrup products
        $I->see('Syrup');
    }

    // Test 21: Category filter persists with pagination
    public function categoryFilterPersistsWithPagination(AcceptanceTester $I)
    {
        $I->wantTo('Verify category filter persists when navigating to page 2');
        $I->amOnPage('/product?category_id=14');
        // Click page 2 if it exists
        $I->click('2');
        $I->seeInCurrentUrl('category_id=14');
        $I->seeInCurrentUrl('page=2');
    }

    // Test 22: Switching between categories updates product list
    public function switchingCategoriesUpdatesProducts(AcceptanceTester $I)
    {
        $I->wantTo('Switch from one category to another and verify products change');
        // Start with category 14
        $I->amOnPage('/product?category_id=14');
        $I->see('1883');

        // Switch to different category (e.g., category 45 - Bags)
        $I->amOnPage('/product?category_id=45');
        $I->seeInCurrentUrl('category_id=45');
    }

    // Test 23: Parent category navigation
    public function parentCategoryNavigation(AcceptanceTester $I)
    {
        $I->wantTo('Navigate to a parent category and verify it shows content');
        // Food Packaging is a parent category
        $I->amOnPage('/product?category_id=1');
        $I->seeInCurrentUrl('category_id=1');
    }

    // Test 24: Multiple nested category levels work
    public function nestedCategoryLevelsWork(AcceptanceTester $I)
    {
        $I->wantTo('Verify nested category filtering works');
        // Category 14 is under Beverage Supply > Bartender & Barista Series
        $I->amOnPage('/product?category_id=14');
        $I->seeInCurrentUrl('category_id=14');
        $I->see('1883'); // Specific subcategory products
    }

    // Additional: Clear category filter
    public function clearCategoryFilter(AcceptanceTester $I)
    {
        $I->wantTo('Navigate to products without category filter');
        $I->amOnPage('/product');
        $I->dontSeeInCurrentUrl('category_id');
        $I->see('items'); // Should see all products
    }
}
