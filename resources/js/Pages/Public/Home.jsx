import { Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Home({ featuredCourses, categories }) {
    return (
        <PublicLayout>
            {/* Hero */}
            <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                <div className="max-w-6xl mx-auto px-4 py-20 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">
                        Aprende con los mejores profesionales
                    </h1>
                    <p className="text-lg md:text-xl mb-8 text-blue-100">
                        Cursos presenciales, online e híbridos para impulsar tu carrera.
                    </p>
                    <Link
                        href="/cursos"
                        className="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50"
                    >
                        Ver catálogo de cursos
                    </Link>
                </div>
            </section>

            {/* Categorías */}
            <section className="max-w-6xl mx-auto px-4 py-12">
                <h2 className="text-2xl font-bold mb-6">Explora por categoría</h2>
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    {categories.map((category) => (
                        <Link
                            key={category.id}
                            href={`/cursos?category=${category.slug}`}
                            className="bg-white p-6 rounded-lg shadow hover:shadow-md text-center"
                        >
                            <span className="text-gray-800 font-medium">{category.name}</span>
                        </Link>
                    ))}
                </div>
            </section>

            {/* Cursos destacados */}
            <section className="max-w-6xl mx-auto px-4 py-12">
                <h2 className="text-2xl font-bold mb-6">Cursos destacados</h2>
                {featuredCourses.length === 0 ? (
                    <p className="text-gray-500">No hay cursos publicados todavía.</p>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {featuredCourses.map((course) => (
                            <Link
                                key={course.id}
                                href={`/cursos/${course.slug}`}
                                className="bg-white rounded-lg shadow hover:shadow-md overflow-hidden"
                            >
                                <div className="h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                                    {course.image_url ? (
                                        <img src={course.image_url} alt={course.title} className="w-full h-full object-cover" />
                                    ) : (
                                        'Sin imagen'
                                    )}
                                </div>
                                <div className="p-4">
                                    <span className="text-xs text-blue-600 font-medium uppercase">
                                        {course.category.name}
                                    </span>
                                    <h3 className="text-lg font-semibold mt-1 mb-2">{course.title}</h3>
                                    <p className="text-sm text-gray-600 mb-3 line-clamp-2">
                                        {course.description}
                                    </p>
                                    <div className="flex justify-between items-center text-sm">
                                        <span className="text-gray-500">{course.modality}</span>
                                        <span className="font-bold text-blue-600">${course.price}</span>
                                    </div>
                                </div>
                            </Link>
                        ))}
                    </div>
                )}
            </section>
        </PublicLayout>
    );
}