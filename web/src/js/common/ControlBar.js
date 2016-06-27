import React from "react";

export default class ControlBar extends React.Component {
    render() {
        return (
            <div className="btn-group">
                {this.props.children}
            </div>
        )
    }
}