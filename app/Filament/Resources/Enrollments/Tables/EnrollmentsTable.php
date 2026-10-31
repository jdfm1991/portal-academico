<?php

namespace App\Filament\Resources\Enrollments\Tables;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Estudiante')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.email')
                    ->label('Email')
                    ->searchable()
                    ->visible(fn() => Auth::user()->role === 'admin'),
                TextColumn::make('course.title')
                    ->label('Curso')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('enrollment_status')
                    ->label('Inscripción')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                    }),
                TextColumn::make('payment_status')
                    ->label('Pago')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'not_required' => 'gray',
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'info',
                    }),
                TextColumn::make('requested_at')
                    ->label('Solicitada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('enrollment_status')
                    ->label('Estado de inscripción')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'rejected' => 'Rechazada',
                        'cancelled' => 'Cancelada',
                    ]),
                SelectFilter::make('payment_status')
                    ->label('Estado de pago')
                    ->options([
                        'not_required' => 'No requerido',
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->enrollment_status === 'pending' && Auth::user()->role === 'admin')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update([
                        'enrollment_status' => 'approved',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                    ])),
                Action::make('reject')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->enrollment_status === 'pending' && Auth::user()->role === 'admin')->requiresConfirmation()
                    ->action(fn($record) => $record->update([
                        'enrollment_status' => 'rejected',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                    ])),

                Action::make('view_receipt')
                    ->label('Ver comprobante')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->visible(
                        fn($record) =>
                        Auth::user()->role === 'admin' && $record->receipts()->exists()
                    )
                    ->modalHeading('Comprobante de pago')
                    ->modalContent(function ($record) {
                        $receipt = $record->receipts()->latest()->first();
                        if (!$receipt) return null;

                        $url = Storage::disk('local')->temporaryUrl(
                            $receipt->file_path,
                            now()->addMinutes(10)
                        );

                        $extension = strtolower(pathinfo($receipt->file_path, PATHINFO_EXTENSION));

                        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                            return view('filament.modals.receipt-image', ['url' => $url]);
                        }

                        return view('filament.modals.receipt-pdf', ['url' => $url]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),

                Action::make('mark_paid')
                    ->label('Marcar como pagado')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(
                        fn($record) =>
                        Auth::user()->role === 'admin' &&
                            $record->payment_status === 'pending' &&
                            !in_array($record->enrollment_status, ['rejected', 'cancelled'])
                    )
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update([
                        'payment_status' => 'paid',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                    ])),

                Action::make('mark_failed')
                    ->label('Rechazar pago')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(
                        fn($record) =>
                        Auth::user()->role === 'admin' &&
                            $record->payment_status === 'pending' &&
                            !in_array($record->enrollment_status, ['rejected', 'cancelled'])
                    )
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update([
                        'payment_status' => 'failed',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                    ])),
            ]);
    }
}
