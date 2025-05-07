<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingResource\Pages;
use App\Filament\Resources\MeetingResource\RelationManagers;
use App\Models\Client;
use App\Models\Meeting;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BelongsToColumn; // هنا لاستعراض العلاقة مع العميل






class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $navigationLabel = 'الاجتماعات';
    protected static ?string $title = 'اجتماعات';
    protected static ?string $label = 'اجتماع';
    protected static ?string $pluralLabel = 'الاجتماعات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('العميل')
                    ->required()
                    ->options(Client::all()->pluck('client_name', 'id')) // ربط مع جدول العملاء
                    ->searchable(), // يمكن البحث عن العميل بسهولة
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الاجتماع')
                    ->required(),



                Forms\Components\Textarea::make('notes')
                    ->label('ملاحظات')
                    ->nullable(),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('موعد الاجتماع')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('حالة الاجتماع')
                    ->options([
                        'scheduled' => 'مجدول',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                    ])
                    ->default('scheduled')
                    ->required(),
                // باقي الحقول الأخرى مثل العميل وموعد الاجتماع
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('client.client_name') // عرض اسم العميل من العلاقة
                    ->label('العميل'),


                Tables\Columns\TextColumn::make('title')
                    ->label('اسم الاجتماع'),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('موعد الاجتماع')
                    ->dateTime('Y-m-d H:i:s'),

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
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }
}
