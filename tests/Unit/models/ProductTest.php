<?php

use App\Models\Color;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can create and find', function () {

    $productCreated = Product::factory()->create();

    $productFound = Product::find($productCreated->id);

    expect($productCreated->id)->toEqual($productFound->id);

});

it('can update', function () {

    $product = Product::factory()->create();

    $product->update(['name' => 'new name']);

    expect($product->name)->toEqual('new name');

});

it('can trash', function () {

    $product = Product::factory()->create();

    $product->delete();

    expect($product->trashed())->toBeTrue();

});

it('can restore', function () {

    $product = Product::factory()->create();
    $product->delete();
    $product->restore();
    expect($product->trashed())->toBeFalse();

});

it('can delete permanently', function () {

    $product = Product::factory()->create();

    $product->forceDelete();

    $this->assertDataBaseMissing('products', ['id' => $product->id]);

});

it('name,weight and supplier_id is not null', function () {

    $this->expectException(QueryException::class);

    Product::factory()->create([
        'name' => null,
        'weight' => null,
        'supplier_id' => null,
    ]);

});

it('can have colors', function () {

    $product = Product::factory()->create();

    Color::factory(2)->create()->each(function (Color $color) use ($product) {
        $product->colors()->attach($color, ['quantity' => 5]);
    });

    expect($product->colors)->toHaveCount(2);
});

it('can orderBy name,weight and quantity', function () {

    Product::factory(10)->create();

    $productsOrderByName = Product::orderBy('name')->pluck('name');

    $productOrderByWeight = Product::orderBy('weight')->pluck('weight');

    expect($productsOrderByName)->not->toBeEmpty();
    expect($productOrderByWeight)->not->toBeEmpty();

});

it('id is a uuid', function () {

    $product = Product::factory()->create();

    expect(Str::isUuid($product->id))->toBeTrue();

});
