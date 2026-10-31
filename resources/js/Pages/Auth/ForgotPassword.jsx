import { useForm, usePage, Link } from '@inertiajs/react';

export default function ForgotPassword() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });
    const { status } = usePage().props.flash || {};

    const submit = (e) => {
        e.preventDefault();
        post('/forgot-password');
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <form onSubmit={submit} className="bg-white p-8 rounded shadow-md w-full max-w-md">
                <h1 className="text-2xl font-bold mb-4">Recuperar contraseña</h1>
                <p className="text-gray-600 mb-6 text-sm">
                    Introduce tu email y te enviaremos un enlace para restablecer tu contraseña.
                </p>

                {status && (
                    <div className="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
                        {status}
                    </div>
                )}

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

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                >
                    {processing ? 'Enviando...' : 'Enviar enlace'}
                </button>

                <div className="mt-4 text-center text-sm">
                    <Link href="/login" className="text-blue-600">
                        Volver al login
                    </Link>
                </div>
            </form>
        </div>
    );
}