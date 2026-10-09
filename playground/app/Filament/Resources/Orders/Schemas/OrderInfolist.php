<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('number')
                    ->label('شماره سفارش'),
                TextEntry::make('customer_name')
                    ->label('نام مشتری'),
                TextEntry::make('product.name')
                    ->label('محصول'),
                TextEntry::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->colors([
                        'pending' => Color::Amber,
                        'paid' => Color::Emerald,
                        'shipped' => Color::Sky,
                        'cancelled' => Color::Red,
                    ])
                    ->icons([
                        'pending' => Heroicon::OutlinedClock,
                        'paid' => Heroicon::OutlinedCheckCircle,
                        'shipped' => Heroicon::OutlinedTruck,
                        'cancelled' => Heroicon::OutlinedXCircle,
                    ]),
                TextEntry::make('total')
                    ->label('مبلغ کل')
                    ->money(),
                TextEntry::make('ordered_at')
                    ->label('ساعت سفارش')
                    ->since(),
                TextEntry::make('created_at')
                    ->label('ایجاد')
                    ->dateTime()
                    ->badge(),
                TextEntry::make('updated_at')
                    ->label('به‌روزرسانی')
                    ->dateTime(),
            ]);
    }
}
