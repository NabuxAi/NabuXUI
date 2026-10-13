<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use NabuXUI\Filament\Forms\Components\NxNumberField;
use NabuXUI\Filament\Forms\Components\NxSegmented;
use NabuXUI\Filament\Forms\Components\NxSelect;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام محصول')
                    ->required(),
                Textarea::make('description')
                    ->label('توضیحات')
                    ->columnSpanFull(),
                NxNumberField::make('price')
                    ->label('قیمت')
                    ->min(0)
                    ->step(0.5)
                    ->required(),
                NxNumberField::make('stock')
                    ->label('موجودی')
                    ->min(0)
                    ->step(1)
                    ->default(0)
                    ->required(),
                NxSegmented::make('status')
                    ->label('وضعیت')
                    ->options([
                        'draft' => 'پیش‌نویس',
                        'active' => 'فعال',
                        'archived' => 'بایگانی',
                    ])
                    ->default('draft')
                    ->required(),
                NxSelect::make('category')
                    ->label('دسته')
                    ->placeholder('انتخاب کنید…')
                    ->options([
                        'hardware' => 'سخت‌افزار',
                        'software' => 'نرم‌افزار',
                        'service' => 'خدمات',
                    ])
                    ->required(),
            ]);
    }
}
