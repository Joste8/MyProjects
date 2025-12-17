# AI Coding Agent Instructions for MyProjects

## Project Overview
This is a Symfony 7.3 web application built with Doctrine ORM, PostgreSQL database, and EasyAdmin for admin interfaces. It manages entities like Users, Courses, Products (with sizes), and Tasks. Frontend uses Twig templates, Stimulus.js for interactivity, and Symfony Asset Mapper for assets.

## Architecture
- **Backend**: Symfony framework with MicroKernel, attribute-based routing (`#[Route]`), and service injection.
- **Database**: PostgreSQL via Docker Compose; Doctrine ORM with attribute mappings (`#[ORM\Entity]`, `#[ORM\Column]`).
- **Admin**: EasyAdmin Bundle for CRUD interfaces (e.g., `ProductCrudController` configures fields like `TextField::new('name')`).
- **Frontend**: Twig templates in `templates/`, Stimulus controllers in `assets/controllers/`, imported via Asset Mapper.
- **Entities**: Located in `src/Entity/`, with relationships (e.g., `Product` one-to-many `ProductSize`).
- **Controllers**: In `src/Controller/`, handle routes and render templates (e.g., `CourseController` for CRUD operations).
- **Forms**: Symfony forms in `src/Form/`, tied to entities (e.g., `CourseType` extends `AbstractType`).
- **Security**: User entity with roles, password hashing via `UserPasswordHasherInterface`.

## Key Workflows
- **Development Server**: Run `symfony serve` (requires Symfony CLI) or use PHP built-in server.
- **Database**: Start with `docker-compose up database`; run migrations with `php bin/console doctrine:migrations:migrate`.
- **Assets**: Use Asset Mapper; no build step needed, assets auto-imported.
- **Testing**: PHPUnit via `vendor/bin/phpunit`; tests in `tests/Controller/` use `WebTestCase` (e.g., `HomeControllerTest`).
- **Commands**: Custom console commands in `src/Command/` (e.g., `app:create-admin` to seed admin user).
- **Dependencies**: Manage with Composer; run `composer install` after cloning.

## Conventions and Patterns
- **Entities**: Use Doctrine attributes for mappings; include getters/setters; repositories in `src/Repository/`.
- **Controllers**: Inject `EntityManagerInterface` for DB ops; use `$this->render()` for Twig views.
- **Routes**: Defined via attributes on controller methods (e.g., `#[Route('/course', name: 'app_course_list')]`).
- **Forms**: Build with `FormBuilderInterface`; validate with `isSubmitted() && isValid()`.
- **Templates**: Extend `base.html.twig`; use Twig blocks; Stimulus via `data-controller` attributes.
- **Migrations**: Auto-generated in `migrations/`; run with `php bin/console doctrine:migrations:migrate`.
- **Admin CRUD**: Extend `AbstractCrudController`; configure fields in `configureFields()` (e.g., hide fields on index with `->hideOnIndex()`).
- **JS Controllers**: Named after files (e.g., `hello_controller.js` -> `data-controller="hello"`); import in `app.js`.
- **Testing**: Functional tests with `static::createClient()`; assert responses with `assertResponseIsSuccessful()`.

## Integration Points
- **Database**: Connect via `DATABASE_URL` env var; use PostgreSQL-specific identity generation.
- **External Deps**: EasyAdmin for admin UI, Stimulus/Turbo for dynamic frontend, Twig Components for reusable UI.
- **Security**: User authentication via Symfony Security; roles like `ROLE_ADMIN`.
- **Messenger**: Async processing with Doctrine Messenger (table `messenger_messages`).

## Examples
- **Entity Relationship**: `ProductSize` belongs to `Product` via `#[ORM\ManyToOne(inversedBy: 'productSizes')]`.
- **CRUD Flow**: In `CourseController::new()`, create form, handle request, persist with `$em->persist($course); $em->flush()`.
- **Admin Config**: In `ProductCrudController`, fields like `IntegerField::new('price')->setColumns(6)`.
- **Stimulus Usage**: Controller connects to element with `this.element.textContent = 'Hello Stimulus!'`.
- **Migration**: Add columns/tables in `up()` method with `$this->addSql('ALTER TABLE ...')`.

Reference: `src/Entity/Product.php`, `src/Controller/Admin/ProductCrudController.php`, `assets/controllers/hello_controller.js`.</content>
<parameter name="filePath">c:\Users\joste\Desktop\projct\MyProjects\.github\copilot-instructions.md