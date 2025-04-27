<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerformanceResource\Pages;
use App\Filament\Resources\PerformanceResource\RelationManagers;
use App\Models\Employee;
use App\Models\Performance;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class PerformanceResource extends Resource
{
    protected static ?string $model = Performance::class;

    protected static ?string $navigationIcon = 'heroicon-o-lightning-bolt';

    protected static ?string $navigationGroup = 'الموظفين';




    protected static ?string $navigationLabel = 'الأداء';
    protected static ?string $title = 'الأداء';
    protected static ?string $label = 'أداء';
    protected static ?string $pluralLabel = 'الأداء';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('الموظف')
                    ->placeholder('اختر الموظف')
                    ->options(Employee::all()->pluck('full_name', 'id'))
                    ->required(),
                Forms\Components\Select::make('year')
                    ->placeholder('اختر السنة')
                    ->default(now()->year)
                    ->label('السنة')
                    ->options(array_combine(range(date("Y"), 2010), range(date("Y"), 2010)))
                    ->required(),
                Forms\Components\Select::make('month')
                    ->label('الشهر')
                    ->placeholder('اختر الشهر')
                    ->options([
                        'january' => 'يناير',
                        'february' => 'فبراير',
                        'march' => 'مارس',
                        'april' => 'إبريل',
                        'may' => 'مايو',
                        'june' => 'يونيو',
                        'july' => 'يوليو',
                        'august' => 'أغسطس',
                        'september' => 'سبتمبر',
                        'october' => 'أكتوبر',
                        'november' => 'نوفمبر',
                        'december' => 'ديسمبر',
                    ])
                    ->required(),
                Forms\Components\Select::make('ratings')
                    ->label('التقييم')
                    ->placeholder('اختر التقييم')
                    ->options([
                        1 => 1,
                        2 => 2,
                        3 => 3,
                        4 => 4,
                        5 => 5,
                        6 => 6,
                        7 => 7,
                        8 => 8,
                        9 => 9,
                        10 => 10,
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')->searchable()->sortable()
                    ->label('الموظف'),
                Tables\Columns\TextColumn::make('year')
                    ->label('السنة'),
                Tables\Columns\TextColumn::make('month')
                    ->label('الشهر'),
                Tables\Columns\TextColumn::make('ratings')
                    ->label('التقييم'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
            ])
            ->pushBulkActions([
                BulkAction::make('export')
                    ->action(fn(Collection $records) => redirect(route('performance.download')))
                    ->icon('heroicon-o-document-download')
                    ->label('Export Data')
            ])
            ->filters([
                //
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
            'index' => Pages\ListPerformances::route('/'),
            'create' => Pages\CreatePerformance::route('/create'),
            'edit' => Pages\EditPerformance::route('/{record}/edit'),
        ];
    }
}
