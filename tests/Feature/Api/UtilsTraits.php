<?php

namespace Tests\Feature\Api;

use App\Models\User;

trait UtilsTraits
{
    public function createUser()
    {
        return User::factory()->create();
    }

    public function createTokenToUser(User $user)
    {
        return $user->createToken('teste')->plainTextToken;
    }

    public function createTokenUser()
    {
        return  $this->createTokenToUser($this->createUser());
    }

    public function defaultHeaders($token = null)
    {
        $token = $token ?? $this->createTokenUser();
        return  ['Authorization' => "Bearer {$token}"];
    }
}
