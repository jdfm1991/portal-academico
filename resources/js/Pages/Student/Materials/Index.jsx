import { Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Index({ course, materials }) {
    return (
        <PublicLayout>
            <div className="max-w-4xl mx-auto px-4 py-8">
                <Link href="/mi-cuenta/cursos" className="text-blue-600 hover:underline text-sm mb-4 inline-block">
                    ← Volver a mis inscripciones
                </Link>

                <div className="mb-6">
                    <h1 className="text-3xl font-bold">{course.title}</h1>
                    <p className="text-gray-600 mt-1">Materiales del curso</p>
                </div>

                {materials.length === 0 ? (
                    <div className="bg-white p-12 rounded-lg shadow text-center">
                        <p className="text-gray-500">El profesor aún no ha publicado materiales para este curso.</p>
                    </div>
                ) : (
                    <div className="space-y-3">
                        {materials.map((material) => (
                            <div key={material.id} className="bg-white rounded-lg shadow p-4 flex justify-between items-center">
                                <div>
                                    <h3 className="font-medium">{material.title}</h3>
                                    <span className="text-xs text-gray-500 uppercase">
                                        {material.type === 'file' ? 'Archivo' : 'Enlace externo'}
                                    </span>
                                </div>

                                {material.type === 'file' ? (
                                    <a
                                        href={material.temporary_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
                                    >
                                        Descargar
                                    </a>
                                ) : (
                                    <a
                                        href={material.external_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
                                    >
                                        Abrir enlace
                                    </a>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}