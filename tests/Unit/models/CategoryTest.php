<?php

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can create and find a category', function () {

    $category = Category::factory()->create();

    $categoryFound = Category::find($category->id);

    expect($category->id)->toEqual($categoryFound->id);

});

it('can update a category', function () {

    $category = Category::factory()->create();

    $category->update(['name' => 'new name']);

    expect($category->name)->toEqual('new name');

});

it('can trash a category', function () {

    $category = Category::factory()->create();

    $category->delete();

    expect($category->trashed())->toBeTrue();

});

it('can restore a category', function () {
    $category = Category::factory()->create();

    $category->delete();

    $category->restore();

    expect($category->trashed())->toBeFalse();
});

it('can permanently delete a category', function () {
    $category = Category::factory()->create();

    $category->forceDelete();

    $this->assertDataBaseMissing('categories', ['id' => $category->id]);
});

it('name is not null', function () {
    $this->expectException(QueryException::class);

    Category::factory()->create(['name' => null]);

});

it('name is unique', function () {

    $this->expectException(UniqueConstraintViolationException::class);

    Category::factory()->create(['name' => 'category']);
    Category::factory()->create(['name' => 'category']);

});

it('can order by name', function () {

    Category::factory(3)->create();

    $categories = Category::orderBy('name')->pluck('name');

    expect($categories)->not->toBeEmpty();
});

it('id is a uuid', function () {

    $category = Category::factory()->create();

    expect(Str::isUuid($category->id))->toBeTrue();

});
