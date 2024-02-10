<?php

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

uses(TestCase::class, RefreshDatabase::class);

it('can create and find a supplier', function(){
    $supplierCreated = Supplier::factory()->create();

    $supplierFound = Supplier::find($supplierCreated->id);

    expect($supplierCreated->id)->toEqual($supplierFound->id);
});

it('can update a supplier', function(){

    $supplierCreated = Supplier::factory()->create();

    $status = $supplierCreated->status == 'A' ? 'I' : 'A';

    $supplierCreated->update(['status' => $status]);

    expect($supplierCreated->status)->toEqual($status);

});

it('can trash a supplier', function(){

    $supplier = Supplier::factory()->create();
    $supplier->delete();
    expect($supplier->trashed())->toBeTrue();

});

it('can restore a supplier', function(){

    $supplier = Supplier::factory()->create();
    $supplier->delete();
    $supplier->restore();
    expect($supplier->trashed())->not->toBeTrue();

});

it('can delete permanently a supplier', function(){

    $supplier = Supplier::factory()->create();
    $supplier->forceDelete();
    $this->assertDataBaseMissing('suppliers', ['id' => $supplier->id]);

});

it('status is not null', function(){

    $this->expectException(QueryException::class);

    Supplier::factory()->create(['status' => null]);

});

it('the supplier has a user', function(){

    $supplier = Supplier::factory()->create();

    expect($supplier->user)->toBeInstanceOf(User::class);

});

it('can order by name', function(){
    User::factory(10)->create();
    Supplier::factory(10)->create();

    dd($suppliersByName = Supplier::join('users','user_id','=','users.id')->orderBy('name')->pluck('name'));
});

it('id is uuid', function(){

    $supplier = Supplier::factory()->create();

    expect(Str::uuid($supplier->id))->toBeTrue();

});