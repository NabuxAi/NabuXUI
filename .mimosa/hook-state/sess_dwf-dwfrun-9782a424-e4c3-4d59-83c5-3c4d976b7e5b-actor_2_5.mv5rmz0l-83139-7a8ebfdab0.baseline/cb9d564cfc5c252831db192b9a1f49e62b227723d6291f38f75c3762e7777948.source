<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use NabuXUI\Filament\Forms\Components\NxRadioGroup;
use NabuXUI\Filament\Forms\Components\NxSegmented;
use NabuXUI\Filament\Forms\Components\NxTimePicker;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                NxRadioGroup::make('status')
                    ->label('وضعیت')
                    ->options([
                        'todo' => 'در انتظار',
                        'doing' => 'در حال انجام',
                        'done' => 'انجام شد',
                    ])
                    ->default('todo')
                    ->required(),
                NxSegmented::make('priority')
                    ->label('اولویت')
                    ->options([
                        'low' => 'کم',
                        'medium' => 'متوسط',
                        'high' => 'زیاد',
                    ])
                    ->default('medium')
                    ->required(),
                TextInput::make('assignee_name')
                    ->label('مسئول'),
                NxTimePicker::make('due_at')
                    ->label('سررسید')
                    ->minuteStep(5),
            ]);
    }
}
