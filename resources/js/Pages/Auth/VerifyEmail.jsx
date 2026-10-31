import { useForm, usePage, Link } from '@inertiajs/react';

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});
    const { auth } = usePage().props;

    const resend = (e) => {
        e.preventDefault();
        post('/email/verification-notification');
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <div className="bg-white p-8 rounded shadow-md w-full max-w-md">
                <h1 className="text-2xl font-bold mb-4">Verifica tu correo</h1>

                <p className="text-gray-600 mb-6">
                    Hemos enviado un enlace de verificación a <strong>{auth.user?.email}</strong>.
                    Revisa tu bandeja de entrada y haz clic en el enlace.
                </p>

                {status === 'verification-link-sent' && (
                    <div className="mb-4 text-green-600 text-sm">
                        Se ha enviado un nuevo enlace de verificación.
                    </div>
                )}

                <form onSubmit={resend}>
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        {processing ? 'Enviando...' : 'Reenviar email de verificación'}
                    </button>
                </form>

                <div className="mt-4 text-center text-sm">
                    <Link href="/logout" method="post" as="button" className="text-gray-600">
                        Cerrar sesión
                    </Link>
                </div>
            </div>
        </div>
    );
}