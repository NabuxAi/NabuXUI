<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام محصول')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'پیش‌نویس',
                        'active' => 'فعال',
                        'archived' => 'بایگانی',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): Heroicon => match ($state) {
                        'draft' => Heroicon::OutlinedPencilSquare,
                        'active' => Heroicon::OutlinedCheckBadge,
                        'archived' => Heroicon::OutlinedArchiveBox,
                        default => Heroicon::OutlinedQuestionMarkCircle,
                    })
                    ->searchable(),
                TextColumn::make('price')
                    ->label('قیمت')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('موجودی')
                    ->numeric()
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 5 => 'danger',
                        $state < 20 => 'warning',
                        default => 'success',
                    })
                    ->icon(fn (int $state): Heroicon => match (true) {
                        $state <= 5 => Heroicon::OutlinedExclamationTriangle,
                        default => Heroicon::OutlinedCheckCircle,
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'draft' => 'پیش‌نویس',
                        'active' => 'فعال',
                        'archived' => 'بایگانی',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
