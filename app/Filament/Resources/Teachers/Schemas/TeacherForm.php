<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('specialty')
                    ->label('Especialidad')
                    ->maxLength(255),
                Textarea::make('biography')
                    ->label('Biografía')
                    ->rows(4),
                TextInput::make('photo_path')
                    ->label('Ruta de foto')
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}