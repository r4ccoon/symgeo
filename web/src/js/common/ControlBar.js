import React from "react";

export default class ControlBar extends React.Component {
    render() {
        return (
            <div>
                <div className="btn-group">
                    {this.getComponent()}
                </div>
                {this.getComponent('modal')}
            </div>
        )
    }

    getComponent(key) {
        if (!key) {
            return this.props.children.filter((comp) => {
                return comp.key == null;
            });
        }

        return this.props.children.filter((comp) => {
            return comp.key === key;
        });
    }
}