# Laravel Dropshipping Store

متجر دروب شيبنج احترافي مبني بـ Laravel و Filament Admin Panel

## المميزات

- 🛍️ **واجهة احترافية** - صفحة رئيسية أنيقة لعرض المنتجات
- 🎛️ **لوحة تحكم متقدمة** - إدارة شاملة للمنتجات والطلبات باستخدام Filament
- 📱 **تصميم متجاوب** - يعمل على جميع الأجهزة والشاشات
- 🖼️ **إدارة الصور** - رفع وعرض صور المنتجات
- 📊 **تتبع الطلبات** - نظام متكامل لإدارة ومتابعة الطلبات
- 💰 **حساب الخصومات** - عرض الأسعار مع الخصومات تلقائياً

## المتطلبات

- PHP 8.1+
- Composer
- MySQL/SQLite
- Node.js & NPM

## التثبيت

1. **استنساخ المشروع**
   ```bash
   git clone https://github.com/USERNAME/laravel-dropshipping-store.git
   cd laravel-dropshipping-store
   ```

2. **تثبيت المكتبات**
   ```bash
   composer install
   npm install
   ```

3. **إعداد البيئة**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **إعداد قاعدة البيانات**
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

5. **إنشاء مستخدم إداري**
   ```bash
   php artisan make:filament-user
   ```

6. **تشغيل المشروع**
   ```bash
   php artisan serve
   ```

## الاستخدام

### الواجهة الرئيسية
- زر الموقع على: `http://localhost:8000`
- عرض المنتجات مع إمكانية الطلب المباشر

### لوحة التحكم
- زر لوحة التحكم على: `http://localhost:8000/admin`
- إدارة المنتجات والطلبات
- إحصائيات شاملة للمبيعات

## البنية التقنية

- **Laravel 10** - Framework PHP
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
