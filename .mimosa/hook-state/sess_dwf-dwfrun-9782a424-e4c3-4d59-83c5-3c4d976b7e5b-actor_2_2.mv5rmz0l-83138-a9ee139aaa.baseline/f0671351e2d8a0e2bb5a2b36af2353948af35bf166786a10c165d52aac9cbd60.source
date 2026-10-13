<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('شماره')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->label('مشتری')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('محصول'),
                TextColumn::make('status')
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
                TextColumn::make('total')
                    ->label('مبلغ کل')
                    ->money()
                    ->sortable(),
                TextColumn::make('ordered_at')
                    ->label('زمان سفارش')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار پرداخت',
                        'paid' => 'پرداخت‌شده',
                        'shipped' => 'ارسال‌شده',
                        'cancelled' => 'لغوشده',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }
}
