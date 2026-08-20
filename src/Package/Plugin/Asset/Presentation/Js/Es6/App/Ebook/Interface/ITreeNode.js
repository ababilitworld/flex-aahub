// File: src/assets/js/interface/ITreeNode.js
export default class ITreeNode {
    constructor() {
        if (this.constructor === ITreeNode) {
            throw new Error("Cannot instantiate interface directly.");
        }
    }

    getId() {
        throw new Error("Must implement getId()");
    }

    getTitle() {
        throw new Error("Must implement getTitle()");
    }

    getDetails() {
        throw new Error("Must implement getDetails()");
    }

    getParent() {
        throw new Error("Must implement getParent()");
    }

    getChildren() {
        throw new Error("Must implement getChildren()");
    }

    toDTO() {
        throw new Error("Must implement toDTO()");
    }
}
