import alt from "../alt";
import UserActions from "../actions/UserActions";

class UserStore {
    constructor() {
        this.users = [];
        this.errorMessage = null;

        this.bindListeners({
            handleGetUsers: UserActions.getUsers,
            handleFetchUsers: UserActions.fetchUsers,
            handleFailed: UserActions.failed
        });
    }

    handleGetUsers(users) {
        this.users = users;
        this.errorMessage = null;
    }

    handleFetchUsers() {
        this.users = []
    }

    handleFailed(errorMessage) {
        this.errorMessage = errorMessage;
    }

}

export default alt.createStore(UserStore, 'UserStore');
