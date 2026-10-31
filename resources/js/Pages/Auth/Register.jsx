import { useForm, Link } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        phone: '',
        document_id: '',
        address: '',
        birth_date: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register', {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100 py-8">
            <form onSubmit={submit} className="bg-white p-8 rounded shadow-md w-full max-w-md">
                <h1 className="text-2xl font-bold mb-6">Crear cuenta</h1>

                {Object.keys(errors).length > 0 && (
                    <div className="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                        Revisa los campos marcados.
                    </div>
                )}

                <div className="mb-4">
                    <label className="block mb-1">Nombre completo *</label>
                    <input
                        type="text"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        className="w-full border rounded px-3 py-2"
                        required
                    />
                    {errors.name && <p className="text-red-500 text-sm mt-1">{errors.name}</p>}
                </div>

                <div className="mb-4">
                    <label className="block mb-1">Email *</label>
                    <input
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        className="w-full border rounded px-3 py-2"
                        required
                    />
                    {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email}</p>}
                </div>

                <div className="mb-4">
                    <label className="block mb-1">Contraseña * (mínimo 8 caracteres)</label>
                    <input
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="w-full border rounded px-3 py-2"
                        required
                    />
                    {errors.password && <p className="text-red-500 text-sm mt-1">{errors.password}</p>}
                </div>

                <div className="mb-4">
                    <label className="block mb-1">Confirmar contraseña *</label>
                    <input
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        className="w-full border rounded px-3 py-2"
                        required
                    />
                </div>

                <details className="mb-4">
                    <summary className="cursor-pointer text-sm text-gray-600">Datos opcionales</summary>

                    <div className="mt-3">
                        <label className="block mb-1 text-sm">Teléfono</label>
                        <input
                            type="text"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            className="w-full border rounded px-3 py-2"
                        />
                        {errors.phone && <p className="text-red-500 text-sm mt-1">{errors.phone}</p>}
                    </div>

                    <div className="mt-3">
                        <label className="block mb-1 text-sm">Documento de identidad</label>
                        <input
                            type="text"
                            value={data.document_id}
                            onChange={(e) => setData('document_id', e.target.value)}
                            className="w-full border rounded px-3 py-2"
                        />
                        {errors.document_id && <p className="text-red-500 text-sm mt-1">{errors.document_id}</p>}
                    </div>

                    <div className="mt-3">
                        <label className="block mb-1 text-sm">Dirección</label>
                        <textarea
                            value={data.address}
                            onChange={(e) => setData('address', e.target.value)}
                            className="w-full border rounded px-3 py-2"
                            rows="2"
                        />
                        {errors.address && <p className="text-red-500 text-sm mt-1">{errors.address}</p>}
                    </div>

                    <div className="mt-3">
                        <label className="block mb-1 text-sm">Fecha de nacimiento</label>
                        <input
                            type="date"
                            value={data.birth_date}
                            onChange={(e) => setData('birth_date', e.target.value)}
                            className="w-full border rounded px-3 py-2"
                        />
                        {errors.birth_date && <p className="text-red-500 text-sm mt-1">{errors.birth_date}</p>}
                    </div>
                </details>

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                >
                    {processing ? 'Creando cuenta...' : 'Crear cuenta'}
                </button>

                <div className="mt-4 text-center text-sm">
                    ¿Ya tienes cuenta?{' '}
                    <Link href="/login" className="text-blue-600">
                        Inicia sesión
                    </Link>
                </div>
            </form>
        </div>
    );
}