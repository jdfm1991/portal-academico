import { useForm, Link } from '@inertiajs/react';

export default function Login() {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login', {
            onFinish: () => reset('password'),
        });
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <form onSubmit={submit} className="bg-white p-8 rounded shadow-md w-full max-w-md">
                <h1 className="text-2xl font-bold mb-6">Iniciar sesión</h1>

                {errors.email && (
                    <div className="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                        {errors.email}
                    </div>
                )}

                <div className="mb-4">
                    <label className="block mb-1">Email</label>
                    <input
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        className="w-full border rounded px-3 py-2"
                        required
                        autoFocus
                    />
                </div>

                <div className="mb-4">
                    <label className="block mb-1">Contraseña</label>
                    <input
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="w-full border rounded px-3 py-2"
                        required
                    />
                </div>

                <div className="mb-4">
                    <label className="flex items-center text-sm">
                        <input
                            type="checkbox"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="mr-2"
                        />
                        Recordarme
                    </label>
                </div>

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                >
                    {processing ? 'Entrando...' : 'Entrar'}
                </button>

                <div className="mt-4 text-center text-sm space-y-1">
                    <div>
                        <Link href="/forgot-password" className="text-blue-600">
                            ¿Olvidaste tu contraseña?
                        </Link>
                    </div>
                    <div>
                        <Link href="/register" className="text-blue-600">
                            Crear cuenta
                        </Link>
                    </div>
                </div>
            </form>
        </div>
    );
}