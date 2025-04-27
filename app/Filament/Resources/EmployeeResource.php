<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'الموظفين';




    protected static ?string $navigationLabel = 'الموظفين';
    protected static ?string $title = 'الموظفين';
    protected static ?string $label = ' موظف';
    protected static ?string $pluralLabel = ' الموظفين';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('avatar')->collection('avatars')->columns(1)->label('صورة شخصية'),
                Forms\Components\TextInput::make('first_name')
                    ->label('الاسم الأول')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->label('اسم الأب')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->label('اسم العائلة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('dob')
                    ->label('تاريخ الميلاد')
                    ->default(now()->subYears(18))
                    ->withoutTime()
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->label('الجنس')
                    ->placeholder('اختر الجنس')
                    ->required()
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'انثي',
                    ]),
                Forms\Components\TextInput::make('phone_1')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_2')
                    ->label('رقم الهاتف الثاني')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('current_address')
                    ->label('العنوان الحالي')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('permanent_address')
                    ->label('العنوان الدائم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('nationality')
                    ->label('الجنسية')
                    ->required()
                    ->options([
                        'egy' => 'مصري',
                    ]),
                Forms\Components\TextInput::make('reference_name_1')
                    ->label('اسم المرجع الأول')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference_phone_1')
                    ->label('رقم هاتف المرجع الأول')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference_name_2')
                    ->label('اسم المرجع الثاني')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference_phone_2')
                    ->label('رقم هاتف المرجع الثاني')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Select::make('marital_status')
                    ->label('الحالة الاجتماعية')
                    ->placeholder('اختر الحالة الاجتماعية')
                    ->required()
                    ->options([
                        'married' => 'متزوج',
                        'single' => 'عازب'
                    ]),
                Forms\Components\Textarea::make('comment')
                    ->label('ملاحظات')
                    ->required()
                    ->maxLength(65535),
                SpatieMediaLibraryFileUpload::make('Documents')
                    ->label('مستندات الموظف')
                    ->collection('employee-documents')
                    ->multiple()
                    ->minFiles(1)
                    ->maxFiles(5),
                Forms\Components\Select::make('user_id')
                    ->label('المستخدم')
                    ->placeholder('اختر المستخدم')
                    ->required()
                    ->options(User::all()->pluck('name', 'id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatars')
                    ->label('صورة شخصية')
                    ->conversion('thumb')->rounded(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('الاسم الكامل')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('dob')
                    ->label('تاريخ الميلاد')
                    ->date(),
                Tables\Columns\TextColumn::make('gender')->label('الجنس'),
                Tables\Columns\TextColumn::make('nationality')->label('الجنسية'),
                Tables\Columns\TextColumn::make('marital_status')->label('الحالة الاجتماعية'),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ التسجيل')
                    ->dateTime(),
            ])
            // ->pushBulkActions([
            //     ExportAction::make('export')
            //         ->icon('heroicon-o-document-download')
            //         ->label('Export Data') // Button label
            //         ->withWriterType(Excel::CSV) // Export type: CSV, XLS, XLSX
            //         ->except('updated_at') // Exclude fields
            //         ->withFilename('employee list') // Set a filename
            //         ->withHeadings() // Get headings from table or form
            //         ->askForFilename(date('Y-m-d') . '-export') // Let the user choose a filename. You may pass a default.
            //         ->askForWriterType(Excel::XLS)  // Let the user choose an export type. You may pass a default.
            //         ->allFields() // Export all fields on model,
            // ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('recorded_at')
                            ->placeholder(fn($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['recorded_at'],
                                fn(Builder $query, $date): Builder => $query->whereDate('recorded_at', '=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('marital status')
                    ->form([
                        Forms\Components\Select::make('marital_status')
                            ->options([
                                'married' => 'Married',
                                'single' => 'Single'
                            ])
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['marital_status'],
                                fn(Builder $query, $value): Builder => $query->where('marital_status', '=', $value),
                            );
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BankAccountDetailsRelationManager::class,
            RelationManagers\CompanyDetailsRelationManager::class,
            RelationManagers\FinancialDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}