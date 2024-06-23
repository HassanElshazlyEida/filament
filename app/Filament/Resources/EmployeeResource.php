<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Department';
    protected static ?string $recordTitleAttribute = 'first_name';

    public static function getGlobalSearchResultTitle(Model $record): string| Htmlable
    {
        return $record->full_name;
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'middle_name', 'address', 'zip_code','country.name'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            // 'Department' => $record->department->name,
            // 'City' => $record->city->name,
            // 'State' => $record->state->name,
            'Country' => $record->country->name,
        ];
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return  parent::getGlobalSearchEloquentQuery()
            ->with('country');
    }
    public static function getNavigationBadge(): ?string
    {
        return Employee::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    // first section
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('middle_name')
     
                        ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_birth')
                        ->displayFormat('Y-m-d')
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('date_hired')
                        ->native(false)
                        ->required()
                        ->columnSpanFull()
                ])
                ->columns(2)
                ->description('Personal Information'),

                Forms\Components\Section::make([
                    // second section
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('zip_code')
                        ->required()
                        ->maxLength(255),
                ])
                ->columns(2)->description('Address Information'),
   

                Forms\Components\Section::make([

                    Select::make('country_id')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function(Set $set) {
                        $set('state_id', null);
                        $set('city_id', null);
                    }),
                    Select::make('state_id')
                    ->options(fn(Get $get): Collection => 
                        State::query()
                        ->where('country_id', $get('country_id'))
                        ->pluck('name', 'id')
                    )
                    ->preload()
                    ->live()
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(fn(Set $set) => $set('city_id', null) ),
                    Select::make('city_id')
                    ->options(
                        fn(Get $get): Collection => 
                        City::query()
                        ->where('state_id', $get('state_id'))
                        ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->live()
                    ->required()
                    ->preload(),
                    Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                ])   ->columns(2)
                ->description('Location Information'),
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_hired')
                    ->hidden(true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
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
               SelectFilter::make('Department')
               ->relationship('department', 'name')
               ->searchable()
               ->preload()
               ->label('Filter By Department')
               ->indicator('Department'),
               Filter::make('created_at')
               ->form([
                    Forms\Components\DatePicker::make('created_from')
                    ->label('Created From')
                    ->date()
                    ->required()
                    ->native(false),
                    Forms\Components\DatePicker::make('created_until')
                    ->label('Created Until')
                    ->date()
                    ->native(false)
                    ->required(),
               ])
               ->query( function(Builder $query, array $data) :Builder {
                    return $query
                    ->when(
                        $data['created_from'],
                        fn(Builder $query, $date) => $query->whereDate('created_at', '>=', $date)
                    )
                    ->when(
                        $data['created_until'],
                        fn(Builder $query, $date) => $query->whereDate('created_at', '<=', $date)
                    );
                }
               )
               ->indicateUsing(function(array $data){
                    $indicators = [];
                    if($data['created_from'] ?? null)
                        $indicators[] = 'Created From ' . $data['created_from'];
                    if($data['created_until']  ?? null )
                        $indicators[] = 'Created Until ' . $data['created_until'];
                    return $indicators;
                })->columnSpan(2)->columns(2)
            ], FiltersLayout::AboveContentCollapsible)->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
