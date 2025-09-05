export interface AlertCustomInterface {
    estado: boolean;
    titulo: string;
    detalle: string;
    onClose?: () => void;
    onConfirm?: () => void; // Nueva prop para la acción de confirmación
    headerBg?: string;
    esConfirm?: boolean; // Flag para determinar si es un confirm
    confirmText?: string; // Texto personalizado para el botón de confirmar
    cancelText?: string; // Texto personalizado para el botón de cancelar
}