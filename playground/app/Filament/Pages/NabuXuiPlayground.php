<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use NabuXUI\Filament\Forms\Components\NxCheckbox;
use NabuXUI\Filament\Forms\Components\NxChipFilter;
use NabuXUI\Filament\Forms\Components\NxCombobox;
use NabuXUI\Filament\Forms\Components\NxMultiSelect;
use NabuXUI\Filament\Forms\Components\NxNumberField;
use NabuXUI\Filament\Forms\Components\NxOtp;
use NabuXUI\Filament\Forms\Components\NxRadioGroup;
use NabuXUI\Filament\Forms\Components\NxRating;
use NabuXUI\Filament\Forms\Components\NxSegmented;
use NabuXUI\Filament\Forms\Components\NxSelect;
use NabuXUI\Filament\Forms\Components\NxSlider;
use NabuXUI\Filament\Forms\Components\NxTagInput;
use NabuXUI\Filament\Forms\Components\NxTimePicker;
use NabuXUI\Filament\Forms\Components\NxToggle;

/**
 * The live demo of the nabuxui-filament fields: every nx control bound to
 * Filament state on one panel page. Saving echoes the state back so the
 * binding is provable at a glance.
 */
class NabuXuiPlayground extends Page
{
    protected string $view = 'filament.pages.nabuxui-playground';

    protected static ?string $navigationLabel = 'NabuXUI fields';

    protected static ?string $title = 'NabuXUI fields';

    /** @var array<string, mixed> */
    public array $saved = [];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components($this->demoComponents());
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('readState')->label('Read state')->submit('save'),
                    ]),
                ]),
            Section::make('Synced state')
                ->schema([
                    Placeholder::make('saved')
                        ->content(fn (): string => json_encode($this->saved, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)),
                ])
                ->visible(fn (): bool => $this->saved !== []),
        ]);
    }

    /** @return array<Component> */
    protected function demoComponents(): array
    {
        return [
            NxToggle::make('notifications')->label('Notifications')->default(true),
            NxSegmented::make('plan')->label('Plan')->default('pro')
                ->options(['starter' => 'Starter', 'pro' => 'Pro', 'studio' => 'Studio']),
            NxChipFilter::make('channel')->label('Channel')
                ->options(['email' => 'Email', 'sms' => 'SMS', 'push' => 'Push'])
                ->counts(['email' => 12, 'sms' => 3, 'push' => 27]),
            NxSlider::make('volume')->label('Volume')->min(0)->max(100)->step(5)->default(40),
            NxRating::make('score')->label('Score')->max(5)->default(3),
            NxMultiSelect::make('stack')->label('Stack')->placeholder('Pick a few…')
                ->options(['php' => 'PHP', 'typescript' => 'TypeScript', 'vue' => 'Vue', 'swift' => 'Swift']),
            NxTagInput::make('tags')->label('Tags')->suggestions(['Livewire', 'Alpine', 'Filament', 'NabuXUI']),
            NxSelect::make('region')->label('Region')->placeholder('Choose…')
                ->options(['eu' => 'Europe', 'mena' => 'MENA', 'apac' => 'APAC']),
            NxRadioGroup::make('billing')->label('Billing cycle')->default('monthly')
                ->options(['monthly' => 'Monthly', 'yearly' => 'Yearly']),
            NxCheckbox::make('terms')->label('Send me the monthly digest'),
            NxNumberField::make('seats')->label('Seats')->min(1)->max(500)->step(1)->default(12),
            NxCombobox::make('country')->label('Country')->placeholder('Search countries…')
                ->options(['de' => 'Germany', 'tr' => 'Türkiye', 'ae' => 'United Arab Emirates', 'jp' => 'Japan']),
            NxTimePicker::make('deploy_at')->label('Deploy at')->minuteStep(5),
            NxOtp::make('access_code')->label('Access code')->length(6)->alphanumeric(),
            TextInput::make('name')->label('Plain Filament control (for contrast)'),
        ];
    }

    public function save(): void
    {
        $this->saved = $this->form->getState();
    }
}
