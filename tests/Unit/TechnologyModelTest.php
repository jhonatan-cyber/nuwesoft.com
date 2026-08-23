<?php

namespace Tests\Unit;

use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechnologyModelTest extends TestCase
{
    use RefreshDatabase;

    // ── Optimized Logo URL Accessor ──

    public function test_optimized_logo_url_returns_null_when_logo_url_is_null(): void
    {
        $tech = Technology::create([
            'name' => 'No Logo Tech',
            'category' => 'frontend',
            'logo_url' => null,
        ]);

        $this->assertNull($tech->optimized_logo_url);
    }

    public function test_optimized_logo_url_returns_null_when_logo_url_is_empty(): void
    {
        $tech = Technology::create([
            'name' => 'Empty Logo Tech',
            'category' => 'frontend',
            'logo_url' => '',
        ]);

        // Empty string is falsy, should return null
        $this->assertEmpty($tech->optimized_logo_url);
    }

    public function test_optimized_logo_url_adds_cloudinary_transformations(): void
    {
        $tech = Technology::create([
            'name' => 'Cloudinary Tech',
            'category' => 'frontend',
            'logo_url' => 'https://res.cloudinary.com/demo/image/upload/v1/logo.png',
        ]);

        $this->assertStringContainsString('f_auto,q_auto', $tech->optimized_logo_url);
        $this->assertStringContainsString('/upload/f_auto,q_auto/', $tech->optimized_logo_url);
    }

    public function test_optimized_logo_url_preserves_non_cloudinary_urls(): void
    {
        $tech = Technology::create([
            'name' => 'External Tech',
            'category' => 'frontend',
            'logo_url' => 'https://example.com/logo.png',
        ]);

        $this->assertEquals('https://example.com/logo.png', $tech->optimized_logo_url);
    }

    // ── Casting ──

    public function test_is_active_is_cast_to_boolean(): void
    {
        $tech = Technology::create([
            'name' => 'Bool Tech',
            'category' => 'frontend',
            'is_active' => 1,
        ]);

        $this->assertIsBool($tech->is_active);
        $this->assertTrue($tech->is_active);
    }

    public function test_invert_dark_is_cast_to_boolean(): void
    {
        $tech = Technology::create([
            'name' => 'Invert Tech',
            'category' => 'frontend',
            'invert_dark' => 1,
        ]);

        $this->assertIsBool($tech->invert_dark);
        $this->assertTrue($tech->invert_dark);
    }

    // ── Relationships ──

    public function test_technology_belongs_to_many_projects(): void
    {
        $tech = Technology::create([
            'name' => 'Laravel',
            'category' => 'backend',
        ]);

        $this->assertEmpty($tech->projects);
    }
}
