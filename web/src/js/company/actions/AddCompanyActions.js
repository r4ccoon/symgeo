import alt from '../alt';
import url from '../../common/RUrl';

class AddCompanyActions {
    postResult(result) {
        return result;
    }

    handleSubmit(company) {
        return (dispatch) => {
            dispatch();
            fetch(url.resource('company'), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(company)
            })
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