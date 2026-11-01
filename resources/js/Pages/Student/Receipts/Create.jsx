import { useForm, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Create({ enrollment }) {
    const { data, setData, post, processing, errors, progress } = useForm({
        receipt: null,
    });

    const submit = (e) => {
        e.preventDefault();
        post(`/mi-cuenta/inscripciones/${enrollment.id}/comprobante`);
    };

    return (
        <PublicLayout>
            <div className="max-w-2xl mx-auto px-4 py-8">
                <Link href="/mi-cuenta/cursos" className="text-blue-600 hover:underline text-sm mb-4 inline-block">
                    ← Volver a mis inscripciones
                </Link>

                <div className="bg-white rounded-lg shadow p-6">
                    <h1 className="text-2xl font-bold mb-2">Subir comprobante de pago</h1>
                    <p className="text-gray-600 mb-6">
                        Curso: <strong>{enrollment.course.title}</strong>
                    </p>

                    {errors.receipt && (
                        <div className="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                            {errors.receipt}
                        </div>
                    )}

                    <form onSubmit={submit}>
                        <div className="mb-4">
                            <label className="block mb-2 font-medium">
                                Selecciona tu comprobante (JPG, PNG o PDF, máximo 5 MB)
                            </label>
                            <input
                                type="file"
                                accept=".jpg,.jpeg,.png,.pdf"
                                onChange={(e) => setData('receipt', e.target.files[0])}
                                className="w-full border rounded px-3 py-2"
                            />
                        </div>

                        {progress && (
                            <div className="mb-4">
                                <div className="w-full bg-gray-200 rounded h-2">
                                    <div
                                        className="bg-blue-600 h-2 rounded"
                                        style={{ width: `${progress.percentage}%` }}
                                    />
                                </div>
                                <p className="text-sm text-gray-600 mt-1">{progress.percentage}% subido</p>
                            </div>
                        )}

                        <button
                            type="submit"
                            disabled={processing || !data.receipt}
                            className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                        >
                            {processing ? 'Subiendo...' : 'Subir comprobante'}
                        </button>
                    </form>
                </div>
            </div>
        </PublicLayout>
    );
}