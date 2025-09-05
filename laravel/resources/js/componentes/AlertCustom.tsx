import { useState, useEffect } from "react";
import { Button } from "react-bootstrap";
import Modal from "react-bootstrap/Modal";
import { AlertCustomInterface } from "../Interfaces/AlertCustomInterface";



const AlertCustom = (datos: AlertCustomInterface) => {
    const [show, setShow] = useState(datos.estado);
    
    useEffect(() => {
        setShow(datos.estado);
    }, [datos.estado]);

    const handleClose = () => {
        setShow(false);
        datos.onClose?.();
    }

    const handleConfirm = () => {
        setShow(false);
        datos.onConfirm?.();
    }

    const headerClass = datos.headerBg ? datos.headerBg : 'bg-primary';
    const confirmText = datos.confirmText || 'Aceptar';
    const cancelText = datos.cancelText || 'Cancelar';

    return (
        <Modal show={show} size="sm" onHide={handleClose}>
            <Modal.Header 
                closeButton 
                className={`${headerClass}`}
                style={{ borderBottom: 'none' }}
            >
                <Modal.Title className="text-white">{datos.titulo}</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <div className="row">
                    {datos.detalle}
                </div>
            </Modal.Body>
            <Modal.Footer>
                {datos.esConfirm && (
                    <Button variant="secondary" onClick={handleClose} title={cancelText}>
                        {cancelText}
                    </Button>
                )}
                <Button 
                    variant="primary" 
                    onClick={datos.esConfirm ? handleConfirm : handleClose} 
                    title={confirmText}
                >
                    {confirmText}
                </Button>
            </Modal.Footer>
        </Modal>
    );
}

export default AlertCustom;

