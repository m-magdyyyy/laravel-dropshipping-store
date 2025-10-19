<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_path')
                    ->label('الصورة')
                    ->size(60)
                    ->square()
                    ->getStateUsing(fn ($record) => $record->thumb_url)
                    ->defaultImageUrl('https://via.placeholder.com/60x60?text=No+Image'),
                
                TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('slug')
                    ->label('الرابط')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('price')
                    ->label('السعر')
                    ->money('EGP')
                    ->sortable(),
                
                TextColumn::make('original_price')
                    ->label('السعر الأصلي')
                    ->money('EGP')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('discount_percentage')
                    ->label('نسبة الخصم')
                    ->suffix('%')
                    ->getStateUsing(fn ($record) => $record->discount_percentage ?: '-'),
                
                IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('active')
                    ->label('المنتجات النشطة')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
                
                Filter::make('inactive')
                    ->label('المنتجات غير النشطة')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false)),
            ])
            ->defaultSort('sort_order');
    }
}
