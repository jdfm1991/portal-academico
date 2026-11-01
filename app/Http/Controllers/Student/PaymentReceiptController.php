<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\PaymentReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PaymentReceiptController extends Controller
{
    public function create(Enrollment $enrollment)
    {
        // Verificación de seguridad: la inscripción debe pertenecer al estudiante autenticado
        abort_if($enrollment->student_id !== Auth::id(), 403);

        // Solo se puede subir si el pago está pendiente
        abort_if($enrollment->payment_status !== 'pending', 403);

        return Inertia::render('Student/Receipts/Create', [
            'enrollment' => $enrollment->load('course'),
        ]);
    }

    public function store(Request $request, Enrollment $enrollment)
    {
        // Mismas verificaciones de seguridad
        abort_if($enrollment->student_id !== Auth::id(), 403);
        abort_if($enrollment->payment_status !== 'pending', 403);

        $validated = $request->validate([
            'receipt' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5 MB
        ], [
            'receipt.required' => 'Debes seleccionar un archivo.',
            'receipt.mimes' => 'El archivo debe ser una imagen (JPG, PNG) o un PDF.',
            'receipt.max' => 'El archivo no puede pesar más de 5 MB.',
        ]);

        // Guardar el archivo en el disco local (privado)
        $path = $request->file('receipt')->store('receipts', 'local');

        // Crear el registro en la base de datos
        PaymentReceipt::create([
            'enrollment_id' => $enrollment->id,
            'file_path' => $path,
            'uploaded_at' => now(),
        ]);

        return redirect()
            ->route('student.courses')
            ->with('status', 'Comprobante subido correctamente. El administrador lo revisará pronto.');
    }
}