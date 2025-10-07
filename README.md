# Fekra Store - Modern Women's Fashion E-commerce

A modern, sleek e-commerce platform built with Laravel & Filament Admin Panel, featuring a contemporary design inspired by Next.js aesthetics.

## ✨ Features

- 🎨 **Modern UI/UX** - Sleek, minimalist design with smooth animations and gradients
- � **Women's Fashion Focus** - Curated collections of dresses, tops, abayas, and accessories
- 🎛️ **Advanced Admin Panel** - Comprehensive product and order management with Filament
- 📱 **Fully Responsive** - Seamless experience across all devices and screen sizes
- 🖼️ **Image Management** - Professional product image upload and display
- 📊 **Order Tracking** - Complete order management and tracking system
- 💰 **Dynamic Pricing** - Automatic discount calculations and display
- 🚀 **Performance Optimized** - Fast loading with modern web technologies

## 🛠️ Tech Stack

- **Backend:** Laravel 10+
- **Admin Panel:** Filament
- **Frontend:** Tailwind CSS, Modern CSS Gradients
- **Fonts:** Inter (sans-serif), Playfair Display (display)
- **Database:** MySQL/SQLite

## 📋 Requirements

- PHP 8.1+
- Composer
- MySQL/SQLite
- Node.js & NPM

## 🚀 Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/m-magdyyyy/laravel-dropshipping-store.git
   cd laravel-dropshipping-store
   ```

   ## 🐳 Deploy on Render (Docker)

   To deploy this Laravel app on Render using Docker, follow these brief steps.

   1. On Render create a new Web Service and choose Docker as the environment.

   2. Set the service to build from this repository (Render will use the repository root Dockerfile).

   3. Add the following environment variables in the Render dashboard:

   - APP_ENV=production
   - APP_DEBUG=false
   - APP_URL=<replace-with-your-render-url>
   - PHP_VERSION=8.2
   - APP_KEY=base64:<place-key-if-not-generated-automatically>

   Databases

   - If you use Render Postgres (managed): set the DATABASE_URL provided by Render. Also set `DB_CONNECTION=pgsql` if you prefer explicit DB config. Laravel will parse `DATABASE_URL` automatically.
   - If you use an external MySQL: set `DB_CONNECTION=mysql`, and configure `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` accordingly.

   Notes & first-run

   - This Dockerfile uses `php:8.2-cli`, installs required PHP extensions (pdo_mysql, mbstring, bcmath, exif, gd with freetype & jpeg) and copies Composer from `composer:2.7` to install dependencies inside the image.
   - We intentionally do NOT commit `vendor/` — Composer runs during image build.
   - The container exposes port 8000 and serves the app from `public/` using PHP built-in server. Render will route HTTP traffic to that port.
   - After the first deploy you may need to run database migrations. The image attempts `php artisan migrate --force` at container start; if migrations need manual attention you can re-deploy or run the migration via Shell/Manual Deploy on Render.

   Don't change application logic; the container serves the app using `public/index.php` so all requests are routed correctly.

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Update your `.env` file:
   ```env
   APP_NAME="Fekra Store"
   APP_URL=http://localhost:8000
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

5. **Create Admin User**
   ```bash
   php artisan make:filament-user
   ```

6. **Run the Application**
   ```bash
   php artisan serve
   ```

## 📱 Usage

### Customer Store Front
- Visit: `http://localhost:8000`
- Browse modern collections
- Shop products with seamless checkout

### Admin Dashboard
- Access: `http://localhost:8000/admin`
- Manage products, orders, and customers
- View comprehensive sales analytics

## 🎨 Design Features

- **Modern Gradients** - Rose, gold, and charcoal color palette
- **Glass Morphism** - Smooth, modern glass effects
- **Smooth Animations** - Fade-in, slide, and scale transitions
- **Typography** - Inter for body text, Playfair Display for headings
- **Micro-interactions** - Hover effects and button animations

## 🏗️ Architecture

- **Laravel 10** - Modern PHP Framework
- **Filament v3** - Admin Panel
- **TailwindCSS** - للتصميم
- **MySQL** - قاعدة البيانات
- **Vite** - Build Tool

## المساهمة

نرحب بالمساهمات! يرجى إنشاء Pull Request أو فتح Issue للاقتراحات.

## الترخيص

هذا المشروع مرخص تحت [MIT License](LICENSE).

---

طور بـ ❤️ للمجتمع العربي

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
