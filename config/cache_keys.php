<?php

/**
 * Centralized cache key registry.
 *
 * Maps entity names to the cache keys that depend on them.
 * When an entity changes, all its registered keys are flushed.
 *
 * Convention: keys are prefixed with their context:
 *   - "dashboard.*" → DashboardController stats
 *   - "health.*" → HealthController metrics
 *   - "welcome_*" → Public welcome page
 *   - "active_*" → Public listing pages
 *   - "settings" → Global settings
 */
return [

    'entities' => [

        'project' => [
            'active_projects_with_relations',
            'active_projects_servicios',
            'portfolio_projects',
            'dashboard.active_projects',
            'dashboard.total_projects',
            'dashboard.projects_by_category',
            'dashboard.recent_projects',
            'welcome_stats',
            'settings', // Projects affect welcome page settings
        ],

        'technology' => [
            'active_technologies',
            'active_technologies_servicios',
            'active_projects_with_relations',
            'dashboard.active_technologies',
            'dashboard.total_technologies',
            'dashboard.tech_by_category',
        ],

        'setting' => [
            'settings',
        ],

        'post' => [
            'dashboard.total_posts',
            'dashboard.published_posts',
        ],

        'testimonial' => [
            'welcome_testimonials',
            'dashboard.total_testimonials',
        ],

        'message' => [
            'dashboard.pending_messages',
            'dashboard.unread_messages',
        ],

        'health' => [
            'health.projects',
            'health.technologies',
            'health.contacts',
        ],

    ],

];
