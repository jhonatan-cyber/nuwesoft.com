# ──────────────────────────────────────────────────
#  Nuwesoft.com — Local Development Makefile
#  Usage: make <target>   (requires Docker Desktop running)
# ──────────────────────────────────────────────────

APP      = laravel.test
DC       = docker compose
EXEC     = $(DC) exec $(APP)
ARTISAN  = $(EXEC) php artisan

# ─── First-time Setup ────────────────────────────

.PHONY: setup
setup: up node-install composer-install artisan-key
	@$(ARTISAN) migrate --force
	@$(ARTISAN) db:seed --force
	@echo "✅ Setup complete → http://localhost:$(shell grep APP_PORT .env | cut -d= -f2 || echo 8088)"

.PHONY: fresh-setup
fresh-setup: up node-install composer-install artisan-key
	@$(ARTISAN) migrate:fresh --force
	@$(ARTISAN) db:seed --force
	@echo "✅ Fresh setup complete"

# ─── Container Lifecycle ─────────────────────────

.PHONY: up
up:
	@$(DC) up -d
	@$(DC) ps

.PHONY: down
down:
	@$(DC) down

.PHONY: restart
restart:
	@$(DC) restart $(APP)

.PHONY: rebuild
rebuild:
	@$(DC) down
	@$(DC) up -d --build
	@$(DC) ps

.PHONY: stop
stop:
	@$(DC) stop

.PHONY: ps
ps:
	@$(DC) ps

# ─── Dependencies ────────────────────────────────

.PHONY: composer-install
composer-install:
	@$(EXEC) composer install

.PHONY: composer-update
composer-update:
	@$(EXEC) composer update

.PHONY: node-install
node-install:
	@$(EXEC) bun install
	@$(EXEC) chown -R sail:sail /var/www/html/node_modules
	@echo "✅ node_modules installed (Linux binaries, correct permissions)"

.PHONY: node-update
node-update:
	@$(EXEC) bun update
	@$(EXEC) chown -R sail:sail /var/www/html/node_modules

# ─── Artisan Shortcuts ───────────────────────────

.PHONY: artisan-key
artisan-key:
	@$(ARTISAN) key:generate --force

.PHONY: migrate
migrate:
	@$(ARTISAN) migrate --force

.PHONY: migrate-fresh
migrate-fresh:
	@$(ARTISAN) migrate:fresh --force

.PHONY: migrate-rollback
migrate-rollback:
	@$(ARTISAN) migrate:rollback --force

.PHONY: seed
seed:
	@$(ARTISAN) db:seed --force

.PHONY: fresh
fresh: migrate-fresh seed
	@echo "✅ Database reset and seeded"

.PHONY: tinker
tinker:
	@$(EXEC) php artisan tinker

# ─── Caching ─────────────────────────────────────

.PHONY: cache-clear
cache-clear:
	@$(ARTISAN) cache:clear
	@$(ARTISAN) config:clear
	@$(ARTISAN) route:clear
	@$(ARTISAN) view:clear
	@$(ARTISAN) event:clear
	@echo "✅ All caches cleared"

.PHONY: cache-optimize
cache-optimize:
	@$(ARTISAN) config:cache
	@$(ARTISAN) route:cache
	@$(ARTISAN) view:cache
	@$(ARTISAN) event:cache
	@echo "✅ Caches optimized"

# ─── Testing & Quality ───────────────────────────

.PHONY: test
test:
	@$(ARTISAN) test

.PHONY: test-coverage
test-coverage:
	@$(EXEC) php -d coverage.enabled=1 vendor/bin/phpunit --coverage-html=coverage

.PHONY: pint
pint:
	@$(EXEC) ./vendor/bin/pint

.PHONY: pint-fix
pint-fix:
	@$(EXEC) ./vendor/bin/pint --fix

.PHONY: stan
stan:
	@$(EXEC) ./vendor/bin/phpstan analyse --memory-limit=512M

.PHONY: lint
lint: pint stan
	@echo "✅ All quality checks passed"

# ─── Frontend ────────────────────────────────────

.PHONY: dev
dev:
	@$(EXEC) bun run dev

.PHONY: build
build:
	@$(EXEC) bun run build

# ─── Debugging ───────────────────────────────────

.PHONY: logs
logs:
	@$(DC) logs -f $(APP)

.PHONY: logs-all
logs-all:
	@$(DC) logs -f

.PHONY: shell
shell:
	@$(EXEC) bash

.PHONY: db-shell
db-shell:
	@$(EXEC) psql -U postgres -d $(shell grep DB_DATABASE .env | cut -d= -f2 || echo nuwesoft)

.PHONY: redis-cli
redis-cli:
	@$(EXEC) redis-cli -h redis

# ─── Production-like (no debug) ──────────────────

.PHONY: production
production:
	@$(EXEC) php artisan config:cache
	@$(EXEC) php artisan route:cache
	@$(EXEC) php artisan view:cache
	@$(EXEC) php artisan event:cache
	@$(EXEC) bun run build
	@echo "✅ Production assets built and caches optimized"

# ─── Utility ─────────────────────────────────────

.PHONY: ownership
ownership:
	@$(EXEC) chown -R sail:sail /var/www/html
	@echo "✅ File ownership fixed"

.PHONY: verify
verify:
	@echo "── Container Status ──"
	@$(DC) ps
	@echo ""
	@echo "── Database ──"
	@$(ARTISAN) migrate:status 2>/dev/null | tail -5
	@echo ""
	@echo "── HTTP Check ──"
	@curl -s -o /dev/null -w "  localhost → HTTP %{http_code}\n" http://localhost:$(shell grep APP_PORT .env | cut -d= -f2 || echo 8088)/

.PHONY: help
help:
	@echo "Nuwesoft.com Docker Dev Commands"
	@echo "================================"
	@echo ""
	@echo "  First-time:"
	@echo "    make setup          Full setup (migrate + seed)"
	@echo "    make fresh-setup    Drop DB, migrate, seed"
	@echo ""
	@echo "  Containers:"
	@echo "    make up             Start all services"
	@echo "    make down           Stop all services"
	@echo "    make restart        Restart Laravel container"
	@echo "    make rebuild        Full rebuild (--build)"
	@echo "    make ps             Show container status"
	@echo ""
	@echo "  Database:"
	@echo "    make migrate        Run pending migrations"
	@echo "    make seed           Run database seeders"
	@echo "    make fresh          Drop + migrate + seed"
	@echo "    make migrate-rollback  Rollback last migration"
	@echo ""
	@echo "  Dependencies:"
	@echo "    make composer-install   composer install"
	@echo "    make node-install       bun install + fix perms"
	@echo ""
	@echo "  Quality:"
	@echo "    make test           Run PHPUnit tests"
	@echo "    make pint           Check code style"
	@echo "    make pint-fix       Auto-fix code style"
	@echo "    make stan           PHPStan static analysis"
	@echo "    make lint           Run pint + stan"
	@echo ""
	@echo "  Frontend:"
	@echo "    make dev            Start Vite dev server"
	@echo "    make build          Build production assets"
	@echo ""
	@echo "  Debugging:"
	@echo "    make logs           Tail Laravel logs"
	@echo "    make logs-all       Tail all container logs"
	@echo "    make shell          Open bash in container"
	@echo "    make db-shell       Open PostgreSQL shell"
	@echo "    make tinker         Open Laravel Tinker"
	@echo ""
	@echo "  Other:"
	@echo "    make cache-clear    Clear all caches"
	@echo "    make cache-optimize Optimize all caches"
	@echo "    make ownership      Fix file permissions"
	@echo "    make verify         Check everything works"
