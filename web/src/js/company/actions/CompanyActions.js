import alt from '../alt';
import RFetch from '../../common/RFetch';

class CompanyActions {
    get(companies) {
        return companies;
    }

    fetch() {
        return (dispatch) => {
            dispatch();
            RFetch.get('company')
                .then((companies) => {
                    this.get(companies.company);
                })
                .catch((errorMessage) => {
                    this.failed(errorMessage);
                });
        }
    }

    failed(errorMessage) {
        return errorMessage;
    }
}

module.exports = alt.createActions(CompanyActions);