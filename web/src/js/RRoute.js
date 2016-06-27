class Route {
    constructor() {
        this.parsed = window.location;
    }

    path(pattern, controllerInstance) {
        const path = this.parsed.pathname;
        if (path && path.match(pattern)) {
            return controllerInstance;
        } else {
            return this;
        }
    }

    run() {
        console.log('no route matched');
    }
}

function RRoute(url) {
    return new Route(url);
}

export default RRoute;