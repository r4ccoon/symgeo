import React from "react";
import {BootstrapTable, TableHeaderColumn} from 'react-bootstrap-table';
import CompanyStore from "./stores/CompanyStore";
import CompanyActions  from "./actions/CompanyActions";

class CompanyTable extends React.Component {
    constructor(props) {
        super(props);

        this.state = CompanyStore.getState();

        this.onChange = this.onChange.bind(this);
    }

    componentDidMount() {
        CompanyActions.fetch();
        CompanyStore.listen(this.onChange);
    }

    componentWillUnmount() {
        CompanyStore.unlisten(this.onChange);
    }

    onChange(state) {
        this.setState(state);
    }

    showUsername(owner) {
        return owner.username;
    }

    render() {
        return (
            <BootstrapTable data={this.state.companies} striped={true} hover={true}>
                <TableHeaderColumn dataField="id" isKey={true} dataAlign="center" dataSort={true}>
                    Id
                </TableHeaderColumn>
                <TableHeaderColumn dataField="name" dataSort={true}>Company Name</TableHeaderColumn>
                <TableHeaderColumn dataField="owner" dataFormat={this.showUsername}>Owner</TableHeaderColumn>
            </BootstrapTable>
        )
    }
}

export default CompanyTable;