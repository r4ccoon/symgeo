import React from 'react'
import {ControlBar, AddButton, SearchBox} from '../common/index'
import CompanyTable from './CompanyTable'
import AddModal from './AddModal'

export default class Company extends React.Component {
    constructor(props) {
        super(props);
        this.state = {isAddModalOpen: false};

        this.openAddModal = this.openAddModal.bind(this);
        this.saveAndCloseAddModal = this.saveAndCloseAddModal.bind(this);
        this.closeAddModal = this.closeAddModal.bind(this);
    }

    closeAddModal() {
        this.setState({isAddModalOpen: false});
    }

    saveAndCloseAddModal() {
        this.setState({isAddModalOpen: false});
    }

    openAddModal() {
        this.setState({isAddModalOpen: true});
    }

    render() {
        return (
            <div>
                <ControlBar>
                    <AddButton onClick={this.openAddModal}/>
                    <SearchBox />
                    <AddModal key="modal"
                              isOpen={this.state.isAddModalOpen}
                              closeModal={this.closeAddModal}
                              saveAndClose={this.saveAndCloseAddModal}
                              cancel={this.closeAddModal}
                    />
                </ControlBar>
                <CompanyTable />
            </div>
        )
    }
}