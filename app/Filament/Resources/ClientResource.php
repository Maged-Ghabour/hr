<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;


use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ViewAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Toggle;
use Konnco\FilamentImport\Actions\ImportAction as ActionsImportAction;
use Konnco\FilamentImport\Import;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Illuminate\Validation\Rule;


class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';




    protected static ?string $navigationLabel = 'العملاء';
    protected static ?string $title = 'العملاء';
    protected static ?string $label = 'عميل';
    protected static ?string $pluralLabel = 'العملاء';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('client_name')
                    ->label('اسم العميل'),

                Forms\Components\TextInput::make('client_phone')
                    ->label('رقم الهاتف')
                    ->required()
                    // ->unique(ignoreRecord: true)
                    ->rule('unique:clients,client_phone,' . (request()->route('record') ? request()->route('record') : '')),


                // ->rule('unique:clients,client_phone') // تحقق من عدم التكرار


                Forms\Components\TextInput::make('storeName_ar')
                    ->label(' اسم المتجر بالعربية'),


                Forms\Components\TextInput::make('website')
                    ->label('رابط الموقع')
                    ->autocomplete('off')
                    ->placeholder('https://example.com')
                    ->unique(ignoreRecord: true),

                // Forms\Components\TextInput::make('storeName_en')
                //     ->label(' اسم المتجر بالانجليزية'),


                Select::make('user_id')
                    ->label('المستخدم المسؤول')
                    ->options(User::all()->pluck('name', 'id'))
                    ->default(auth()->id())
                    ->searchable()
                    ->disabled()
                    ->required(),


                Forms\Components\Select::make('store_category')
                    ->label('فئة المتجر')
                    ->placeholder('تصنيف المتجر')
                    ->options([
                        'إلكترونيات' => 'إلكترونيات',
                        'موضة وأزياء' => 'موضة وأزياء',
                        'تجميل وعناية' => 'تجميل وعناية',
                        'بقالة ومواد غذائية' => 'بقالة ومواد غذائية',
                        'furniture' => 'أثاث وديكور',
                        'كتب ومجلات' => 'كتب ومجلات',
                        'ألعاب وهوايات' => 'ألعاب وهوايات',
                        'رياضة ولياقة' => 'رياضة ولياقة',
                        'سيارات واكسسوارات' => 'سيارات واكسسوارات',
                        'معدات وأدوات' => 'معدات وأدوات',
                        'مستلزمات الحيوانات' => 'مستلزمات الحيوانات',
                        'مكتبية وقرطاسية' => 'مكتبية وقرطاسية',
                        'مجوهرات واكسسوارات' => 'مجوهرات واكسسوارات',
                        'هواتف واكسسوارات' => 'هواتف واكسسوارات',
                        'منتجات يدوية' => 'منتجات يدوية',
                        'أخري' => 'أخري'
                    ]),



                Forms\Components\Select::make('status')
                    ->label('حالة العميل')
                    ->placeholder('حالة العميل')
                    ->options([
                        "0" => 'لم يتم التواصل',
                        "1" => 'تم التواصل',

                    ])
                    ->default('0'),


                // Forms\Components\Select::make('store_rate')
                //     ->label('تقييم المتجر')
                //     ->placeholder("تقييم المتجر")
                //     ->options([
                //         "1" => 1,
                //         "2" => 2,
                //         "3" => 3,
                //         "4" => 4,
                //         "5" => 5,
                //         "6" => 6,
                //         "7" => 7,
                //         "8" => 8,
                //         "9" => 9,
                //         "10" => 10

                //     ]),

                // Forms\Components\SpatieMediaLibraryFileUpload::make('store_image')
                //     ->collection('store_images')
                //     ->label('صورة المتجر'),



                Forms\Components\Textarea::make('notes')
                    ->label('ملاحظات')
                    ->placeholder('اكتب الملاحظات هنا...'),


                Section::make(' التعليقات')
                    ->schema([
                        Forms\Components\Repeater::make('comments')
                            ->label('التعليقات')
                            ->schema([


                                Forms\Components\Textarea::make('content')
                                    ->label("تعليق")
                                    ->rows(3)
                                    ->helperText('تمت الإضافة بواسطة: ' . Auth::user()?->name ?? 'غير معروف')
                                    ->columnSpan('full')
                                    ->required()



                            ])
                            ->default([])
                            ->columnSpan('full')
                            ->columns(2)
                            ->createItemButtonLabel('إضافة تعليق')
                            ->afterStateUpdated(function ($component, $state) {
                                // هذه الطريقة يتم تنفيذها عند تحديث الحالة بعد إضافة العنصر
                                if ($state) {
                                    Notification::make()
                                        ->title('تم إضافة التعليق بنجاح')
                                        ->message('تم إضافة تعليقك إلى النظام بنجاح.')
                                        ->success()
                                        ->send();
                                }
                            })
                    ])
                    ->collapsible(), // اختياري لجعله قابل للطي


            ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->hidden(), // نخبي الآي دي لكن يبقى موجود بالصف


                Tables\Columns\TextColumn::make('serial')
                    ->label('م')
                    ->formatStateUsing(function ($state, $record) {
                        static $index = 0;
                        return ++$index;
                    }),

                Tables\Columns\TextColumn::make('client_name')
                    ->label(' اسم العميل')
                    ->searchable()
                    ->sortable()
                    ->extraAttributes(function ($record) {
                        return [
                            'x-data' => '{}',
                            'x-on:click' => "localStorage.setItem('bookmark_row_id', '{$record->id}'); window.dispatchEvent(new Event('storage'));",
                            'class' => 'cursor-pointer bookmarkable-cell',
                        ];
                    }),


                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->action(function ($record) {
                        $record->status = !$record->status;
                        $record->save();
                    })
                    ->getStateUsing(function ($record) {
                        if ($record->status == 1) {
                            return '😀 تم التواصل';
                        } else {
                            return '🥲 لم يتم التواصل';
                        }
                    })
                    ->extraAttributes(function ($record) {
                        // إضافة تنسيقات CSS حسب الحالة
                        return $record->status == 1
                            ? ['class' => 'sucess-label']  // اللون الأخضر عند تم التواصل
                            : ['class' => 'failed-label'];   // اللون الأحمر عند لم يتم التواصل
                    }),


                Tables\Columns\TextColumn::make('client_phone')
                    ->label('رقم الجوال')
                    ->searchable()

                    ->sortable(),
                Tables\Columns\TextColumn::make('storeName_ar')
                    ->label('اسم المتجر بالعربي')
                    ->searchable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make("store_category")
                //     ->label("تصنيفات المتجر")
                //     ->searchable()
                //     ->sortable(),

                // Tables\Columns\TextColumn::make('notes')
                //     ->label('ملاحظات'),



                // Tables\Columns\BadgeColumn::make('status')
                //     ->label('الحالة')
                //     ->enum([
                //         'pending' => 'لم يتم التواصل',
                //         'approved' => 'مهتم',
                //         'rejected' => 'غير مهتم',
                //     ])
                //     ->colors([

                //         'pending' => 'bg-yellow-400 text-yellow-800',
                //         'approved' => 'success',
                //         'rejected' => 'danger',
                //     ])
                //     ->icons([
                //         'pending' => 'heroicon-o-clock',
                //         'approved'  => 'heroicon-o-check-circle',
                //         'rejected' => 'heroicon-o-x-circle',
                //     ]),



                Tables\Columns\TextColumn::make('created_date')
                    ->label('تاريخ الإنشاء')

                    ->formatStateUsing(fn($state, $record) => $record->created_at->format('Y-m-d', 'H:i:s')),
                // Tables\Columns\TextColumn::make('created_date')

                // Tables\Columns\TextColumn::make('created_time')
                //     ->label('وقت الإنشاء')

                //     ->formatStateUsing(fn($state, $record) => $record->created_at->format('h:i A')),



                // Tables\Columns\TextColumn::make('created_diff')
                //     ->label('منذ الإنشاء')
                //     ->searchable()
                //     ->sortable()
                //     ->formatStateUsing(fn($state, $record) => $record->created_at->diffForHumans()),


                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المستخدم')
                    ->sortable(),







                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('واتساب')
                    ->extraAttributes(['class' => 'w-1/6'])  // عرض العمود 25% من المساحة
                    ->getStateUsing(function ($record) {
                        $client_phone = preg_replace('/[^0-9]/', '', $record->client_phone);
                        $url = 'https://wa.me/' . $client_phone . '?text=' . urlencode('السلام عليكم، ممكن أساعدك؟');

                        return <<<HTML
            <a href="{$url}" target="_blank"
                style="
                    font-size: 1.8rem;
                    color:green;

                    display: inline-block;
                ">
                 <i class="fa-brands fa-whatsapp"></i>

            </a>
                 <a href="{$record->website}" target="_blank"
                style="
                    font-size: 1.8rem;
                    padding-right: 5px;
                    color:#4646ff;
                    display: inline-block;
                ">
                  <i class="fa-solid fa-earth-americas"></i>
            </a>
        HTML;
                    })

                    ->html(),






                //         Tables\Columns\TextColumn::make('website')
                //             ->label('الموقع')
                //             ->getStateUsing(function ($record) {


                //                 return <<<HTML

                // HTML;
                //             })
                //             ->html(),


            ])



            ->filters([
                Filter::make('store_category')
                    ->form([
                        Select::make('store_category')
                            ->label('التصنيف')
                            ->options([
                                'إلكترونيات' => 'إلكترونيات',
                                'موضة وأزياء' => 'موضة وأزياء',
                                'تجميل وعناية' => 'تجميل وعناية',
                                'بقالة ومواد غذائية' => 'بقالة ومواد غذائية',
                                'furniture' => 'أثاث وديكور',
                                'كتب ومجلات' => 'كتب ومجلات',
                                'ألعاب وهوايات' => 'ألعاب وهوايات',
                                'رياضة ولياقة' => 'رياضة ولياقة',
                                'سيارات واكسسوارات' => 'سيارات واكسسوارات',
                                'معدات وأدوات' => 'معدات وأدوات',
                                'مستلزمات الحيوانات' => 'مستلزمات الحيوانات',
                                'مكتبية وقرطاسية' => 'مكتبية وقرطاسية',
                                'مجوهرات واكسسوارات' => 'مجوهرات واكسسوارات',
                                'هواتف واكسسوارات' => 'هواتف واكسسوارات',
                                'منتجات يدوية' => 'منتجات يدوية',
                                'أخري' => 'أخري'
                            ])

                    ])



                    ->query(function (Builder $query, array $data) {
                        if (isset($data['store_category'])) {
                            return $query->where('store_category', '=', $data['store_category']);
                        }
                    }),

                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->label('حالة العميل')
                            ->options([

                                "0" => 'لم يتم التواصل',
                                "1" => 'تم التواصل',

                            ])
                    ])

                    ->query(function (Builder $query, array $data) {
                        if (isset($data['status'])) {
                            return $query->where('status', '=', $data['status']);
                        }
                    }),

                Filter::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->form([
                        DatePicker::make('created_at')
                            ->label('تاريخ')

                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['created_at'])) {
                            return $query->whereDate('created_at', '=', $data['created_at']);
                        }
                    }),
                SelectFilter::make('user_id')
                    ->label('اسم المستخدم')
                    ->default(auth()->id())
                    ->relationship('user', 'name'),


            ])
            ->defaultSort('created_at', 'desc'); // الأحدث أولاً




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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            // 'view' => Pages\ViewClient::route('/{record}'), // 👈 هذا السطر الجديد

        ];
    }

    public static function getValidationMessages(): array
    {
        return [
            'client_phone.unique' => 'رقم الجوال هذا موجود بالفعل في النظام، يرجى إدخال رقم آخر.',
        ];
    }
}
