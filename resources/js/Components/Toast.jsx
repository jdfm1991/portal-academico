import { useEffect, useState } from 'react';

export default function Toast({ message, type = 'success', onClose }) {
    const [visible, setVisible] = useState(true);

    useEffect(() => {
        const timer = setTimeout(() => {
            setVisible(false);
            if (onClose) onClose();
        }, 5000); // Se cierra solo en 5 segundos

        return () => clearTimeout(timer);
    }, [onClose]);

    if (!visible || !message) return null;

    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

    return (
        <div className={`fixed bottom-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${bgColor} transition-all duration-300`}>
            {message}
            <button onClick={() => setVisible(false)} className="ml-4 font-bold">
                ×
            </button>
        </div>
    );
}