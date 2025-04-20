<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LapinResource\Pages;
use App\Models\Lapin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class LapinResource extends Resource
{
    protected static ?string $model = Lapin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Élevage';

    protected static ?string $navigationLabel = 'Lapins';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'alive')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

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
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->label('Sexe')
                            ->options([
                                'male' => 'Mâle',
                                'female' => 'Femelle',
                            ])
                            ->required(),
                        Forms\Components\DatePicker::make('birthdate')
                            ->label('Date de naissance')
                            ->maxDate(now())
                            ->displayFormat('d/m/Y'),
                        Forms\Components\TextInput::make('breed')
                            ->label('Race')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Logement et état')
                    ->schema([
                        Forms\Components\TextInput::make('cage')
                            ->label('Cage/Enclos')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'alive' => 'Vivant',
                                'dead' => 'Décédé',
                                'sold' => 'Vendu',
                                'given' => 'Donné',
                            ])
                            ->default('alive')
                            ->required(),
                        Forms\Components\TextInput::make('color')
                            ->label('Couleur')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('weight')
                            ->label('Poids (kg)')
                            ->numeric()
                            ->step(0.01),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('identification_number')
                    ->label('N° ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Sexe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'male' ? 'Mâle' : 'Femelle')
                    ->color(fn (string $state): string => $state === 'male' ? 'info' : 'pink')
                    ->sortable(),
                Tables\Columns\TextColumn::make('birthdate')
                    ->label('Date de naissance')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->label('Âge')
                    ->getStateUsing(function (Lapin $record): string {
                        if (!$record->birthdate) {
                            return 'Non défini';
                        }
                        return $record->birthdate->diffForHumans(null, true);
                    })
                    ->searchable(false)
                    ->sortable(false),
                Tables\Columns\TextColumn::make('cage')
                    ->label('Cage')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        return match($state) {
                            'alive' => 'Vivant',
                            'dead' => 'Décédé',
                            'sold' => 'Vendu',
                            'given' => 'Donné',
                            default => $state,
                        };
                    })
                    ->color(fn (Lapin $record): string => $record->status_color)
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Poids')
                    ->suffix(' kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'alive' => 'Vivant',
                        'dead' => 'Décédé',
                        'sold' => 'Vendu',
                        'given' => 'Donné',
                    ]),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Sexe')
                    ->options([
                        'male' => 'Mâle',
                        'female' => 'Femelle',
                    ]),
                Tables\Filters\Filter::make('cage')
                    ->form([
                        Forms\Components\TextInput::make('cage')
                            ->label('Cage/Enclos'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['cage'],
                                fn (Builder $query, $cage): Builder => $query->where('cage', 'like', "%{$cage}%"),
                            );
                    }),
                Tables\Filters\Filter::make('birthdate')
                    ->form([
                        Forms\Components\DatePicker::make('birthdate_from')
                            ->label('Date de naissance (depuis)'),
                        Forms\Components\DatePicker::make('birthdate_until')
                            ->label('Date de naissance (jusqu\'à)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['birthdate_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('birthdate', '>=', $date),
                            )
                            ->when(
                                $data['birthdate_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('birthdate', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Changer le statut')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Nouveau statut')
                                ->options([
                                    'alive' => 'Vivant',
                                    'dead' => 'Décédé',
                                    'sold' => 'Vendu',
                                    'given' => 'Donné',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update(['status' => $data['status']]);
                            }
                        }),
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
            'index' => Pages\ListLapins::route('/'),
            'create' => Pages\CreateLapin::route('/create'),
            'edit' => Pages\EditLapin::route('/{record}/edit'),
        ];
    }
}