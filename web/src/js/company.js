import React from 'react'
import Company from './company/Company'
import {render} from 'react-dom'

export default class CompanyController {
    run() {
        render(
            <Company />,
            document.getElementById('company')
        )
    }
}