import { describe, expect, it } from 'vitest'
import { isPrivateAddress } from '../../../scripts/url-safety.mjs'

describe('browser capture URL safety', () => {
    it.each([
        '127.0.0.1',
        '10.0.0.1',
        '172.16.0.1',
        '192.168.1.1',
        '169.254.169.254',
        '100.64.0.1',
        '192.0.2.1',
        '198.51.100.1',
        '203.0.113.1',
        '::1',
        'fc00::1',
        'fe80::1',
        '::ffff:127.0.0.1',
        '2001:db8::1',
    ])('blocks private or reserved address %s', address => {
        expect(isPrivateAddress(address)).toBe(true)
    })

    it.each(['1.1.1.1', '8.8.8.8', '2606:4700:4700::1111'])(
        'allows public address %s',
        address => {
            expect(isPrivateAddress(address)).toBe(false)
        },
    )
})
