<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('اسم المنتج'),
                
                TextEntry::make('slug')
                    ->label('الرابط')
                    ->copyable(),
                
                TextEntry::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),
                
                TextEntry::make('features')
                    ->label('المميزات')
                    ->formatStateUsing(fn ($state) => $state ? nl2br($state) : 'لا توجد مميزات محددة')
                    ->html()
                    ->columnSpanFull(),
                
                TextEntry::make('price')
                    ->label('السعر الحالي')
                    ->money('EGP'),
                
                TextEntry::make('original_price')
                    ->label('السعر الأصلي')
                    ->money('EGP')
                    ->placeholder('غير محدد'),
                
                TextEntry::make('discount_percentage')
                    ->label('نسبة الخصم')
                    ->suffix('%')
                    ->getStateUsing(fn ($record) => $record->discount_percentage ?: 'لا يوجد خصم'),
                
                ImageEntry::make('image')
                    ->label('الصورة الرئيسية')
                    ->disk('public')
                    ->columnSpanFull(),
                
                ImageEntry::make('gallery')
                    ->label('معرض الصور')
                    ->disk('public')
                    ->columnSpanFull(),
                
                IconEntry::make('is_active')
                    ->label('حالة المنتج')
                    ->boolean(),
                
                TextEntry::make('sort_order')
                    ->label('ترتيب العرض'),
                
                TextEntry::make('meta_title')
                    ->label('عنوان SEO')
                    ->placeholder('غير محدد')
                    ->columnSpanFull(),
                
                TextEntry::make('meta_description')
                    ->label('وصف SEO')
                    ->placeholder('غير محدد')
                    ->columnSpanFull(),
                
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i'),
                
                TextEntry::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->columns(2);
    }
}
