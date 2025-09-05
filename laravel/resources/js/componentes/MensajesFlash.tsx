import { MensajesFlashProps } from "../Interfaces/MensajesFlashProps";


const MensajesFlash: React.FC<MensajesFlashProps> = ({ flash }) => {
    // Si no hay mensaje, no renderizar nada
    if (!flash?.mensaje && !flash?.success) {
        return null;
    }

    // Determinar el tipo de alerta
    const alertType = flash?.css || 
                     (flash?.success ? 'success' : 'info') || 
                     (flash?.error ? 'danger' : 'info');

    // Determinar el mensaje a mostrar
    const message = flash?.mensaje || flash?.success || flash?.error;

    return (
        <div className={`alert alert-${alertType} alert-dismissible fade show`} role="alert">
            {message}
            <button 
                type="button" 
                className="btn-close" 
                data-bs-dismiss="alert" 
                aria-label="Close"
            ></button>
        </div>
    );
};

export default MensajesFlash;