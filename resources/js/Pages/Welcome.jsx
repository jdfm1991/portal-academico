import { usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage().props;

    return (
        <div style={{ padding: '2rem', fontFamily: 'sans-serif' }}>
            <h1>Portal Academia</h1>
            {auth.user ? (
                <p>Hola, {auth.user.name} ({auth.user.role})</p>
            ) : (
                <p>No has iniciado sesión.</p>
            )}
        </div>
    );
}