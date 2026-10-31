<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('enrollment_status')
                    ->label('Estado de inscripción')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobada',
                        'rejected' => 'Rechazada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->required(),
                Select::make('payment_status')
                    ->label('Estado de pago')
                    ->options([
                        'not_required' => 'No requerido',
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                    ])
                    ->required(),
                Textarea::make('cancellation_reason')
                    ->label('Motivo de cancelación')
                    ->rows(2)
                    ->visible(fn (callable $get) => $get('enrollment_status') === 'cancelled'),
                DateTimePicker::make('reviewed_at')
                    ->label('Fecha de revisión')
                    ->disabled(),
            ]);
    }
}