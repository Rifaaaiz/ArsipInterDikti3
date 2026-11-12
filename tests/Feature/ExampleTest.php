<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_pokja_upload_route_keeps_its_contract(): void
    {
        $routes = app('router')->getRoutes();
        $uploadRoute = $routes->getByName('arsip.index');

        $this->assertNotNull($uploadRoute);
        $this->assertSame('dashboard-pokja/upload', $uploadRoute->uri());
        $this->assertContains('role:admin_pokja', $uploadRoute->gatherMiddleware());

        $matchingRoutes = array_filter(
            $routes->getRoutes(),
            fn ($route) => $route->uri() === 'dashboard-pokja/upload'
                && in_array('GET', $route->methods(), true)
        );

        $this->assertCount(1, $matchingRoutes);
    }

    public function test_superadmin_archive_routes_resolve_to_existing_methods(): void
    {
        $routes = app('router')->getRoutes();
        $archiveRoutes = [
            'superadmin.arsip.form' => 'createArchive',
            'superadmin.arsip.store' => 'storeArchive',
            'superadmin.arsip.edit' => 'editArchive',
            'superadmin.arsip.update' => 'updateArchive',
            'superadmin.arsip.file.delete' => 'deleteArchiveFile',
            'superadmin.arsip.delete' => 'deleteArchive',
        ];

        foreach ($archiveRoutes as $routeName => $method) {
            $route = $routes->getByName($routeName);

            $this->assertNotNull($route, "Route {$routeName} must exist.");
            $this->assertTrue(method_exists($route->getController(), $method), "Route {$routeName} must resolve to {$method}.");
        }
    }
}
