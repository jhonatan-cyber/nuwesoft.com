import vue from 'eslint-plugin-vue';
import typescriptParser from '@typescript-eslint/parser';

export default [
    ...vue.configs['flat/recommended'],
    {
        files: ['resources/js/**/*.{ts,d.ts}'],
        languageOptions: {
            parser: typescriptParser,
        },
    },
    {
        files: ['resources/js/**/*.{vue,ts,js}'],
        languageOptions: {
            parserOptions: {
                parser: typescriptParser,
            },
        },
        rules: {
            // Vue rules
            'vue/multi-word-component-names': 'off',
            'vue/no-v-html': 'warn',
            'vue/no-v-text-v-html-on-component': 'warn',
            'vue/no-ref-as-operand': 'warn',
            'vue/no-unused-vars': 'warn',
            'vue/valid-define-emits': 'warn',
            'vue/valid-define-props': 'warn',
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
