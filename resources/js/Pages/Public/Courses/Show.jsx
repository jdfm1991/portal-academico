import { Link, usePage } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Show({ course, hasEnrollment }) {
    const { auth, flash, errors } = usePage().props;

    return (
        <PublicLayout>
            <div className="max-w-4xl mx-auto px-4 py-8">
                {errors.enrollment && (
                    <div className="mb-4 p-3 bg-red-100 text-red-700 rounded">
                        {errors.enrollment}
                    </div>
                )}
                {/* Breadcrumb */}
                <nav className="text-sm text-gray-500 mb-4">
                    <Link href="/cursos" className="hover:text-blue-600">Cursos</Link>
                    <span className="mx-2">/</span>
                    <span className="text-gray-700">{course.title}</span>
                </nav>

                {/* Header */}
                <div className="bg-white rounded-lg shadow p-6 mb-6">
                    <span className="text-sm text-blue-600 font-medium uppercase">
                        {course.category.name}
                    </span>
                    <h1 className="text-3xl font-bold mt-2 mb-4">{course.title}</h1>
                    <p className="text-gray-600 mb-6">{course.description}</p>

                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span className="block text-gray-500">Modalidad</span>
                            <span className="font-medium capitalize">{course.modality}</span>
                        </div>
                        <div>
                            <span className="block text-gray-500">Nivel</span>
                            <span className="font-medium capitalize">{course.level}</span>
                        </div>
                        <div>
                            <span className="block text-gray-500">Duración</span>
                            <span className="font-medium">{course.duration || 'No especificada'}</span>
                        </div>
                        <div>
                            <span className="block text-gray-500">Precio</span>
                            <span className="font-bold text-blue-600 text-lg">${course.price}</span>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Columna principal */}
                    <div className="md:col-span-2 space-y-6">
                        {/* Temario */}
                        <div className="bg-white rounded-lg shadow p-6">
                            <h2 className="text-xl font-bold mb-4">Temario</h2>
                            {course.syllabus ? (
                                <div className="text-gray-700 whitespace-pre-line">{course.syllabus}</div>
                            ) : (
                                <p className="text-gray-500">El temario se publicará próximamente.</p>
                            )}
                        </div>

                        {/* Fechas */}
                        {(course.start_date || course.end_date) && (
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4">Fechas</h2>
                                <div className="space-y-2 text-sm">
                                    {course.start_date && (
                                        <p><span className="text-gray-500">Inicio:</span> {course.start_date}</p>
                                    )}
                                    {course.end_date && (
                                        <p><span className="text-gray-500">Fin:</span> {course.end_date}</p>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Sidebar */}
                    <aside className="space-y-6">
                        {/* Profesor */}
                        {course.teacher && (
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-lg font-bold mb-3">Profesor</h2>
                                <p className="font-medium">{course.teacher.user.name}</p>
                                {course.teacher.specialty && (
                                    <p className="text-sm text-gray-600 mt-1">{course.teacher.specialty}</p>
                                )}
                                {course.teacher.biography && (
                                    <p className="text-sm text-gray-600 mt-3">{course.teacher.biography}</p>
                                )}
                            </div>
                        )}

                        <div className="bg-white rounded-lg shadow p-6">
                            {auth.user ? (
                                hasEnrollment ? (
                                    <div className="text-center">
                                        <p className="text-green-700 font-medium mb-2">Ya estás inscrito en este curso.</p>
                                        <Link href="/mi-cuenta/cursos" className="text-blue-600 hover:underline text-sm">
                                            Ver mis inscripciones
                                        </Link>
                                    </div>
                                ) : (
                                    <Link
                                        href={`/cursos/${course.slug}/inscribirse`}
                                        method="post"
                                        as="button"
                                        className="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700"
                                    >
                                        Inscribirme
                                    </Link>
                                )
                            ) : (
                                <>
                                    <p className="text-sm text-gray-600 mb-3">
                                        Para inscribirte, inicia sesión o crea una cuenta.
                                    </p>
                                    <Link
                                        href="/login"
                                        className="block w-full text-center bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 mb-2"
                                    >
                                        Iniciar sesión
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="block w-full text-center border border-blue-600 text-blue-600 py-3 rounded-lg font-semibold hover:bg-blue-50"
                                    >
                                        Crear cuenta
                                    </Link>
                                </>
                            )}
                        </div>
                    </aside>
                </div>
            </div>
        </PublicLayout>
    );
}