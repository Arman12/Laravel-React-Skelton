import React, { ReactNode } from 'react';
/**
 * Modal.
 *
 * Author 'Arman Saleem'
 * 
 * @returns {TSX.Element} - TSX representation of the component.
 */
interface ModalProps {
  isOpen: boolean;
  onClose?: () => void;
  children: ReactNode;
  className?: string;
}
const Modal = (props: ModalProps) => {
  const { isOpen, onClose, children, className } = props;
  if (!isOpen) {
    return null;
  }

  return (
    <div className={className + " modal"}>
      <div className="modal-content">
        {children}
        <div className="modal-footer">
          <button className="primary-btn" onClick={onClose}>
            OK
          </button>
          <button className="close-button" onClick={onClose}>
            Close
          </button>
        </div>
      </div>
    </div>
  );
};

export default Modal;
