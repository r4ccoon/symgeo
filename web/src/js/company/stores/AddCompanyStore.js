import alt from "../alt";
import AddCompanyActions from "../actions/AddCompanyActions";

class AddCompanyStore {
    constructor() {
        this.company = [];
        this.errorMessage = null;
        this.result = [];

        this.bindListeners({
            handlePostResult: AddCompanyActions.postResult,
            handleSubmit: AddCompanyActions.handleSubmit,
            handleFailed: AddCompanyActions.failed
        });
    }

    handlePostResult(result) {
        this.result = result;
        this.errorMessage = null;
    }

    handleSubmit() {
        this.company = []
    }

    handleFailed(errorMessage) {
        this.errorMessage = errorMessage;
    }

}

export default alt.createStore(AddCompanyStore, 'AddCompanyStore');
