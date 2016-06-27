import React from "react";

export default class SearchBox extends React.Component {
    render() {
        return (
            <div className="input-group">
                <input type="text" className="form-control"/>
                <div className="input-group-btn">
                    <button type="button" className="btn btn-default">
                        <i className="fa fa-search"></i>
                    </button>
                </div>
            </div>
        )
    }
}