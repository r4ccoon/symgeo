import alt from '../alt';
import RFetch from '../../common/RFetch';

class UserActions {
    getUsers(users) {
        return users;
    }

    fetchUsers(username) {
        return (dispatch) => {
            // we dispatch an event here so we can have "loading" state.
            dispatch();
            RFetch.get('user/search', {username: username})
                .then((users) => {
                    // we can access other actions within our action through `this.actions`
                    this.getUsers(users);
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

module.exports = alt.createActions(UserActions);