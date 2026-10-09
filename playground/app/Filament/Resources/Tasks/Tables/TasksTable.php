<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('اولویت')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'high' => 'زیاد',
                        'medium' => 'متوسط',
                        'low' => 'کم',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger', // قرمز
                        'medium' => 'warning', // نارنجی
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->icon(fn (string $state): Heroicon => match ($state) {
                        'todo' => Heroicon::OutlinedClock,
                        'doing' => Heroicon::OutlinedArrowPath,
                        'done' => Heroicon::OutlinedCheckCircle,
                        default => Heroicon::OutlinedQuestionMarkCircle,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'todo' => 'gray',
                        'doing' => 'info',
                        'done' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'todo' => 'در انتظار',
                        'doing' => 'در حال انجام',
                        'done' => 'انجام شد',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('assignee_name')
                    ->label('مسئول')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('due_at')
                    ->label('سررسید')
                    ->date()
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'todo' => 'در انتظار',
                        'doing' => 'در حال انجام',
                        'done' => 'انجام شد',
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
            ]);
    }
}
