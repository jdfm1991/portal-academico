import { Link, usePage } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Index({ enrollments }) {
    const { flash } = usePage().props;

    const statusLabel = {
        pending: 'Pendiente',
        approved: 'Aprobada',
        rejected: 'Rechazada',
        cancelled: 'Cancelada',
    };

    const statusColor = {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
        cancelled: 'bg-gray-100 text-gray-800',
    };

    const paymentLabel = {
        not_required: 'No requerido',
        pending: 'Pendiente',
        paid: 'Pagado',
        failed: 'Fallido',
        refunded: 'Reembolsado',
    };

    return (
        <PublicLayout>
            <div className="max-w-6xl mx-auto px-4 py-8">
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-bold">Mis inscripciones</h1>
                    <Link href="/cursos" className="text-blue-600 hover:underline">
                        Explorar más cursos
                    </Link>
                </div>

                {flash.status && (
                    <div className="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {flash.status}
                    </div>
                )}

                {enrollments.length === 0 ? (
                    <div className="bg-white p-12 rounded-lg shadow text-center">
                        <p className="text-gray-500 mb-4">Aún no tienes inscripciones.</p>
                        <Link
                            href="/cursos"
                            className="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
                        >
                            Ver catálogo de cursos
                        </Link>
                    </div>
                ) : (
                    <div className="space-y-4">
                        {enrollments.map((enrollment) => (
                            <div key={enrollment.id} className="bg-white rounded-lg shadow p-6">
                                <div className="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                                    <div className="flex-1">
                                        <span className="text-sm text-blue-600 font-medium uppercase">
                                            {enrollment.course.category.name}
                                        </span>
                                        <h2 className="text-xl font-bold mt-1 mb-2">
                                            {enrollment.course.title}
                                        </h2>
                                        <p className="text-sm text-gray-600 mb-3">
                                            Solicitada el {new Date(enrollment.requested_at).toLocaleDateString('es-ES')}
                                        </p>

                                        <div className="flex flex-wrap gap-2">
                                            <span className={`px-3 py-1 rounded text-sm font-medium ${statusColor[enrollment.enrollment_status]}`}>
                                                {statusLabel[enrollment.enrollment_status]}
                                            </span>
                                            <span className="px-3 py-1 rounded text-sm font-medium bg-gray-100 text-gray-800">
                                                Pago: {paymentLabel[enrollment.payment_status]}
                                            </span>
                                        </div>
                                    </div>

                                    <div className="flex flex-col gap-2 md:items-end">
                                        {enrollment.enrollment_status === 'approved' && (
                                            <Link
                                                href={`/mi-cuenta/cursos/${enrollment.course.slug}/materiales`}
                                                className="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
                                            >
                                                Ver materiales
                                            </Link>
                                        )}

                                        {enrollment.payment_status === 'pending' && (
                                            <Link
                                                href={`/mi-cuenta/inscripciones/${enrollment.id}/comprobante`}
                                                className="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700"
                                            >
                                                Subir comprobante
                                            </Link>
                                        )}

                                        <Link
                                            href={`/cursos/${enrollment.course.slug}`}
                                            className="text-blue-600 hover:underline text-sm text-center"
                                        >
                                            Ver curso
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}