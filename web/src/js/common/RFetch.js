import url from './RUrl';

const RFetch = {
    get: function (path, params) {
        return fetch(url.resource(path, params), {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        });
    },

    post: function (path, paramsURL, paramsBody) {
        return fetch(url.resource(path, paramsURL), {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(paramsBody),
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        })
    }
};

export default RFetch;