<?php

use App\Models\Color;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

uses(TestCase::class, RefreshDatabase::class);

it('can create and find', function(){

    $color = Color::factory()->create();

    $colorFound = Color::find($color->id);

    expect($color->id)->toEqual($colorFound->id);

});

it('can update', function(){

    $color = Color::factory()->create();

    $color->update(['name' => 'new name']);

    expect($color->name)->toEqual('new name');
});

it('can trash', function(){

    $color = Color::factory()->create();

    $color->delete();

    expect($color->trashed())->toBeTrue();

});

it('can restore', function(){

    $color = Color::factory()->create();

    $color->delete();

    $color->restore();

    expect($color->trashed())->toBeFalse();

});

it('can delete permanently',function(){

    $color = Color::factory()->create();

    $color->forceDelete();

    $this->assertDataBaseMissing('colors', ['id' => $color->id]);

});

it('name is not null', function(){

   $this->expectException(QueryException::class);

    Color::factory()->create(['name' => null]);

});

it('name, code is unique', function(){

    $this->expectException(UniqueConstraintViolationException::class);

    Color::factory()->create([
        'name' => 'white',
        'code' => '#ffff'
    ]);

    Color::factory()->create([
        'name' => 'white',
        'code' => '#ffff'
    ]);

});

it('can order by name, code', function(){

    $color = Color::factory(5)->create();

    $colorsByName = Color::orderBy('name')->pluck('name');

    $colorByCode = Color::orderBy('code')->pluck('code');

    expect($colorsByName)->not->toBeEmpty();

    expect($colorByCode)->not->toBeEmpty();

});

it('id is a uuid', function(){

    $color = Color::factory()->create();

    expect(Str::isUuid($color->id))->toBeTrue();

});