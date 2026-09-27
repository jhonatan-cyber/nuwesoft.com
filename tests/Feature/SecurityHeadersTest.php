<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_script_src_requires_a_nonce_and_rejects_unsafe_inline(): void
    {
        $csp = (string) $this->get('/')->assertOk()->headers->get('Content-Security-Policy');
        $scriptSrc = $this->directive('script-src', $csp);

        $this->assertNotSame('', $scriptSrc, 'The Content-Security-Policy header does not define script-src.');

        $this->assertStringContainsString("'nonce-", $scriptSrc);
        $this->assertStringNotContainsString("'unsafe-inline'", $scriptSrc);
    }

    public function test_style_src_rejects_unsafe_inline_but_keeps_style_attributes(): void
    {
        $csp = (string) $this->get('/')->assertOk()->headers->get('Content-Security-Policy');
        $styleSrc = $this->directive('style-src', $csp);

        $this->assertNotSame('', $styleSrc, 'The Content-Security-Policy header does not define style-src.');
        $this->assertStringNotContainsString("'unsafe-inline'", $styleSrc);
        $this->assertStringContainsString("'nonce-", $styleSrc, 'style-src must accept the nonce used by nonced <style> elements.');

        // <style> elements come from first-party bundles, while style="..."
        // attributes are still needed by the Vue templates.
        $this->assertStringContainsString("'unsafe-inline'", $this->directive('style-src-attr', $csp));
    }

    public function test_inline_scripts_carry_the_nonce_from_the_policy(): void
    {
        $response = $this->get('/')->assertOk();
        $csp = (string) $response->headers->get('Content-Security-Policy');

        $this->assertSame(1, preg_match("/'nonce-([^']+)'/", $csp, $matches), 'No nonce found in the policy.');

        $nonce = (string) $matches[1];
        $this->assertNotSame('', $nonce);

        $this->assertStringContainsString(
            'nonce="' . $nonce . '"',
            (string) $response->getContent(),
            'Inline scripts are not carrying the nonce required by the policy.'
        );
    }

    public function test_script_src_hosts_are_a_tight_allowlist(): void
    {
        $csp = (string) $this->get('/')->assertOk()->headers->get('Content-Security-Policy');
        $scriptSrc = $this->directive('script-src', $csp);

        $tokens = preg_split('/\s+/', trim($scriptSrc)) ?: [];
        array_shift($tokens); // drop the directive name

        // The nonce is unique per request; drop it before comparing hosts.
        $tokens = array_values(array_filter(
            $tokens,
            static fn (string $token): bool => ! str_starts_with($token, "'nonce-")
        ));
        sort($tokens);

        // Only hosts that really serve scripts are allowed:
        //  - static.cloudflareinsights.com: Web Analytics beacon injected by Cloudflare
        //  - us-/eu-assets.i.posthog.com: PostHog remote config + lazy extensions
        // Anything else (e.g. cdn.jsdelivr.net, which only ever served images) must fail.
        $expected = [
            "'self'",
            'https://eu-assets.i.posthog.com',
            'https://static.cloudflareinsights.com',
            'https://us-assets.i.posthog.com',
        ];
        sort($expected);

        $this->assertSame(
            $expected,
            $tokens,
            'script-src drifted from the expected host allowlist.'
        );

        $this->assertStringNotContainsString("'unsafe-inline'", $scriptSrc);
    }

    public function test_security_headers_are_still_applied(): void
    {
        $this->get('/')->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Extract a single directive (by exact name) from the Content-Security-Policy header.
     */
    private function directive(string $name, string $csp): string
    {
        foreach (explode(';', $csp) as $directive) {
            $directive = trim($directive);
            $firstToken = explode(' ', $directive)[0] ?? '';

            if ($firstToken === $name) {
                return $directive;
            }
        }

        return '';
    }
}
