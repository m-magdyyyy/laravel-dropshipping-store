<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\Order;
use App\Models\Product;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('المنتج')
                    ->options(Product::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                
                TextInput::make('quantity')
                    ->label('الكمية')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(10)
                    ->required(),
                
                TextInput::make('customer_name')
                    ->label('اسم العميل')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Textarea::make('address')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(1000)
                    ->rows(3)
                    ->columnSpanFull(),
                
                Select::make('governorate')
                    ->label('المحافظة')
                    ->options([
                        'القاهرة' => 'القاهرة',
                        'الجيزة' => 'الجيزة',
                        'الإسكندرية' => 'الإسكندرية',
                        'الدقهلية' => 'الدقهلية',
                        'الشرقية' => 'الشرقية',
                        'القليوبية' => 'القليوبية',
                        'كفر الشيخ' => 'كفر الشيخ',
                        'الغربية' => 'الغربية',
                        'المنوفية' => 'المنوفية',
                        'البحيرة' => 'البحيرة',
                        'بني سويف' => 'بني سويف',
                        'الفيوم' => 'الفيوم',
                        'المنيا' => 'المنيا',
                        'أسيوط' => 'أسيوط',
                        'سوهاج' => 'سوهاج',
                        'قنا' => 'قنا',
                        'أسوان' => 'أسوان',
                        'الأقصر' => 'الأقصر',
                        'البحر الأحمر' => 'البحر الأحمر',
                        'الوادي الجديد' => 'الوادي الجديد',
                        'مطروح' => 'مطروح',
                        'شمال سيناء' => 'شمال سيناء',
                        'جنوب سيناء' => 'جنوب سيناء',
                        'بورسعيد' => 'بورسعيد',
                        'دمياط' => 'دمياط',
                        'الإسماعيلية' => 'الإسماعيلية',
                        'السويس' => 'السويس',
                    ])
                    ->searchable(),
                Select::make('status')
                    ->label('حالة الطلب')
                    ->options(Order::getStatuses())
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->label('ملاحظات')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
