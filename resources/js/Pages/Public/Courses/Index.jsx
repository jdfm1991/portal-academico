import { Link, router } from '@inertiajs/react';
import { useState } from 'react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Index({ courses, categories, filters }) {
    const [search, setSearch] = useState(filters.search || '');

    const updateFilters = (key, value) => {
        const newFilters = { ...filters, [key]: value };
        // Elimina claves vacías para que no aparezcan en la URL
        Object.keys(newFilters).forEach((k) => {
            if (!newFilters[k]) delete newFilters[k];
        });
        router.get('/cursos', newFilters, { preserveState: true, preserveScroll: true });
    };

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        updateFilters('search', search);
    };

    const clearFilters = () => {
        setSearch('');
        router.get('/cursos', {}, { preserveState: true });
    };

    const hasFilters = Object.keys(filters).length > 0;

    return (
        <PublicLayout>
            <div className="max-w-6xl mx-auto px-4 py-8">
                <h1 className="text-3xl font-bold mb-6">Catálogo de cursos</h1>

                <div className="flex flex-col lg:flex-row gap-6">
                    {/* Sidebar de filtros */}
                    <aside className="lg:w-64 flex-shrink-0">
                        <div className="bg-white p-4 rounded-lg shadow space-y-4">
                            <form onSubmit={handleSearchSubmit}>
                                <label className="block text-sm font-medium mb-1">Buscar</label>
                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Título del curso"
                                    className="w-full border rounded px-3 py-2 text-sm"
                                />
                            </form>

                            <div>
                                <label className="block text-sm font-medium mb-1">Categoría</label>
                                <select
                                    value={filters.category || ''}
                                    onChange={(e) => updateFilters('category', e.target.value)}
                                    className="w-full border rounded px-3 py-2 text-sm"
                                >
                                    <option value="">Todas</option>
                                    {categories.map((cat) => (
                                        <option key={cat.id} value={cat.slug}>{cat.name}</option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">Modalidad</label>
                                <select
                                    value={filters.modality || ''}
                                    onChange={(e) => updateFilters('modality', e.target.value)}
                                    className="w-full border rounded px-3 py-2 text-sm"
                                >
                                    <option value="">Todas</option>
                                    <option value="presencial">Presencial</option>
                                    <option value="online">Online</option>
                                    <option value="hibrido">Híbrido</option>
                                </select>
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">Nivel</label>
                                <select
                                    value={filters.level || ''}
                                    onChange={(e) => updateFilters('level', e.target.value)}
                                    className="w-full border rounded px-3 py-2 text-sm"
                                >
                                    <option value="">Todos</option>
                                    <option value="principiante">Principiante</option>
                                    <option value="intermedio">Intermedio</option>
                                    <option value="avanzado">Avanzado</option>
                                </select>
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">Precio máximo</label>
                                <input
                                    type="number"
                                    value={filters.max_price || ''}
                                    onChange={(e) => updateFilters('max_price', e.target.value)}
                                    placeholder="Sin límite"
                                    className="w-full border rounded px-3 py-2 text-sm"
                                />
                            </div>

                            <div className="flex items-center">
                                <input
                                    type="checkbox"
                                    id="free"
                                    checked={filters.free === '1' || filters.free === true}
                                    onChange={(e) => updateFilters('free', e.target.checked ? '1' : '')}
                                    className="mr-2"
                                />
                                <label htmlFor="free" className="text-sm font-medium">Solo cursos gratuitos</label>
                            </div>

                            {hasFilters && (
                                <button
                                    onClick={clearFilters}
                                    className="w-full text-sm text-red-600 hover:underline"
                                >
                                    Limpiar filtros
                                </button>
                            )}
                        </div>
                    </aside>

                    {/* Listado de cursos */}
                    <div className="flex-1">
                        {courses.data.length === 0 ? (
                            <div className="bg-white p-12 rounded-lg shadow text-center">
                                <p className="text-gray-500 mb-4">No se encontraron cursos con esos filtros.</p>
                                <button onClick={clearFilters} className="text-blue-600 hover:underline">
                                    Ver todos los cursos
                                </button>
                            </div>
                        ) : (
                            <>
                                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    {courses.data.map((course) => (
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
                                                    <span className="text-gray-500 capitalize">{course.modality}</span>
                                                    <span className="font-bold text-blue-600">${course.price}</span>
                                                </div>
                                            </div>
                                        </Link>
                                    ))}
                                </div>

                                {/* Paginación */}
                                {courses.last_page > 1 && (
                                    <div className="mt-8 flex justify-center gap-2">
                                        {courses.links.map((link, i) => (
                                            <Link
                                                key={i}
                                                href={link.url || '#'}
                                                className={`px-3 py-1 rounded border text-sm ${link.active
                                                    ? 'bg-blue-600 text-white border-blue-600'
                                                    : link.url
                                                        ? 'bg-white text-gray-700 hover:bg-gray-50'
                                                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                    }`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                                preserveScroll
                                            />
                                        ))}
                                    </div>
                                )}
                            </>
                        )}
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}