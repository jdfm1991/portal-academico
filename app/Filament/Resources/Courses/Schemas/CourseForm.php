<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name', fn ($query) => $query->where('is_active', true))
                    ->searchable()
                    ->required(),
                Select::make('teacher_id')
                    ->label('Profesor')
                    ->relationship('teacher', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name)
                    ->searchable()
                    ->nullable(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->rows(3),
                Textarea::make('syllabus')
                    ->label('Temario')
                    ->rows(5),
                TextInput::make('price')
                    ->label('Precio')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->required(),
                Select::make('modality')
                    ->label('Modalidad')
                    ->options([
                        'presencial' => 'Presencial',
                        'online' => 'Online',
                        'hibrido' => 'Híbrido',
                    ])
                    ->required(),
                Select::make('level')
                    ->label('Nivel')
                    ->options([
                        'principiante' => 'Principiante',
                        'intermedio' => 'Intermedio',
                        'avanzado' => 'Avanzado',
                    ])
                    ->required(),
                TextInput::make('duration')
                    ->label('Duración')
                    ->maxLength(255),
                DatePicker::make('start_date')
                    ->label('Fecha de inicio'),
                DatePicker::make('end_date')
                    ->label('Fecha de fin')
                    ->after('start_date'),
                TextInput::make('image_path')
                    ->label('Ruta de imagen')
                    ->maxLength(255),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'published' => 'Publicado',
                        'inactive' => 'Inactivo',
                    ])
                    ->default('draft')
                    ->required()
                    ->live(),
                DateTimePicker::make('published_at')
                    ->label('Fecha de publicación')
                    ->visible(fn (callable $get) => $get('status') === 'published'),
            ]);
    }
}