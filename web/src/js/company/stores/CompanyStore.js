import alt from "../alt";
import CompanyActions from "../actions/CompanyActions" ;

class CompanyStore {
    constructor() {
        this.companies = [];
        this.errorMessage = null;

        this.bindListeners({
            handleGet: CompanyActions.get,
            handleFetch: CompanyActions.fetch,
            handleFailed: CompanyActions.failed
        });
    }

    handleGet(companies) {
        this.companies = companies;
        this.errorMessage = null;
    }

    handleFetch() {
        this.companies = []
    }

    handleFailed(errorMessage) {
        this.errorMessage = errorMessage;
    }
}

export default alt.createStore(CompanyStore, 'CompanyStore');
