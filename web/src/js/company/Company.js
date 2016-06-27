import React from 'react'
import {ControlBar, AddButton, SearchBox} from '../common/index'
import CompanyTable from './CompanyTable'

export default class Company extends React.Component {
    render() {
        return (
            <ControlBar>
                <AddButton />
                <SearchBox />
                <CompanyTable />
            </ControlBar>
        )
    }
}