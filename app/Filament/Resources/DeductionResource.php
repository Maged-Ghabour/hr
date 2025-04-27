<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeductionResource\Pages;
use App\Filament\Resources\DeductionResource\RelationManagers;
use App\Models\Deduction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DeductionResource extends Resource
{
    protected static ?string $model = Deduction::class;

    protected static ?string $navigationIcon = 'heroicon-o-minus-circle';

    protected static ?string $navigationGroup = 'الإعدادات';



    protected static ?string $navigationLabel = 'الخصومات';
    protected static ?string $title = 'الخصومات';
    protected static ?string $label = 'خصم';
    protected static ?string $pluralLabel = 'خصومات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->reactive()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
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
            'index' => Pages\ListDeductions::route('/'),
            'create' => Pages\CreateDeduction::route('/create'),
            'edit' => Pages\EditDeduction::route('/{record}/edit'),
        ];
    }
}
