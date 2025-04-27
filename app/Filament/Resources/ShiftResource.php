<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShiftResource\Pages;
use App\Filament\Resources\ShiftResource\RelationManagers;
use App\Models\Shift;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;

    protected static ?string $navigationIcon = 'heroicon-o-moon';

    protected static ?string $navigationGroup = 'الإعدادات';


    protected static ?string $navigationLabel = 'مواعيد العمل';
    protected static ?string $title = 'مواعيد العمل';
    protected static ?string $label = 'موعد العمل';
    protected static ?string $pluralLabel = 'مواعيد العمل';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم موعد العمل')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TimePicker::make('start')
                    ->label('وقت بدء العمل')
                    ->default(now())
                    ->withoutSeconds()
                    ->required(),
                Forms\Components\TimePicker::make('end')
                    ->label('وقت انتهاء العمل')
                    ->default(now())
                    ->withoutSeconds()
                    ->required(),
                Forms\Components\MultiSelect::make('days')
                    ->options([
                        'saturday' => 'السبت',
                        'sunday' => 'الأحد',
                        'monday' => 'الاثنين',
                        'tuesday' => 'الثلاثاء',
                        'wednesday' => 'الأربعاء',
                        'thursday' => 'الخميس',
                        'friday' => 'الجمعة',

                    ])
                    ->required(),
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
                    ->label('اسم موعد العمل')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start')
                    ->label('وقت بدء العمل')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                    ->label('وقت انتهاء العمل')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('days')
                    ->label('الأيام')
                    ->sortable(),
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
            'index' => Pages\ListShifts::route('/'),
            'create' => Pages\CreateShift::route('/create'),
            'edit' => Pages\EditShift::route('/{record}/edit'),
        ];
    }
}
