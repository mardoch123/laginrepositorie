<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RabbitResource\Pages;
use App\Models\Rabbit;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RabbitResource extends Resource
{
    protected static ?string $model = Rabbit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Gestion d\'élevage';

    protected static ?string $navigationLabel = 'Lapins';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations générales')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('identification_number')
                            ->label('Numéro d\'identification')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->label('Sexe')
                            ->options([
                                'male' => 'Mâle',
                                'female' => 'Femelle',
                            ])
                            ->required(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Date de naissance')
                            ->required()
                            ->maxDate(now())
                            ->displayFormat('d/m/Y'),
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'alive' => 'Vivant',
                                'dead' => 'Mort',
                                'sold' => 'Vendu',
                                'given' => 'Donné',
                            ])
                            ->default('alive')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Caractéristiques')
                    ->schema([
                        Forms\Components\TextInput::make('breed')
                            ->label('Race')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('color')
                            ->label('Couleur')
                            ->maxLength(255),
                        Forms\Components\Select::make('cage_id')
                            ->label('Cage')
                            ->relationship('cage', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Actif')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identification_number')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Sexe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'blue',
                        'female' => 'pink',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'male' => 'Mâle',
                        'female' => 'Femelle',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Date de naissance')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->label('Âge')
                    ->getStateUsing(function (Rabbit $record): string {
                        if (!$record->birth_date) {
                            return 'N/A';
                        }
                        
                        $age = $record->birth_date->diffInMonths(Carbon::now());
                        
                        if ($age < 1) {
                            $days = $record->birth_date->diffInDays(Carbon::now());
                            return $days . ' jour' . ($days > 1 ? 's' : '');
                        }
                        
                        return $age . ' mois';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('birth_date', $direction === 'desc' ? 'asc' : 'desc');
                    }),
                Tables\Columns\TextColumn::make('breed')
                    ->label('Race')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cage.name')
                    ->label('Cage')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'alive' => 'success',
                        'dead' => 'danger',
                        'sold' => 'warning',
                        'given' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'alive' => 'Vivant',
                        'dead' => 'Mort',
                        'sold' => 'Vendu',
                        'given' => 'Donné',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Sexe')
                    ->options([
                        'male' => 'Mâle',
                        'female' => 'Femelle',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'alive' => 'Vivant',
                        'dead' => 'Mort',
                        'sold' => 'Vendu',
                        'given' => 'Donné',
                    ]),
                Tables\Filters\SelectFilter::make('cage')
                    ->label('Cage')
                    ->relationship('cage', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListRabbits::route('/'),
            'create' => Pages\CreateRabbit::route('/create'),
            'edit' => Pages\EditRabbit::route('/{record}/edit'),
        ];
    }
}