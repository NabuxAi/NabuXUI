<?php

namespace App\Filament\Resources;

use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\AvatarProviders\UiAvatarsProvider;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'کاربران';

    protected static ?string $navigationLabel = 'کاربران';

    protected static ?string $modelLabel = 'کاربر';

    protected static ?string $pluralModelLabel = 'کاربران';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        // Only the real columns of the users table (name, email); there is no
        // is_active / user_type column in this app, so nothing hypothetical.
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('avatar')
                    ->label('آواتار')
                    ->circular()
                    ->defaultImageUrl(fn (User $record): string => (new UiAvatarsProvider)->get($record)),
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('تأیید ایمیل')
                    ->badge()
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? 'تأییدشده' : 'تأییدنشده')
                    ->color(fn (User $record): string => filled($record->email_verified_at) ? 'success' : 'warning')
                    ->icon(fn (User $record): Heroicon => filled($record->email_verified_at) ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedXCircle),
                TextColumn::make('created_at')
                    ->label('تاریخ عضویت')
                    ->date('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('وضعیت تأیید ایمیل')
                    ->nullable()
                    ->trueLabel('تأییدشده')
                    ->falseLabel('تأییدنشده'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
