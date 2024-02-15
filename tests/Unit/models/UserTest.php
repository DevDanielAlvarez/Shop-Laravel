<?php

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can create and find a user', function () {

    $user = User::factory()->create();

    $userFound = User::first();

    expect($user->id)->toEqual($userFound->id);

});

it('can update a user', function () {

    $user = User::factory()->create();

    $user->update(['name' => 'new name']);

    expect($user->name)->toEqual('new name');

});

it('can delete a user', function () {

    $user = User::factory()->create();

    $user->delete();

    expect($user->trashed())->toBeTrue();

});

it('can restore a user', function () {
    $user = User::factory()->create();
    $user->delete();
    $user->restore();
    expect($user->trashed())->not->toBeTrue();
});

it('can permanently delete a user', function () {

    $user = User::factory()->create();

    $user->forceDelete();

    $this->assertDataBaseMissing('users', ['id' => $user->id]);

});

it('name,email and password is required', function () {
    $this->expectException(QueryException::class);
    User::factory()->create([
        'name' => null,
        'email' => null,
        'password' => null,
    ]);
});

it('email is unique', function () {
    $this->expectException(UniqueConstraintViolationException::class);
    User::factory(2)->create(['email' => '123@123.com']);
});
it('password is hash', function () {

    $user = User::factory()->create(['password' => bcrypt('12345678')]);

    expect(Hash::check('12345678', $user->password))->toBeTrue();

});

it('can order by name or email', function () {

    User::factory(10)->create();

    $usersByName = User::orderBy('name')->get()->pluck('name');
    $usersByEmail = User::orderBy('email')->get()->pluck('email');
    expect($usersByEmail)->not->toBeEmpty();
    expect($usersByName)->not->toBeEmpty();

});
