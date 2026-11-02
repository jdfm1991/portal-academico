import { Link, usePage } from '@inertiajs/react';
import Toast from '@/Components/Toast';
import { useState, useEffect } from 'react';

export default function PublicLayout({ children }) {
    const { auth, flash, academy } = usePage().props;
    const [toast, setToast] = useState(null);

    // Efecto para detectar cuando llega un mensaje flash
    useEffect(() => {
        if (flash?.status) {
            setToast({ message: flash.status, type: 'success' });
        }
    }, [flash?.status]);


    return (
        <div className="min-h-screen flex flex-col bg-gray-50">
            {/* Mostrar el Toast si existe */}
            {toast && (
                <Toast
                    message={toast.message}
                    type={toast.type}
                    onClose={() => setToast(null)}
                />
            )}

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
                <div className="max-w-6xl mx-auto px-4 py-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                        <div>
                            <h3 className="font-bold text-lg mb-2">{academy?.name || 'Portal Academia'}</h3>
                            <p className="text-sm text-gray-600">{academy?.slogan}</p>
                        </div>
                        <div>
                            <h4 className="font-medium mb-2">Contacto</h4>
                            <p className="text-sm text-gray-600">{academy?.address}</p>
                            <p className="text-sm text-gray-600">{academy?.phone}</p>
                            <p className="text-sm text-gray-600">{academy?.email}</p>
                        </div>
                        <div>
                            <h4 className="font-medium mb-2">Síguenos</h4>
                            <div className="flex gap-4 text-sm">
                                {academy?.instagram && <span className="text-gray-600">{academy.instagram}</span>}
                                {academy?.facebook && <span className="text-gray-600">{academy.facebook}</span>}
                            </div>
                        </div>
                    </div>
                    <div className="text-center text-sm text-gray-500 border-t pt-4">
                        © {new Date().getFullYear()} {academy?.name || 'Portal Academia'}. Todos los derechos reservados.
                    </div>
                </div>
            </footer>
        </div>
    );
}