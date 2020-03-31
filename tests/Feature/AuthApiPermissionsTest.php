<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Datamining\WifiData;

class AuthApiPermissionsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Check that the delete endpoint can be used by the logged in user himself.
     *
     * @return void
     */
    public function testAPIAccessRemoveUserAsUser()
    {
        $user1 = factory(User::class)->create();

        $response = $this->actingAs($user1, 'api')->json('POST', '/api/auth/delete');

        $response->assertNoContent();

        $this->assertDeleted($user1);
    }

    /**
     * Check that the delete endpoint also deletes all associated data.
     *
     * @return void
     */
    public function testAPIRemoveUserAndDataAsUser()
    {
        $user = factory(User::class)->create();

        $wifiData = factory(WifiData::class)->create();
        $wifiData->user()->associate($user);
        $wifiData->save();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/auth/delete');

        $response->assertNoContent();

        $this->assertDeleted($user);
        $this->assertDeleted($wifiData);
    }
}
