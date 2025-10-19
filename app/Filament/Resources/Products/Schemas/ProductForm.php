<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم المنتج')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),
                
                TextInput::make('slug')
                    ->label('الرابط (Slug)')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('سيتم إنشاؤه تلقائياً من اسم المنتج إذا تُرك فارغاً')
                    ->columnSpan(1),
                
                Textarea::make('description')
                    ->label('الوصف')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                
                Textarea::make('features')
                    ->label('المميزات')
                    ->helperText('أدخل مميزات المنتج، كل ميزة في سطر منفصل')
                    ->rows(4)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('السعر الحالي')
                    ->required()
                    ->numeric()
                    ->prefix('ج.م')
                    ->columnSpan(1),
                
                TextInput::make('original_price')
                    ->label('السعر الأصلي')
                    ->numeric()
                    ->prefix('ج.م')
                    ->helperText('السعر قبل الخصم (اختياري)')
                    ->columnSpan(1),
                
                FileUpload::make('image')
                    ->label('صورة المنتج')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->visibility('public')
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->preserveFilenames()
                    ->openable()
                    ->downloadable()
                    ->maxSize(51200) // 50MB in KB
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])
                    ->deleteUploadedFileUsing(fn (string $file) => Storage::disk('public')->delete($file))
                    ->helperText('الحد الأقصى لحجم الملف: 50 ميجابايت')
                    ->mutateDehydratedStateUsing(function ($state) {
                        // تسجيل الحالة للتصحيح
                        Log::info('FileUpload mutateDehydratedStateUsing called', [
                            'state_type' => gettype($state),
                            'state_value' => $state
                        ]);

                        // إذا لم يكن هناك ملف مرفوع، أرجع الحالة كما هي
                        if (!$state) {
                            return $state;
                        }

                        // إذا كان الملف عبارة عن URL (مثل صورة موجودة بالفعل)، أرجع كما هو
                        if (is_string($state) && (str_starts_with($state, 'http') || str_starts_with($state, 'https'))) {
                            return $state;
                        }

                        // إذا كان الملف عبارة عن مصفوفة (ملف مرفوع جديد)
                        if (is_array($state) && isset($state['path'])) {
                            try {
                                Log::info('Processing new uploaded image', ['path' => $state['path']]);

                                // إنشاء اسم فريد جديد للصورة
                                $newFileName = time() . '_' . uniqid() . '.webp';

                                // إنشاء مسار الملف الأصلي والجديد
                                $originalPath = Storage::disk('public')->path($state['path']);
                                $newPath = 'products/' . $newFileName;

                                // إنشاء ImageManager
                                $manager = new ImageManager(new Driver());

                                // قراءة الصورة الأصلية
                                $image = $manager->read($originalPath);

                                // تصغير الصورة لعرض 1200 بكسل مع الحفاظ على النسبة
                                $image->scaleDown(width: 1200);

                                // حفظ الصورة كـ WebP بجودة 85%
                                $processedImage = $image->toWebp(85);

                                // حفظ الصورة المعالجة في storage
                                Storage::disk('public')->put($newPath, (string) $processedImage);

                                // حذف الملف الأصلي المرفوع
                                Storage::disk('public')->delete($state['path']);

                                Log::info('Image processed successfully', [
                                    'original' => $state['path'],
                                    'new' => $newPath,
                                    'original_size' => filesize($originalPath) ?? 'unknown',
                                    'new_size' => strlen((string) $processedImage)
                                ]);

                                // إرجاع اسم الملف الجديد فقط (سيتم حفظه في قاعدة البيانات)
                                return $newPath;

                            } catch (\Exception $e) {
                                // في حالة حدوث خطأ، احتفظ بالملف الأصلي
                                Log::error('Image processing failed in FileUpload', [
                                    'error' => $e->getMessage(),
                                    'file' => $state['path'] ?? 'unknown'
                                ]);
                                return $state['path'] ?? $state;
                            }
                        }

                        // إذا كان الملف عبارة عن string (اسم ملف محفوظ)
                        if (is_string($state) && !str_starts_with($state, 'http')) {
                            // هذا قد يكون اسم ملف تم حفظه بالفعل، تحقق من وجوده
                            $filePath = Storage::disk('public')->path($state);
                            if (Storage::disk('public')->exists($state) && !str_ends_with($state, '.webp')) {
                                try {
                                    Log::info('Processing existing image file', ['path' => $state]);

                                    // إنشاء اسم فريد جديد للصورة
                                    $newFileName = time() . '_' . uniqid() . '.webp';
                                    $newPath = 'products/' . $newFileName;

                                    // إنشاء ImageManager
                                    $manager = new ImageManager(new Driver());

                                    // قراءة الصورة الأصلية
                                    $image = $manager->read($filePath);

                                    // تصغير الصورة لعرض 1200 بكسل مع الحفاظ على النسبة
                                    $image->scaleDown(width: 1200);

                                    // حفظ الصورة كـ WebP بجودة 85%
                                    $processedImage = $image->toWebp(85);

                                    // حفظ الصورة المعالجة في storage
                                    Storage::disk('public')->put($newPath, (string) $processedImage);

                                    // حذف الملف الأصلي
                                    Storage::disk('public')->delete($state);

                                    Log::info('Existing image processed successfully', [
                                        'original' => $state,
                                        'new' => $newPath
                                    ]);

                                    return $newPath;
                                } catch (\Exception $e) {
                                    Log::error('Failed to process existing image', [
                                        'error' => $e->getMessage(),
                                        'file' => $state
                                    ]);
                                    return $state;
                                }
                            }
                        }

                        // في جميع الحالات الأخرى، أرجع الحالة كما هي
                        return $state;
                    })
                    ->columnSpanFull(),

                FileUpload::make('gallery')
                    ->label('معرض الصور')
                    ->multiple()
                    ->image()
                    ->directory('products/gallery')
                    ->disk('public')
                    ->visibility('public')
                    ->reorderable()
                    ->appendFiles()
                    ->maxFiles(5)
                    ->preserveFilenames()
                    ->openable()
                    ->downloadable()
                    ->maxSize(51200) // 50MB in KB
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])
                    ->deleteUploadedFileUsing(fn (string $file) => Storage::disk('public')->delete($file))
                    ->helperText('الحد الأقصى: 5 صور، كل صورة حتى 50 ميجابايت')
                    ->mutateDehydratedStateUsing(function ($state) {
                        // تسجيل الحالة للتصحيح
                        Log::info('Gallery FileUpload mutateDehydratedStateUsing called', [
                            'state_type' => gettype($state),
                            'state_value' => is_array($state) ? count($state) . ' files' : $state
                        ]);

                        // إذا لم يكن هناك ملفات مرفوعة، أرجع الحالة كما هي
                        if (!$state || !is_array($state)) {
                            return $state;
                        }

                        $processedFiles = [];

                        foreach ($state as $file) {
                            // إذا كان الملف عبارة عن URL (مثل صورة موجودة بالفعل)، أضفه كما هو
                            if (is_string($file) && (str_starts_with($file, 'http') || str_starts_with($file, 'https'))) {
                                $processedFiles[] = $file;
                                continue;
                            }

                            // إذا كان الملف عبارة عن مصفوفة (ملف مرفوع جديد)
                            if (is_array($file) && isset($file['path'])) {
                                try {
                                    Log::info('Processing new gallery image', ['path' => $file['path']]);

                                    // إنشاء اسم فريد جديد للصورة
                                    $newFileName = time() . '_' . uniqid() . '.webp';

                                    // إنشاء مسار الملف الأصلي والجديد
                                    $originalPath = Storage::disk('public')->path($file['path']);
                                    $newPath = 'products/gallery/' . $newFileName;

                                    // إنشاء ImageManager
                                    $manager = new ImageManager(new Driver());

                                    // قراءة الصورة الأصلية
                                    $image = $manager->read($originalPath);

                                    // تصغير الصورة لعرض 1200 بكسل مع الحفاظ على النسبة
                                    $image->scaleDown(width: 1200);

                                    // حفظ الصورة كـ WebP بجودة 85%
                                    $processedImage = $image->toWebp(85);

                                    // حفظ الصورة المعالجة في storage
                                    Storage::disk('public')->put($newPath, (string) $processedImage);

                                    // حذف الملف الأصلي المرفوع
                                    Storage::disk('public')->delete($file['path']);

                                    // إضافة اسم الملف الجديد إلى المصفوفة
                                    $processedFiles[] = $newPath;

                                } catch (\Exception $e) {
                                    // في حالة حدوث خطأ، احتفظ بالملف الأصلي
                                    Log::error('Image processing failed in gallery FileUpload', [
                                        'error' => $e->getMessage(),
                                        'file' => $file['path'] ?? 'unknown'
                                    ]);
                                    $processedFiles[] = $file['path'] ?? $file;
                                }
                            } else {
                                // في الحالات الأخرى، أضف الملف كما هو
                                $processedFiles[] = $file;
                            }
                        }

                        return $processedFiles;
                    })
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('المنتج نشط')
                    ->default(true)
                    ->columnSpan(1),
                
                TextInput::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0)
                    ->dehydrateStateUsing(fn ($state) => ($state === null || $state === '') ? 0 : (int) $state)
                    ->minValue(0)
                    ->columnSpan(1),
                
                TextInput::make('meta_title')
                    ->label('عنوان SEO')
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                Textarea::make('meta_description')
                    ->label('وصف SEO')
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
