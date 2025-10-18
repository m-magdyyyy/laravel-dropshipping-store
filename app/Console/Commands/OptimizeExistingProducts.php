<?php

namespace App\Console\Commands;

use App\Jobs\OptimizeProductImages;
use App\Models\Product;
use Illuminate\Console\Command;

class OptimizeExistingProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:optimize {--all : Optimize all products} {--missing : Only optimize products without thumbnails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تحسين صور المنتجات الموجودة وإنشاء thumbnails بصيغة WebP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 بدء عملية تحسين صور المنتجات...');

        $query = Product::query();

        if ($this->option('missing')) {
            // Only products without thumbnails
            $query->whereNull('thumbnail_path')
                  ->orWhere('thumbnail_path', '');
            $this->info('📋 تحسين المنتجات التي لا تحتوي على thumbnails فقط');
        } elseif ($this->option('all')) {
            $this->info('📋 تحسين جميع المنتجات');
        } else {
            // Default: only products with images but no thumbnails
            $query->whereNotNull('image')
                  ->where('image', '!=', '')
                  ->where(function ($q) {
                      $q->whereNull('thumbnail_path')
                        ->orWhere('thumbnail_path', '');
                  });
            $this->info('📋 تحسين المنتجات التي لديها صور بدون thumbnails');
        }

        $totalProducts = $query->count();

        if ($totalProducts === 0) {
            $this->warn('⚠️  لا توجد منتجات تحتاج للتحسين');
            return 0;
        }

        $this->info("📊 عدد المنتجات: {$totalProducts}");
        
        if (!$this->confirm('هل تريد المتابعة؟', true)) {
            $this->warn('تم الإلغاء');
            return 0;
        }

        $bar = $this->output->createProgressBar($totalProducts);
        $bar->start();

        $count = 0;
        $query->chunk(50, function ($products) use ($bar, &$count) {
            foreach ($products as $product) {
                if ($product->image) {
                    OptimizeProductImages::dispatch($product->id);
                    $count++;
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ تم إضافة {$count} منتج إلى قائمة التحسين!");
        $this->info('💡 تأكد من تشغيل queue worker: php artisan queue:work');

        return 0;
    }
}
