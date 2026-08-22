import vue from 'eslint-plugin-vue';

export default [
    ...vue.configs['flat/recommended'],
    {
        files: ['resources/js/**/*.{vue,ts,js}'],
        rules: {
            // Vue rules
            'vue/multi-word-component-names': 'off',
            'vue/no-v-html': 'warn',
            'vue/require-default-prop': 'off',

            // General
            'no-console': ['warn', { allow: ['warn', 'error'] }],
            'no-debugger': 'warn',
            'prefer-const': 'error',
            'no-var': 'error',
            'no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
        },
    },
    {
        ignores: [
            'public/**',
            'vendor/**',
            'node_modules/**',
            'storage/**',
            'bootstrap/cache/**',
            '*.config.js',
            '*.config.ts',
        ],
    },
];
