<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable(),
                TextColumn::make('teacher.user.name')
                    ->label('Profesor')
                    ->default('Sin asignar'),
                TextColumn::make('modality')
                    ->label('Modalidad')
                    ->badge(),
                TextColumn::make('level')
                    ->label('Nivel')
                    ->badge(),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('USD'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'inactive' => 'danger',
                    }),
                TextColumn::make('enrollments_count')
                    ->counts('enrollments')
                    ->label('Inscripciones'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'published' => 'Publicado',
                        'inactive' => 'Inactivo',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
                SelectFilter::make('modality')
                    ->label('Modalidad')
                    ->options([
                        'presencial' => 'Presencial',
                        'online' => 'Online',
                        'hibrido' => 'Híbrido',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                Action::make('publish')
                    ->label('Publicar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'published')
                    ->action(fn($record) => $record->update([
                        'status' => 'published',
                        'published_at' => now(),
                    ])),
                Action::make('unpublish')
                    ->label('Despublicar')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn($record) => $record->status === 'published')
                    ->action(fn($record) => $record->update([
                        'status' => 'inactive',
                    ])),
            ]);
    }
}
