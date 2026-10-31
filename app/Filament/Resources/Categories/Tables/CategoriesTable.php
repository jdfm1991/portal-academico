<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('courses_count')->counts('courses')->label('Cursos'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }
}