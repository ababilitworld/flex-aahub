// File: src/assets/js/model/TreeNodeModel.js
import ITreeNode from "../interface/ITreeNode.js";
import TreeNodeDTO from "../dto/TreeNodeDTO.js";

export default class TreeNodeModel extends ITreeNode {
    constructor({ id, title, details, parent = null, children = [], example = null }) {
        super();
        this.id = id;
        this.title = title;
        this.details = details;
        this.parent = parent;
        this.children = children; // array of TreeNodeModel instances
        this.example = example; // Add example property
    }

    getId() {
        return this.id;
    }

    getTitle() {
        return this.title;
    }

    getDetails() {
        return this.details;
    }

    getParent() {
        return this.parent;
    }

    getChildren() {
        return this.children;
    }

    getExample() {
        return this.example;
    }

    toDTO() {
        return new TreeNodeDTO({
            id: this.id,
            title: this.title,
            details: this.details,
            parent: this.parent,
            children: this.children.map(child => child.getId()),
            example: this.example
        });
    }
}