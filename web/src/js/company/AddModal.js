import React from "react";
import Modal from "react-bootstrap/lib/Modal";
import UserStore from "./stores/UserStore";
import UserActions from "./actions/UserActions";
import AddCompanyActions from "./actions/AddCompanyActions";
import connectToStores from "alt-utils/lib/connectToStores";

class AddModal extends React.Component {
    constructor() {
        super();
        this.getUserTimer = null;
        this.onOwnerInputChange = this.onOwnerInputChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleNameChange = this.handleNameChange.bind(this);
        this.state = {
            name: '',
            owner: ''
        };
    }

    static getStores() {
        return [UserStore];
    }

    static getPropsFromStores() {
        return UserStore.getState();
    }

    onOwnerInputChange(event) {
        var eventValue = event.target.value;
        if (this.getUserTimer != null) {
            clearTimeout(this.getUserTimer);
            this.getUserTimer = null;
        }

        this.setState({owner: eventValue});

        this.getUserTimer = setTimeout(function () {
            UserActions.fetchUsers(eventValue);
        }, 1500);
    }

    handleSubmit(event) {
        console.log('bahh');
        event.preventDefault();

        var name = this.state.name.trim();
        var owner = this.state.owner.trim();
        if (!name || !owner) {
            return;
        }

        AddCompanyActions.handleSubmit({
            name: name,
            owner: owner
        });

        this.props.saveAndClose();
        console.log('bass');
    }

    handleNameChange(event) {
        this.setState({name: event.target.value});
    }

    render() {
        return (
            <div>
                <Modal
                    show={this.props.isOpen}
                    onHide={this.props.closeModal}
                    aria-labelledby="ModalHeader">
                    <Modal.Header closeButton>
                        <Modal.Title id='ModalHeader'>Add New Company</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <form role="form" onSubmit={this.handleSubmit}>
                            <div className="form-group">
                                <label>Name</label>
                                <input
                                    type="text"
                                    className="form-control"
                                    value={this.state.name}
                                    onChange={this.handleNameChange}
                                />
                            </div>
                            <div className="form-group">
                                <label>Owner</label>
                                <input type="text"
                                       className="form-control"
                                       value={this.state.owner}
                                       onChange={this.onOwnerInputChange}
                                />
                            </div>
                        </form>
                    </Modal.Body>
                    <Modal.Footer>
                        <button className='btn btn-default' onClick={this.props.cancel}>Cancel</button>
                        <button className='btn btn-primary' onClick={this.handleSubmit}>
                            Save
                        </button>
                    </Modal.Footer>
                </Modal>
            </div>
        )
    }
}

export default connectToStores(AddModal);