// File: src/assets/js/model/BaseTreeNode.js
import TreeNodeModel from "./TreeNodeModel.js";

export default class BaseTreeNode extends TreeNodeModel {
    constructor(props) {
        super(props);
        this._visible = true;
    }

    toggleChildrenVisibility() {
        this._visible = !this._visible;
        return this._visible;
    }

    isVisible() {
        return this._visible;
    }
}
