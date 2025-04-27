<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayScaleResource\Pages;
use App\Filament\Resources\PayScaleResource\RelationManagers;
use App\Models\PayScale;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PayScaleResource extends Resource
{
    protected static ?string $model = PayScale::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'الإعدادات';

    //    protected static bool $hasAssociateAction = true;
    //    protected static bool $hasDissociateAction = true;
    //    protected static bool $hasDissociateBulkAction = true;


    protected static ?string $navigationLabel = 'الدرجات الوظيفية';
    protected static ?string $title = 'الدرجات الوظيفية';
    protected static ?string $label = 'درجة وظيفية';
    protected static ?string $pluralLabel = 'درجات وظيفية';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الدرجة الوظيفية')
                    ->columns(1)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('basic_salary')
                    ->label('الراتب الأساسي')
                    ->columns(1)
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->columns(1)
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدرجة الوظيفية'),

                Tables\Columns\TextColumn::make('basic_salary')->money('EGP', true)
                    ->label('الراتب الأساسي'),

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
            RelationManagers\AllowancesRelationManager::class,
            RelationManagers\DeductionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayScales::route('/'),
            'create' => Pages\CreatePayScale::route('/create'),
            'edit' => Pages\EditPayScale::route('/{record}/edit'),
        ];
    }
}
