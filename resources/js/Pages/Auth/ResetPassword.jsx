import { useForm } from '@inertiajs/react';

export default function ResetPassword({ token, email }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        token: token,
        email: email || '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/reset-password', {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <form onSubmit={submit} className="bg-white p-8 rounded shadow-md w-full max-w-md">
                <h1 className="text-2xl font-bold mb-6">Restablecer contraseña</h1>

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
                    />
                </div>

                <div className="mb-4">
                    <label className="block mb-1">Nueva contraseña (mínimo 8 caracteres)</label>
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
                    <label className="block mb-1">Confirmar nueva contraseña</label>
                    <input
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        className="w-full border rounded px-3 py-2"
                        required
                    />
                </div>

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                >
                    {processing ? 'Actualizando...' : 'Actualizar contraseña'}
                </button>
            </form>
        </div>
    );
}