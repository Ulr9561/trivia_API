<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_attributes_are_set_correctly(): void
    {
        $user = User::create([
            'name' => "D'Ulrich ADEGOKE",
            'email' => 'ulrichadeg857sdoke@gmaidfgl.qdcom',
            'password' => Hash::make("password")
        ]);
        $created_user = User::find($user->id);
        $this->assertEquals("Ulrich ADEGOKE", $created_user->name);
        $this->assertNotNull($created_user);
    }
}
