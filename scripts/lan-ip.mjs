import os from 'os';
import { writeFileSync } from 'fs';

function getLanIp() {
    try {
        const interfaces = os.networkInterfaces();
        for (const name of Object.keys(interfaces)) {
            for (const iface of interfaces[name] ?? []) {
                if (iface.family === 'IPv4' && !iface.internal) {
                    return iface.address;
                }
            }
        }
    } catch {
        // ignore
    }
    return null;
}

const ip = getLanIp();

if (ip) {
    const vitePort = process.env.VITE_DEV_PORT || '5173';

    // Write VITE_DEV_SERVER_URL to .env.local so Vite loads it automatically
    writeFileSync('.env.local', `VITE_DEV_SERVER_URL=http://${ip}:${vitePort}\n`, 'utf8');

    const port = process.env.APP_PORT || '8080';
    console.log('');
    console.log('  ╔══════════════════════════════════════════════════╗');
    console.log('  ║     🌐  Red Local                               ║');
    console.log('  ║                                                ║');
    console.log(`  ║     Accede desde tu celular u otros             ║`);
    console.log(`  ║     dispositivos en la misma red:               ║`);
    console.log('  ║                                                ║');
    console.log(`  ║     🔗  http://${ip}:${port}                    ║`);
    console.log(`  ║     ⚡  Vite HMR: http://${ip}:${vitePort}             ║`);
    console.log('  ║                                                ║');
    console.log('  ╚══════════════════════════════════════════════════╝');
    console.log('');
} else {
    console.log('');
    console.log('  ╔══════════════════════════════════════════════════╗');
    console.log('  ║     ⚠️  Sin conexión de red detectada           ║');
    console.log('  ║                                                ║');
    console.log('  ║     Conectate a una red WiFi o LAN para         ║');
    console.log('  ║     acceder desde otros dispositivos.           ║');
    console.log('  ╚══════════════════════════════════════════════════╝');
    console.log('');
}
