<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveTypeResource\Pages;
use App\Filament\Resources\LeaveTypeResource\RelationManagers;
use App\Models\LeaveType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class LeaveTypeResource extends Resource
{
    protected static ?string $model = LeaveType::class;

    protected static ?string $navigationIcon = 'heroicon-o-external-link';

    protected static ?string $navigationGroup = 'الإعدادات';



    protected static ?string $navigationLabel = 'أنواع الإجازات';
    protected static ?string $title = 'أنواع الإجازات';
    protected static ?string $label = 'نوع إجازة';
    protected static ?string $pluralLabel = 'أنواع الإجازات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الإجازة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('credit_type')
                    ->label('المدة الزمنية')
                    ->placeholder('اختر المدة الزمنية')

                    ->options([
                        'day' => 'يومي',
                        'week' => 'أسبوعي',
                        'month' => 'شهري',
                        'year' => 'سنوي',
                    ])->required(),
                Forms\Components\TextInput::make('credit_leaves')
                    ->label('عدد الأيام')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الإجازة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credit_type')
                    ->label('المدة الزمنية'),
                Tables\Columns\TextColumn::make('credit_leaves')
                    ->label('عدد الأيام'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
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
            'index' => Pages\ListLeaveTypes::route('/'),
            'create' => Pages\CreateLeaveType::route('/create'),
            'edit' => Pages\EditLeaveType::route('/{record}/edit'),
        ];
    }
}
