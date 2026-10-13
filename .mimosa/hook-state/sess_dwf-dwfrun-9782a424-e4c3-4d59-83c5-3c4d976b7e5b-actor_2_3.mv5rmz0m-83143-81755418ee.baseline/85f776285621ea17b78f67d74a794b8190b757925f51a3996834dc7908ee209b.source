<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use NabuXUI\Filament\Forms\Components\NxChipFilter;
use NabuXUI\Filament\Forms\Components\NxCombobox;
use NabuXUI\Filament\Forms\Components\NxNumberField;
use NabuXUI\Filament\Forms\Components\NxTimePicker;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->label('شماره سفارش')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('customer_name')
                    ->label('نام مشتری')
                    ->required(),
                NxCombobox::make('product_id')
                    ->label('محصول')
                    ->placeholder('جست‌وجوی محصول…')
                    ->options(fn (): array => Product::pluck('name', 'id')->all())
                    ->required(),
                NxChipFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار پرداخت',
                        'paid' => 'پرداخت‌شده',
                        'shipped' => 'ارسال‌شده',
                        'cancelled' => 'لغوشده',
                    ])
                    ->default('pending')
                    ->required(),
                NxNumberField::make('total')
                    ->label('مبلغ کل')
                    ->min(0)
                    ->step(0.01)
                    ->rule('numeric')
                    ->required(),
                NxTimePicker::make('ordered_at')
                    ->label('ساعت سفارش')
                    ->minuteStep(5),
            ]);
    }
}
