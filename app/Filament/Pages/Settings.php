<?php

namespace App\Filament\Pages;

use Filament\Pages\Actions\ButtonAction;
use Filament\Pages\Page;
use Filament\Forms;

class Settings extends Page
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $navigationGroup = 'الإعدادات';

    protected static ?string $navigationLabel = 'الإعدادات';
    protected static ?string $title = 'الإعدادات';
    protected static ?string $slug = 'settings';
    protected static ?string $label = 'الإعدادات';
    protected static ?string $pluralLabel = 'الإعدادات';







    public function mount(): void
    {
        $this->form->fill([
            'company.name' => setting('company.name', ''),
            'company.phone' => setting('company.phone', ''),
            'company.email' => setting('company.email', ''),
            'company.website' => setting('company.website', ''),
            'company.fax' => setting('company.fax', ''),
            'company.address' => setting('company.address', ''),
            'company.city' => setting('company.city', ''),
            'company.state' => setting('company.state', ''),
            'company.postcode' => setting('company.postcode', ''),
            'company.country' => setting('company.country', ''),
            'company.employee_number_prefix' => setting('company.employee_number_prefix', ''),
            'company.currency' => setting('company.currency', ''),
            'company.site_name' => setting('company.site_name', ''),
        ]);
    }

    public function submit(): void
    {
        setting(['company.name' => $this->form->getState()['company']['name']]);
        setting(['company.phone' => $this->form->getState()['company']['phone']]);
        setting(['company.email' => $this->form->getState()['company']['email']]);
        setting(['company.website' => $this->form->getState()['company']['website']]);
        setting(['company.fax' => $this->form->getState()['company']['fax']]);
        setting(['company.address' => $this->form->getState()['company']['address']]);
        setting(['company.city' => $this->form->getState()['company']['city']]);
        setting(['company.state' => $this->form->getState()['company']['state']]);
        setting(['company.postcode' => $this->form->getState()['company']['postcode']]);
        setting(['company.country' => $this->form->getState()['company']['country']]);
        setting(['company.employee_number_prefix' => $this->form->getState()['company']['employee_number_prefix']]);
        setting(['company.currency' => $this->form->getState()['company']['currency']]);
        setting(['company.site_name' => $this->form->getState()['company']['site_name']]);
        setting()->save();
    }


    protected function getActions(): array
    {
        return [
            ButtonAction::make('settings')->action('openSettingsModal'),
        ];
    }

    public function openSettingsModal(): void
    {
        $this->dispatchBrowserEvent('open-settings-modal');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Tabs::make('الإعدادات')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('تفاصيل الشركة')
                        ->schema([
                            Forms\Components\TextInput::make('company.name')
                                ->label('اسم الشركة')
                                ->required(),
                            Forms\Components\TextInput::make('company.phone')
                                ->label('رقم الهاتف')
                                ->required(),
                            Forms\Components\TextInput::make('company.email')
                                ->label('البريد الإلكتروني')
                                ->required(),
                            Forms\Components\TextInput::make('company.website')
                                ->label('الموقع الإلكتروني')
                                ->required(),
                            Forms\Components\TextInput::make('company.fax')
                                ->label('الفاكس')
                                ->required(),
                            Forms\Components\TextInput::make('company.address')
                                ->label('العنوان')
                                ->required(),
                            Forms\Components\TextInput::make('company.city')
                                ->label('المدينة')
                                ->required(),
                            Forms\Components\TextInput::make('company.state')
                                ->label('المحافظة')
                                ->required(),
                            Forms\Components\TextInput::make('company.postcode')
                                ->label('الرمز البريدي')
                                ->required(),
                            Forms\Components\TextInput::make('company.country')
                                ->label('الدولة')

                                ->required(),
                        ]),
                    Forms\Components\Tabs\Tab::make('System')
                        ->label('النظام')
                        ->schema([
                            Forms\Components\TextInput::make('company.employee_number_prefix')
                                ->label('رقم الموظف')
                                ->required(),
                            Forms\Components\Select::make('company.currency')
                                ->label('العملة')
                                ->options([
                                    'EGP' => 'EGP',
                                ])
                                ->required(),
                        ]),
                    Forms\Components\Tabs\Tab::make('Logo and Title')
                        ->label('الشعار والعنوان')
                        ->schema([
                            Forms\Components\TextInput::make('company.site_name')->required()
                                ->label('اسم الموقع'),
                        ]),
                ]),
        ];
    }
}
