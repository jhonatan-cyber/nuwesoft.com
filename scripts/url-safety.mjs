import { lookup } from 'node:dns/promises';
import { isIP } from 'node:net';

const publicHosts = new Map();

const isPrivateIpv4 = (address) => {
    const octets = address.split('.').map(Number);
    if (octets.length !== 4 || octets.some(octet => !Number.isInteger(octet) || octet < 0 || octet > 255)) return true;

    const [a, b] = octets;

    return a === 0
        || a === 10
        || a === 127
        || (a === 100 && b >= 64 && b <= 127)
        || (a === 169 && b === 254)
        || (a === 172 && b >= 16 && b <= 31)
        || (a === 192 && b === 0)
        || (a === 192 && b === 88)
        || (a === 192 && b === 168)
        || (a === 192 && b === 0 && octets[2] === 2)
        || (a === 198 && (b === 18 || b === 19))
        || (a === 198 && b === 51 && octets[2] === 100)
        || (a === 203 && b === 0 && octets[2] === 113)
        || a >= 224;
};

const isPrivateIpv6 = (address) => {
    const normalized = address.toLowerCase().split('%')[0];

    if (normalized === '::' || normalized === '::1') return true;
    if (normalized.startsWith('fc') || normalized.startsWith('fd') || normalized.startsWith('fe8') || normalized.startsWith('fe9') || normalized.startsWith('fea') || normalized.startsWith('feb')) return true;
    if (normalized.startsWith('ff') || normalized.startsWith('2001:db8') || normalized.startsWith('2001:0:') || normalized.startsWith('2002:') || normalized.startsWith('64:ff9b:')) return true;

    const mappedIpv4 = normalized.match(/::ffff:(\d+\.\d+\.\d+\.\d+)$/)?.[1];
    return mappedIpv4 ? isPrivateIpv4(mappedIpv4) : false;
};

export const isPrivateAddress = address => {
    const version = isIP(address);
    if (version === 4) return isPrivateIpv4(address);
    if (version === 6) return isPrivateIpv6(address);
    return true;
};

export const assertSafeNetworkUrl = async (rawUrl, navigationOrigin = null) => {
    const url = new URL(rawUrl);

    if (['data:', 'blob:', 'about:'].includes(url.protocol)) return;
    if (!['http:', 'https:'].includes(url.protocol)) throw new Error(`Protocolo bloqueado: ${url.protocol}`);
    if (url.username || url.password) throw new Error('Las credenciales dentro de la URL no están permitidas.');
    if (navigationOrigin && url.origin !== navigationOrigin) throw new Error('La navegación hacia otro origen fue bloqueada.');

    const cacheKey = url.hostname.toLowerCase();
    let addresses = publicHosts.get(cacheKey);
    if (!addresses) {
        addresses = await lookup(url.hostname, { all: true, verbatim: true });
        if (addresses.length === 0 || addresses.some(({ address }) => isPrivateAddress(address))) {
            throw new Error('La URL apunta a una red privada o reservada.');
        }
        publicHosts.set(cacheKey, addresses);
    }
};
