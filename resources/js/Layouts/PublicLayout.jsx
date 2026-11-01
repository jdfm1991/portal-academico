import { Link, usePage } from '@inertiajs/react';

export default function PublicLayout({ children }) {
    const { auth } = usePage().props;

    return (
        <div className="min-h-screen flex flex-col bg-gray-50">
            <header className="bg-white shadow-sm">
                <nav className="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                    <Link href="/" className="text-xl font-bold text-blue-600">
                        Portal Academia
                    </Link>

                    <div className="flex items-center gap-4">
                        <Link href="/cursos" className="text-gray-700 hover:text-blue-600">
                            Cursos
                        </Link>

                        {auth.user ? (
                            <>
                                <span className="text-sm text-gray-600">
                                    Hola, {auth.user.name}
                                </span>
                                {['admin', 'teacher'].includes(auth.user.role) && (
                                    <a href="/admin" className="text-sm text-blue-600 hover:underline">
                                        Panel
                                    </a>
                                )}
                                {auth.user.role === 'student' && (
                                    <Link href="/mi-cuenta/cursos" className="text-sm text-blue-600 hover:underline">
                                        Mi cuenta
                                    </Link>
                                )}
                                <Link
                                    href="/logout"
                                    method="post"
                                    as="button"
                                    className="text-sm text-gray-600 hover:text-red-600"
                                >
                                    Cerrar sesión
                                </Link>
                            </>
                        ) : (
                            <>
                                <Link href="/login" className="text-gray-700 hover:text-blue-600">
                                    Iniciar sesión
                                </Link>
                                <Link
                                    href="/register"
                                    className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                                >
                                    Registrarse
                                </Link>
                            </>
                        )}
                    </div>
                </nav>
            </header>

            <main className="flex-1">{children}</main>

            <footer className="bg-white border-t mt-12">
                <div className="max-w-6xl mx-auto px-4 py-6 text-center text-sm text-gray-600">
                    © {new Date().getFullYear()} Portal Academia. Todos los derechos reservados.
                </div>
            </footer>
        </div>
    );
}