<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Filament\Resources\LeaveResource\RelationManagers;
use App\Models\Employee;
use App\Models\Leave;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-external-link';

    protected static ?string $navigationGroup = 'الموظفين';




    protected static ?string $navigationLabel = 'الإجازات';
    protected static ?string $title = 'الإجازات';
    protected static ?string $label = 'إجازة';
    protected static ?string $pluralLabel = 'الإجازات';


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
                Forms\Components\TextInput::make('credit_type')
                    ->label('نوع الإجازة')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('credit_leaves')
                    ->label('مدة الإجازة')
                    ->required()
                    ->options([
                        'day' => 'يومي',
                        'week' => 'أسبوعي',
                        'month' => 'شهري',
                        'year' => 'سنوي',
                    ]),
                Forms\Components\DatePicker::make('from')
                    ->default(now())
                    ->label('من')
                    ->placeholder('اختر تاريخ البداية')
                    ->required(),
                Forms\Components\DatePicker::make('to')
                    ->default(now())
                    ->label('إلى')
                    ->placeholder('اختر تاريخ النهاية')
                    ->required(),
                auth()->user()->hasRole('employee') ?
                    Forms\Components\Hidden::make('status')
                    ->required()
                    ->label('الحالة')
                    ->default('pending') :
                    Forms\Components\Select::make('status')
                    ->required()
                    ->default('pending')
                    ->options([
                        'pending' => 'قيد المراجعة',
                        'accepted' => 'تمت الموافقة',
                        'rejected' => 'تم الرفض',
                    ]),
                Forms\Components\Textarea::make('reason')
                    ->label('سبب الإجازة')

                    ->required()
                    ->maxLength(300),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')->searchable()
                    ->label('اسم الموظف'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('تمت الموافقة بواسطة'),
                Tables\Columns\TextColumn::make('credit_type')
                    ->label('نوع الإجازة'),
                Tables\Columns\TextColumn::make('credit_leaves')
                    ->label('مدة الإجازة'),
                Tables\Columns\TextColumn::make('from')
                    ->label('من')
                    ->date(),
                Tables\Columns\TextColumn::make('to')
                    ->label('إلى')
                    ->date(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('سبب الإجازة'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'secondary',
                        'danger' => 'rejected',
                        'warning' => 'pending',
                        'success' => 'accepted',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
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
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if (auth()->user()->hasRole('employee')) {
            return Leave::query()->where('employee_id', (Employee::firstWhere('user_id', auth()->user()->id))->id);
        }

        return Leave::query();
    }
}
