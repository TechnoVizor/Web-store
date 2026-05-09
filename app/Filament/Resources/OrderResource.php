<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Информация о заказе')
                    ->schema([
                        Section::make('Информация о клиенте')
                            ->schema([
                                TextInput::make('customer_name')->label('Имя покупателя')->disabled(),
                                TextInput::make('customer_phone')->label('Телефон')->disabled(),
                                TextInput::make('customer_address')->label('Адрес доставки')->disabled(),
                            ])->columns(3),

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Клиент')
                            ->disabled(), // Нельзя менять клиента

                        Select::make('status')
                            ->label('Статус заказа')
                            ->options([
                                'new' => 'Новый',
                                'processing' => 'В обработке',
                                'shipped' => 'Отправлен',
                                'delivered' => 'Доставлен',
                                'cancelled' => 'Отменен',
                            ])
                            ->required(),

                        TextInput::make('total_amount')
                            ->label('Итоговая сумма')
                            ->disabled()
                            ->prefix('$'),
                    ])->columns(3),

                Section::make('Состав заказа')
                    ->schema([
                        // Выводим товары из связанной таблицы order_items
                        Repeater::make('items')
                            ->relationship() // Laravel сам найдет связь items() в модели Order
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->label('Товар')
                                    ->disabled(),

                                TextInput::make('quantity')
                                    ->label('Кол-во')
                                    ->disabled(),

                                TextInput::make('price')
                                    ->label('Цена при покупке')
                                    ->disabled()
                                    ->prefix('$'),
                            ])
                            ->columns(3)
                            ->addable(false) // Запрещаем вручную добавлять товары в уже созданный заказ
                            ->deletable(false), // Запрещаем удалять товары из заказа
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('№ Заказа')
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Клиент')
                    ->description(fn (Order $record): string => $record->customer_phone ?? '') // Добавим телефон под именем для удобства
                    ->searchable(),

                // Выпадающий список прямо в таблице для быстрой смены статуса
                SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'processing' => 'В обработке',
                        'shipped' => 'Отправлен',
                        'delivered' => 'Доставлен',
                        'cancelled' => 'Отменен',
                    ])
                    ->selectablePlaceholder(false),

                TextColumn::make('total_amount')
                    ->label('Сумма')
                    ->money('usd')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc'); // Сначала новые заказы
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
