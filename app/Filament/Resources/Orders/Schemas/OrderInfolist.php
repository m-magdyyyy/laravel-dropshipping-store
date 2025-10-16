<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('رقم الطلب'),
                TextEntry::make('product.name')
                    ->label('المنتج')
                    ->default('غير محدد'),
                TextEntry::make('quantity')
                    ->label('الكمية'),
                TextEntry::make('total_price')
                    ->label('المبلغ الإجمالي')
                    ->getStateUsing(fn ($record) => $record->product ? number_format($record->product->price * $record->quantity, 0) . ' ج.م' : 'غير محدد'),
                TextEntry::make('customer_name')
                    ->label('اسم العميل'),
                TextEntry::make('phone')
                    ->label('رقم الهاتف')
                    ->copyable(),
                TextEntry::make('address')
                    ->label('العنوان')
                    ->columnSpanFull(),
                TextEntry::make('governorate')
                    ->label('المحافظة'),
                TextEntry::make('status')
                    ->label('حالة الطلب')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'shipped' => 'تم الشحن',
                        'delivered' => 'تم التسليم',
                        'cancelled' => 'ملغي',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextEntry::make('notes')
                    ->label('ملاحظات')
                    ->columnSpanFull()
                    ->placeholder('لا توجد ملاحظات'),
                TextEntry::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime('d/m/Y H:i'),
                TextEntry::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
