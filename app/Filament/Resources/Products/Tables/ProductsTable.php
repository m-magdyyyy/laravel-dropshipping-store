<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                ImageColumn::make('image_url')
                    ->label('الصورة')
                    ->size(60)
                    ->getStateUsing(function ($record) {
                        $state = $record->image_url;
                        if (!$state) {
                            return 'https://via.placeholder.com/60x60?text=No+Image';
                        }

                        // Prefer APP_URL (deployment dev override) otherwise use current request host
                        $host = config('app.url') ?: request()->getSchemeAndHttpHost();

                        // If state is absolute (http(s)), normalize to current host but keep path/query
                        if (str_starts_with($state, 'http')) {
                            $parts = parse_url($state);
                            $path = $parts['path'] ?? '/';
                            if (isset($parts['query'])) {
                                $path .= '?' . $parts['query'];
                            }
                            return rtrim($host, '\/') . $path;
                        }

                        // Otherwise state is relative; build absolute using current host
                        return rtrim($host, '\/') . '/' . ltrim($state, '\/');
                    })
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
