<?php
namespace MyApp\Models;

class CatalogTest extends \BaseTest
{
    public function testGetCategoryById()
    {
        $categories = Catalog::getCategories();
        $actual = array_shift($categories);
        $this->assertEquals($actual, Catalog::getCategoryById($actual['id']));
    }
    public function testGetGoodsByIds()
    {
        $actual = Catalog::getGoodsByIds([]);
        $this->assertEmpty($actual);
        $this->assertIsArray($actual);
    }
}