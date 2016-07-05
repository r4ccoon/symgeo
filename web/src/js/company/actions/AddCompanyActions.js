import alt from '../alt';
import RFetch from '../../common/RFetch';

class AddCompanyActions {
    postResult(result) {
        return result;
    }

    handleSubmit(company) {
        return (dispatch) => {
            dispatch();
            RFetch.post('company', false, company)
                .then((result) => {
                    // we can access other actions within our action through `this.actions`
                    this.postResult(result);
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

module.exports = alt.createActions(AddCompanyActions);