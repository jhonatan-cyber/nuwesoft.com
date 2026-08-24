<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CspReportController extends Controller
{
    /**
     * Handle CSP violation reports.
     *
     * Browsers send POST requests with a JSON body containing:
     * - document-uri: The page where the violation occurred
     * - violated-directive: The CSP directive that was violated
     * - blocked-uri: The resource that was blocked
     * - original-policy: The full CSP policy
     * - referrer: The referrer (if any)
     * - status-code: The HTTP status code
     * - source-file: The source file (if available)
     * - line-number: The line number (if available)
     */
    public function store(Request $request): JsonResponse
    {
        // CSP reports can come as application/csp-report or application/json
        $report = $request->input('csp-report');

        if (! $report) {
            // Some browsers send the report directly as JSON
            $report = $request->all();
        }

        // Log the violation with structured context
        Log::warning('CSP Violation', [
            'document_uri' => $report['document-uri'] ?? 'unknown',
            'violated_directive' => $report['violated-directive'] ?? 'unknown',
            'blocked_uri' => $report['blocked-uri'] ?? 'unknown',
            'original_policy' => $report['original-policy'] ?? 'unknown',
            'referrer' => $report['referrer'] ?? null,
            'status_code' => $report['status-code'] ?? null,
            'source_file' => $report['source-file'] ?? null,
            'line_number' => $report['line-number'] ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Return 204 No Content (browser expects this for report-uri)
        return response()->json(null, 204);
    }
}
