import { execSync } from 'child_process';

const DEFAULT_VITE_PORT = 5173;
const VITE_PORT = parseInt(process.env.VITE_DEV_PORT, 10) || DEFAULT_VITE_PORT;

// Check unique ports to clean (the configured port + the default in case they differ)
const ports = new Set([VITE_PORT, DEFAULT_VITE_PORT]);

for (const port of ports) {
    try {
        const output = execSync(
            `netstat -ano | findstr "TCP" | findstr ":${port} "`,
            { encoding: 'utf8', timeout: 5000 }
        );

        const lines = output.trim().split('\n').filter(Boolean);
        const killedPids = new Set();

        for (const line of lines) {
            // netstat output format: TCP    0.0.0.0:5173   0.0.0.0:0    LISTENING    25196
            const parts = line.trim().split(/\s+/);
            const pid = parts[parts.length - 1];

            if (pid && pid !== '0' && !killedPids.has(pid)) {
                killedPids.add(pid);
                try {
                    execSync(`taskkill.exe /F /PID ${pid}`, {
                        encoding: 'utf8',
                        timeout: 5000,
                        stdio: 'ignore',
                    });
                    console.log(`  ✓ Puerto ${port}: proceso PID ${pid} detenido`);
                } catch {
                    // Couldn't kill — maybe no permission or already gone
                }
            }
        }

        if (killedPids.size === 0) {
            console.log(`  ✓ Puerto ${port}: libre`);
        }
    } catch {
        // findstr returned nothing or command failed — port is free
        if (port === VITE_PORT) {
            console.log(`  ✓ Puerto ${port}: libre`);
        }
    }
}
