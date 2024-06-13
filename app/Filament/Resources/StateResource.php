<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\State;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;

use Filament\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\BelongsToSelect;
use App\Filament\Resources\StateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StateResource\RelationManagers;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Area';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('country_id')
                ->relationship('country', 'name')
                ->searchable()
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
               
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]) ;
    }

    public static function getRelations(): array
    {
        return [           

           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
   
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Section::make('State Information')
            ->schema([
                TextEntry::make('name')

                ->label('Name'),
                TextEntry::make('country.name')
            
                ->label('Country'),
            ])->columns(2),  
              
        ]);
    }

}
