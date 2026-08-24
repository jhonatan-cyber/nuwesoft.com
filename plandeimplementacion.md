Plan de Mejoras Arquitectónicas y Optimización
Este plan detalla la hoja de ruta de desarrollo para implementar mejoras de rendimiento, calidad de código, estabilidad y SEO en el proyecto Nuwesoft.com.

User Review Required
IMPORTANT

Se propone dividir el plan en tres fases secuenciales para no interrumpir el servicio y asegurar despliegues continuos controlados.

Fase 1: Rendimiento e Infraestructura (Caché Redis y Optimización Cloudinary).
Fase 2: Arquitectura de Código (Capa de Almacenamiento y Tipos TypeScript).
Fase 3: Analíticas (Dashboard de PostHog), SEO Avanzado (JSON-LD) y Tests (Pest).
Open Questions
WARNING

¿Querés que implementemos todo el plan de corrido, o preferís que vayamos aprobando y ejecutando fase por fase? (Recomendamos ir fase por fase para verificar la estabilidad en producción en cada paso).

Proposed Changes
Fase 1: Rendimiento e Infraestructura (Performance)
[MODIFY] 
HandleInertiaRequests.php
Implementar caché en Redis para Setting::getAll() dentro del middleware.
[MODIFY] 
Setting.php
Añadir invalidación automática de la caché de Redis cuando se ejecute setValue o se actualice una configuración.
[MODIFY] 
Project.php
 y 
Technology.php
Añadir caché de consultas en los listados públicos y crear observadores (Observers) o eventos para limpiar la caché ante cambios.
Implementar accesores (logo_url_optimized, image_url_optimized) que apliquen transformaciones automáticas de Cloudinary (q_auto,f_auto,w_800, etc.) basadas en parámetros de renderizado.
Fase 2: Arquitectura de Código (Clean Code)
[NEW] 
StorageServiceInterface.php
Definir los métodos contractualmente obligatorios para cualquier driver de almacenamiento (upload, delete).
[NEW] 
CloudinaryStorageService.php
Adaptar la lógica actual de Cloudinary para que implemente el contrato.
[MODIFY] 
UploadToCloudinary.php
Reemplazar la inyección directa del servicio acoplado por el binding de la interfaz mediante Dependency Injection.
[MODIFY] 
package.json
 y 
composer.json
Integrar la dependencia spatie/laravel-typescript-transformer y configurar la compilación de tipos en la build.
Fase 3: Analytics, SEO & Testing
[NEW] 
SchemaHelper.php
Crear un helper para compilar schemas dinámicos JSON-LD basados en tipos de contenido (Organization, Article, Portfolio).
[MODIFY] 
DashboardController.php
Añadir consultas seguras a la API de PostHog para recuperar métricas clave del sitio web (vistas, rebote, países) e inyectarlas al panel.
[NEW] 
Feature/ContactTest.php
Implementar pruebas de integración básicas utilizando Pest para el envío de formularios de contacto y validación de API.
Verification Plan
Automated Tests
Correr php artisan test para verificar que la suite de pruebas unitarias esté en verde.
Ejecutar npx tsc --noEmit en el frontend para asegurar que las definiciones TypeScript autogeneradas no tengan conflictos.
Manual Verification
Inspeccionar las consultas de base de datos en producción para asegurar que el uso de Redis reduce la carga.
Auditar el score de LCP en Google PageSpeed para medir el impacto de la optimización de Cloudinary.