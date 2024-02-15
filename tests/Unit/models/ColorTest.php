<?php

use App\Models\Color;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can create and find', function () {

    $color = Color::factory()->create();

    $colorFound = Color::find($color->id);

    expect($color->id)->toEqual($colorFound->id);

});

it('can update', function () {

    $color = Color::factory()->create();

    $color->update(['name' => 'new name']);

    expect($color->name)->toEqual('new name');
});

it('can trash', function () {

    $color = Color::factory()->create();

    $color->delete();

    expect($color->trashed())->toBeTrue();

});

it('can restore', function () {

    $color = Color::factory()->create();

    $color->delete();

    $color->restore();

    expect($color->trashed())->toBeFalse();

});

it('can delete permanently', function () {

    $color = Color::factory()->create();

    $color->forceDelete();

    $this->assertDataBaseMissing('colors', ['id' => $color->id]);

});

it('name and code is not null', function () {

    $this->expectException(QueryException::class);

    Color::factory()->create([
        'name' => null,
        'code' => null
    ]);

});

it('pivot column quantity is not null (color_product)', function(){

    $this->expectException(QueryException::class);

    $color = Color::factory()->create();
    $product = Product::factory()->create();

    $color->products()->attach($product,['quantity' => null]);

});

it('name, code is unique', function () {

    $this->expectException(UniqueConstraintViolationException::class);

    Color::factory()->create([
        'name' => 'white',
        'code' => '#ffff',
    ]);

    Color::factory()->create([
        'name' => 'white',
        'code' => '#ffff',
    ]);

});

it('can have products', function(){

    $color = Color::factory()->create();
    Product::factory(2)->create()->each(function(Product $product) use ($color){

        $product->colors()->attach($color,[
            'quantity' => 3
        ]);

    });

    expect($color->products)->toHaveCount(2);
});

it('can order by name, code', function () {

    $color = Color::factory(5)->create();

    $colorsByName = Color::orderBy('name')->pluck('name');

    $colorByCode = Color::orderBy('code')->pluck('code');

    expect($colorsByName)->not->toBeEmpty();

    expect($colorByCode)->not->toBeEmpty();

});

it('id is a uuid', function () {

    $color = Color::factory()->create();

    expect(Str::isUuid($color->id))->toBeTrue();

});