import alt from '../alt';
import url from '../../common/RUrl';

class UserActions {
    getUsers(users) {
        return users;
    }

    fetchUsers(username) {
        return (dispatch) => {
            // we dispatch an event here so we can have "loading" state.
            dispatch();
            fetch(url.resource('user', {username: username}))
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