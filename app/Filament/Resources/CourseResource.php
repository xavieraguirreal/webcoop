<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Cooperativa';
    protected static ?string $modelLabel = 'Curso';
    protected static ?string $pluralModelLabel = 'Formación / Cursos';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del curso')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\RichEditor::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Detalles')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Imagen')
                            ->image()
                            ->directory('courses'),
                        Forms\Components\TextInput::make('duration')
                            ->label('Duración')
                            ->placeholder('Ej: 3 meses'),
                        Forms\Components\Select::make('modality')
                            ->label('Modalidad')
                            ->options([
                                'presencial' => 'Presencial',
                                'virtual' => 'Virtual',
                                'mixta' => 'Mixta',
                            ]),
                        Forms\Components\Toggle::make('has_certificate')
                            ->label('Otorga certificado'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
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
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duración'),
                Tables\Columns\TextColumn::make('modality')
                    ->label('Modalidad')
                    ->badge(),
                Tables\Columns\IconColumn::make('has_certificate')
                    ->label('Certif.')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
