# nuwesoft.com

nuwesoft official website.

## Run locally

- `composer dev` starts Laravel, the queue worker, and Vite.
- `composer dev:logs` starts `pail` for log tailing on systems that support `pcntl` (for example Linux or macOS).

On Windows, `composer dev` is the recommended command because `pail` is not available there.
