import React from "react";

export default class AddButton extends React.Component {  
    render() {
        return (
            <button type="button" className="btn btn-default" onClick={this.props.onClick}>
                <i className="fa fa-plus"></i>
            </button>
        )
    }
}