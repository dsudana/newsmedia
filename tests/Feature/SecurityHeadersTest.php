<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    /**
     * Test security headers are present on all responses
     */
    public function test_security_headers_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Test MIME type sniffing protection
     */
    public function test_mime_type_sniffing_protection(): void
    {
        $response = $this->get('/');

        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
    }

    /**
     * Test clickjacking protection
     */
    public function test_clickjacking_protection(): void
    {
        $response = $this->get('/');

        $this->assertEquals('DENY', $response->headers->get('X-Frame-Options'));
    }

    /**
     * Test XSS protection header
     */
    public function test_xss_protection_header(): void
    {
        $response = $this->get('/');

        $this->assertEquals('1; mode=block', $response->headers->get('X-XSS-Protection'));
    }

    /**
     * Test Content Security Policy header present
     */
    public function test_content_security_policy_header(): void
    {
        $response = $this->get('/');

        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    /**
     * Test Permissions Policy header
     */
    public function test_permissions_policy_header(): void
    {
        $response = $this->get('/');

        $this->assertTrue($response->headers->has('Permissions-Policy'));
    }

    /**
     * Test security headers on all routes
     */
    public function test_security_headers_on_all_routes(): void
    {
        $routes = [
            '/',
            '/blog',
            '/dashboard',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);

            if ($response->status() !== 302 && $response->status() !== 401) {
                $response->assertHeader('X-Content-Type-Options', 'nosniff');
            }
        }
    }
}
