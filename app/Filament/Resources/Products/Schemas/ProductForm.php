<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

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
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                    ->maxSize(2048)
                    ->helperText('اختر صورة المنتج من جهازك (JPG, PNG, WebP - حد أقصى 2MB)')
                    ->columnSpanFull(),
                
                FileUpload::make('gallery')
                    ->label('معرض الصور')
                    ->multiple()
                    ->image()
                    ->directory('products/gallery')
                    ->reorderable()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                    ->maxSize(2048)
                    ->maxFiles(5)
                    ->helperText('اختر صور إضافية للمنتج (حد أقصى 5 صور، كل صورة 2MB)')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('المنتج نشط')
                    ->default(true)
                    ->columnSpan(1),
                
                TextInput::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0)
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
