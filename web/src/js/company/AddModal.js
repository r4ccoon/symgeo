import React from "react";
import Modal from "react-bootstrap/lib/Modal";

export default class AddModal extends React.Component { 
    render() {
        return (
            <div>
                <Modal
                    show={this.props.isOpen}
                    onHide={this.props.closeModal}
                    aria-labelledby="ModalHeader">
                    <Modal.Header closeButton>
                        <Modal.Title id='ModalHeader'>A Title Goes here</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <p>Some Content here</p>
                    </Modal.Body>
                    <Modal.Footer>
                        <button className='btn btn-default' onClick={this.props.cancel}>Cancel</button>
                        <button className='btn btn-primary' onClick={this.props.saveAndClose}>
                            Save
                        </button>
                    </Modal.Footer>
                </Modal>
            </div>
        )
    }
}