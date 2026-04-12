<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkAreaResource\Pages;
use App\Models\WorkArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WorkAreaResource extends Resource
{
    protected static ?string $model = WorkArea::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Cooperativa';
    protected static ?string $modelLabel = 'Área de trabajo';
    protected static ?string $pluralModelLabel = 'Áreas de trabajo';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('group')
                            ->label('Categoría')
                            ->options(WorkArea::GROUPS)
                            ->required()
                            ->default('talleres-productivos'),
                        Forms\Components\Textarea::make('short_description')
                            ->label('Descripción corta')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Descripción completa')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Visual')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Imagen destacada')
                            ->image()
                            ->directory('work-areas')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9'),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icono (clase heroicon)')
                            ->placeholder('heroicon-o-...'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activa')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Imagen')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->label('Categoría')
                    ->formatStateUsing(fn (string $state) => WorkArea::GROUPS[$state] ?? $state)
                    ->badge(),
                Tables\Columns\TextColumn::make('short_description')
                    ->label('Descripción')
                    ->limit(60),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkAreas::route('/'),
            'create' => Pages\CreateWorkArea::route('/create'),
            'edit' => Pages\EditWorkArea::route('/{record}/edit'),
        ];
    }
}
