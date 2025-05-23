<?php

namespace App\Filament\Resources;

use App\Exports\AttendancesExport;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\LinkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?string $navigationGroup = 'الموظفين';

    protected static ?string $navigationLabel = 'الحضور';
    protected static ?string $title = 'الحضور';
    protected static ?string $label = 'حضور';
    protected static ?string $pluralLabel = 'الحضور';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                auth()->user()->hasRole('employee') ?
                    Forms\Components\Hidden::make('employee_id')
                    ->default((Employee::firstWhere('user_id', auth()->user()->id))->id)
                    ->label('الموظف')
                    ->required() :
                    Forms\Components\Select::make('employee_id')
                    ->label('الموظف')
                    ->options(Employee::all()->pluck('full_name', 'id'))
                    ->required(),
                Forms\Components\Hidden::make('recorded_by')
                    ->default(auth()->user()->id)
                    ->required(),
                Forms\Components\Select::make('shift_id')
                    ->label('الفترة')
                    ->options(Shift::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TimePicker::make('time_id')
                    ->label('وقت الدخول')
                    ->default(now()),
                Forms\Components\TimePicker::make('time_out')
                    ->label('وقت الخروج')
                    ->default(now()),
                auth()->user()->hasRole('employee') ?
                    Forms\Components\Hidden::make('status')
                    ->required()
                    ->default('present') :
                    Forms\Components\Select::make('status')
                    ->label('الحالة')

                    ->required()
                    ->default('present')
                    ->options([
                        'on_leave' => 'في أجازة',
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                    ]),
                auth()->user()->hasRole('employee') ?
                    Forms\Components\Hidden::make('is_confirmed')
                    ->required()
                    ->default(false) :
                    Forms\Components\Toggle::make('is_confirmed')
                    ->label('تم التأكيد')
                    ->required(),
                Forms\Components\DateTimePicker::make('recorded_at')
                    ->label('وقت التسجيل')
                    ->default(now())
                    ->disabled()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BooleanColumn::make('is_confirmed')
                    ->label('تم التأكيد'),
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('اسم الموظف')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')

                    ->label('تم التأكيد بواسطة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shift.name')
                    ->label('الفترة'),

                Tables\Columns\TextColumn::make('time_id')->time()
                    ->label('وقت الدخول'),
                Tables\Columns\TextColumn::make('time_out')->time()
                    ->label('وقت الخروج'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'secondary',
                        'danger' => 'absent',
                        'warning' => 'on_leave',
                        'success' => 'present',
                    ]),
                Tables\Columns\TextColumn::make('recorded_at')
                    ->label('وقت التسجيل')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
            ])
            ->pushBulkActions([
                BulkAction::make('export')
                    ->action(fn(Collection $records) => redirect(route('attendance.download')))
                    ->icon('heroicon-o-document-download')
                    ->label('Export Data')
            ])
            ->filters([
                Tables\Filters\Filter::make('recorded_at')
                    ->form([
                        Forms\Components\DatePicker::make('recorded_at')
                            ->default(now())
                            ->placeholder(fn($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['recorded_at'],
                                fn(Builder $query, $date): Builder => $query->whereDate('recorded_at', '>=', $date),
                            );
                    }),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
