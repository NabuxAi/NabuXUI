<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use NabuXUI\Filament\Forms\Components\NxSegmented;
use NabuXUI\Filament\Forms\Components\NxSelect;
use NabuXUI\Filament\Forms\Components\NxSlider;
use NabuXUI\Filament\Forms\Components\NxToggle;
use NabuXUI\NabuXUI;
use UnitEnum;

/**
 * تنظیمات — a settings form that really persists. The four nx controls
 * (toggle, segmented, select, slider) live in a Filament form schema; the
 * «ذخیره» action validates the state, normalizes it against the option
 * keys, and writes it to storage/app/settings.json via the local Storage
 * disk. mount() fills the form back from that file, so the values survive
 * a reload — no database, no migration, just a plain JSON document.
 *
 * None of the four nx fields dispatch events (they bind through
 * wire:model on their state paths — packages/filament/resources/views/
 * forms/*.blade.php), so the only Livewire entry point is the form's own
 * submit handler.
 */
class Settings extends Page
{
    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $navigationLabel = 'تنظیمات';

    protected static ?string $title = 'تنظیمات';

    protected static string|UnitEnum|null $navigationGroup = 'ابزارها';

    /** The JSON document on the local disk that holds the saved values. */
    protected const STORE_PATH = 'settings.json';

    /**
     * The four keys the page owns, with their factory defaults. Anything
     * else found in the file is ignored on read and never written.
     *
     * @var array<string, bool|string|int>
     */
    protected const DEFAULTS = [
        'notifications' => true,
        'density' => 'cozy',
        'timezone' => 'UTC',
        'volume' => 40,
    ];

    /** تراکم رابط: stored as the key, shown as the Persian label. */
    protected const DENSITY_OPTIONS = [
        'compact' => 'فشرده',
        'cozy' => 'معمولی',
        'comfortable' => 'راحت',
    ];

    /** منطقهٔ زمانی: international zones only, keyed by the real IANA name. */
    protected const TIMEZONE_OPTIONS = [
        'UTC' => 'هماهنگ جهانی (UTC)',
        'Europe/Berlin' => 'برلین',
        'Europe/Istanbul' => 'استانبول',
        'Europe/London' => 'لندن',
        'Asia/Dubai' => 'دبی',
        'Asia/Tokyo' => 'توکیو',
        'America/New_York' => 'نیویورک',
        'Australia/Sydney' => 'سیدنی',
    ];

    /** @var array<string, mixed> */
    public ?array $data = [];

    /** The settings as they were last written to the file; null until «ذخیره» runs. */
    public ?array $saved = null;

    /** The wall-clock time of the last save, Persian digits, for the confirmation strip. */
    public ?string $savedAt = null;

    public function mount(): void
    {
        $this->form->fill($this->readSettings());
    }

    public function getSubheading(): string
    {
        return 'ترجیح‌های محیط کار، در فایل تنظیمات محلی ذخیره می‌شوند.';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components($this->settingsFields());
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('saveSettings')->label('ذخیره')->submit('save'),
                    ]),
                ]),
            Section::make('تنظیمات ذخیره شد')
                ->description(fn (): string => 'آخرین ذخیره در ساعت '.$this->savedAt)
                ->schema([
                    Placeholder::make('savedSummary')
                        ->content(fn (): string => $this->summaryText()),
                ])
                ->visible(fn (): bool => $this->saved !== null),
        ]);
    }

    /** The four nx controls, each bound to its key in the stored document. */
    protected function settingsFields(): array
    {
        return [
            NxToggle::make('notifications')
                ->label('اعلان‌ها')
                ->default(self::DEFAULTS['notifications']),
            NxSegmented::make('density')
                ->label('تراکم رابط')
                ->options(self::DENSITY_OPTIONS)
                ->default(self::DEFAULTS['density'])
                ->rule('in:'.implode(',', array_keys(self::DENSITY_OPTIONS))),
            NxSelect::make('timezone')
                ->label('منطقهٔ زمانی')
                ->options(self::TIMEZONE_OPTIONS)
                ->placeholder('انتخاب کنید…')
                ->default(self::DEFAULTS['timezone'])
                ->required()
                ->rule('in:'.implode(',', array_keys(self::TIMEZONE_OPTIONS))),
            // The slider's state arrives as a numeric string (the range
            // input's own contract); save() casts it before it reaches
            // the file, and readSettings() hands mount() an int.
            NxSlider::make('volume')
                ->label('حجم صدا')
                ->min(0)
                ->max(100)
                ->step(5)
                ->default(self::DEFAULTS['volume']),
        ];
    }

    /** Validate, clamp to the known keys, and write the JSON document. */
    public function save(): void
    {
        $state = $this->form->getState();

        $settings = [
            'notifications' => (bool) ($state['notifications'] ?? false),
            'density' => (string) ($state['density'] ?? ''),
            'timezone' => (string) ($state['timezone'] ?? ''),
            'volume' => (int) ($state['volume'] ?? 0),
        ];

        // The form rules already pin density/timezone to their option keys
        // and volume to an integer; this is the last guard before the file.
        if (! isset(self::DENSITY_OPTIONS[$settings['density']])) {
            $settings['density'] = self::DEFAULTS['density'];
        }
        if (! isset(self::TIMEZONE_OPTIONS[$settings['timezone']])) {
            $settings['timezone'] = self::DEFAULTS['timezone'];
        }
        $settings['volume'] = max(0, min(100, $settings['volume']));

        Storage::disk('local')->put(
            self::STORE_PATH,
            json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        );

        $this->saved = $settings;
        // H:i with the locale's digits (Persian digits in the fa panel),
        // via the package's own digit map.
        $this->savedAt = strtr(
            now()->format('H:i'),
            array_combine(range(0, 9), NabuXUI::digits()),
        );
    }

    /**
     * The form's starting point: the saved document, normalized against
     * DEFAULTS so a missing, corrupt, or hand-edited file can never break
     * mount().
     *
     * @return array{notifications: bool, density: string, timezone: string, volume: int}
     */
    protected function readSettings(): array
    {
        $stored = null;

        if (Storage::disk('local')->exists(self::STORE_PATH)) {
            $stored = json_decode((string) Storage::disk('local')->get(self::STORE_PATH), true);
        }

        $stored = is_array($stored) ? $stored : [];

        return [
            'notifications' => is_bool($stored['notifications'] ?? null)
                ? $stored['notifications']
                : self::DEFAULTS['notifications'],
            'density' => is_string($stored['density'] ?? null) && isset(self::DENSITY_OPTIONS[$stored['density']])
                ? $stored['density']
                : self::DEFAULTS['density'],
            'timezone' => is_string($stored['timezone'] ?? null) && isset(self::TIMEZONE_OPTIONS[$stored['timezone']])
                ? $stored['timezone']
                : self::DEFAULTS['timezone'],
            'volume' => is_numeric($stored['volume'] ?? null)
                ? max(0, min(100, (int) $stored['volume']))
                : self::DEFAULTS['volume'],
        ];
    }

    /** The saved values as one Persian line with Persian digits for the volume. */
    protected function summaryText(): string
    {
        return sprintf(
            'اعلان‌ها: %s · تراکم رابط: %s · منطقهٔ زمانی: %s · حجم صدا: %s',
            $this->saved['notifications'] ? 'روشن' : 'خاموش',
            self::DENSITY_OPTIONS[$this->saved['density']] ?? $this->saved['density'],
            self::TIMEZONE_OPTIONS[$this->saved['timezone']] ?? $this->saved['timezone'],
            NabuXUI::formatNumber((int) $this->saved['volume']),
        );
    }
}
