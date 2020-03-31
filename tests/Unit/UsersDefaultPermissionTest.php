<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;

class DefaultPermissionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *  Contains all dashboard / admin permission routes and their method,
     *  to ensure that they are not accessable without authentication or without the needed admin permissions.
     */
    private $dashboardRoutes;

    protected function setUp(): void
    {
        $this->dashboardRoutes = collect([
            [
                'path' => 'home',
                'method' => 'GET',
            ],
            [
                'path' => 'users',
                'method' => 'GET',
            ],
            [
                'path' => 'sources',
                'method' => 'GET',
            ],
            [
                'path' => 'manage-oauth',
                'method' => 'GET',
            ],
            [
                'path' => 'users/all',
                'method' => 'GET',
            ],
            [
                'path' => 'users/delete',
                'method' => 'DELETE',
            ],
            [
                'path' => 'users/role',
                'method' => 'POST',
            ],
            [
                'path' => 'sources/all',
                'method' => 'GET',
            ],
            [
                // Random id, as this should fail before the id is checked
                'path' => 'sources/delete/1',
                'method' => 'DELETE',
            ],
            [
                'path' => 'sources/new',
                'method' => 'POST',
            ],
            [
                'path' => 'sources/update',
                'method' => 'PUT',
            ],
            [
                'path' => 'categories/all',
                'method' => 'GET',
            ],
            [
                // Random id, as this should fail before the id is checked
                'path' => 'categories/delete/1',
                'method' => 'DELETE',
            ],
            [
                'path' => 'categories/new',
                'method' => 'POST',
            ],
            [
                'path' => 'categories/update',
                'method' => 'PUT',
            ],
            [
                'path' => '/privacy/update',
                'method' => 'POST',
            ],
            [
                'path' => '/privacy/edit',
                'method' => 'GET',
            ],
            [
                'path' => '/imprint/update',
                'method' => 'POST',
            ],
            [
                'path' => '/imprint/edit',
                'method' => 'GET',
            ],
            [
                'path' => '/retention',
                'method' => 'GET',
            ],
            [
                'path' => '/retention/update',
                'method' => 'POST',
            ],
            [
                'path' => '/retention/get',
                'method' => 'GET',
            ],
        ]);

        parent::setUp();
    }

    /**
     * A basic test to make sure that a new user does not by default gain admin permissions.
     *
     * @return void
     */
    public function testNoDefaultAdminPermissions()
    {
        $user = factory(User::class)->create();

        $this->assertFalse($user->is_admin);
    }

    /**
     * A basic test to make sure that the graphql API cannot be accessed without authentification.
     *
     * @return void
     */
    public function testGraphQLEnsureNoPublicAccess()
    {
        // Just use an empty query, as this should fail even before validating the query.
        $response = $this->graphql('');

        $response->assertUnauthorized();
    }

    /**
     * A basic test to make sure that the dashboard cannot be accessed without authentification.
     *
     * @return void
     */
    public function testEnsureNoPublicAccessToDashboard()
    {
        $this->dashboardRoutes->each(function($item) {
            $response = $this->json($item['method'], $item['path']);

            $response->assertUnauthorized();
        });
    }

    /**
     * A basic test to make sure that the dashboard cannot be accessed without the admin permissions.
     *
     * @return void
     */
    public function testEnsureAdminOnlyAccessToUsers()
    {
        $user = factory(User::class)->create();

        $this->dashboardRoutes->each(function($item) use ($user) {
            $response = $this->actingAs($user, 'web')->json($item['method'], $item['path']);

            $response->assertForbidden();
        });
    }

}
