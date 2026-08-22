export default {
    extends: ['@commitlint/config-conventional'],
    rules: {
        'type-enum': [2, 'always', [
            'feat',     // New feature
            'fix',      // Bug fix
            'docs',     // Documentation
            'style',    // Formatting (no code change)
            'refactor', // Code refactoring
            'perf',     // Performance improvement
            'test',     // Tests
            'build',    // Build system
            'ci',       // CI/CD
            'chore',    // Maintenance
            'revert',   // Revert
        ]],
        'type-case': [2, 'always', 'lower-case'],
        'type-empty': [2, 'never'],
        'subject-empty': [2, 'never'],
        'subject-case': [2, 'never', ['sentence-case', 'start-case', 'pascal-case', 'upper-case']],
        'header-max-length': [2, 'always', 100],
        'body-max-line-length': [1, 'always', 120],
    },
};
