class RUrl {
    constructor() {
        this.baseUrl = "/api/v1/";
    }

    resource(url, searchParams) {
        var finalUrl = this.baseUrl + url;

        if (searchParams) {
            finalUrl = this.baseUrl + url + '?' + this.populateParams(searchParams);
        }

        return finalUrl;
    }

    populateParams(searchParams) {
        var eq = [];
        for (var i in searchParams) {
            eq.push(i + '=' + searchParams[i])
        }
        var ret = eq.join('&');
        return ret;
    }
}

const url = new RUrl();
export default url;