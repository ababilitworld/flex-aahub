// File: src/assets/js/model/BaseTreeNode.js
import ITreeNode from "../interface/ITreeNode.js";

export default class BaseTreeNode extends ITreeNode {
    constructor(dto) {
        super();
        this.id = dto.id;
        this.title = dto.title;
        this.details = dto.details;
        this.parent = dto.parent || null;
        this.children = dto.children || [];
    }

    getId() { return this.id; }
    getTitle() { return this.title; }
    getDetails() { return this.details; }
    getParent() { return this.parent; }
    getChildren() { return this.children; }
}
