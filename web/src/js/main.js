import '../../../node_modules/react-bootstrap-table/css/react-bootstrap-table-all.min.css';
import '../css/app.css';

import CompanyController from "./company";
import RRoute from "./RRoute";

RRoute(window.url)
    .path(/^\/panel\/company/, new CompanyController())
    .run();
