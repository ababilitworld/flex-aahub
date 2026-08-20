// File: src/assets/js/model/TreeNodeModel.js
import ITreeNode from "../Interface/ITreeNode.js";
import TreeNodeDTO from "../Dto/TreeNodeDTO.js";

export default class TreeNodeModel extends ITreeNode {
    constructor({ id, title, details, parent = null, children = [], example = null }) {
        super();
        this.id = id;
        this.title = title; // This is now an object {en: '', ar: '', bn: ''}
        this.details = details; // This is now an object {en: '', ar: '', bn: ''}
        this.parent = parent;
        this.children = children; // array of TreeNodeModel instances
        this.example = example; // This is now an object {en: '', ar: '', bn: ''}
    }

    getId() {
        return this.id;
    }

    getTitle(language = 'en') {
        return this.title && typeof this.title === 'object' ? this.title[language] || this.title.en || '' : this.title || '';
    }

    getDetails(language = 'en') {
        return this.details && typeof this.details === 'object' ? this.details[language] || this.details.en || '' : this.details || '';
    }

    getParent() {
        return this.parent;
    }

    getChildren() {
        return this.children;
    }

    getExample(language = 'en') {
        return this.example && typeof this.example === 'object' ? this.example[language] || this.example.en || '' : this.example || '';
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