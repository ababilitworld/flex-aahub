// File: src/assets/js/factory/TreeFactory.js
import BaseTreeNode from "../base/BaseTreeNode.js"; // use correct path

export default class TreeFactory {
    constructor(flatDtoList = []) {
        this.flatMap = new Map();
        flatDtoList.forEach(dto => this.flatMap.set(dto.id, dto));
    }

    createTree(rootId) {
        const rootDto = this.flatMap.get(rootId);
        if (!rootDto) return null;

        const children = Object.values(rootDto.children || {}).map(childId => this.createTree(childId)).filter(child => child !== null);
        const node = new BaseTreeNode({ ...rootDto, children });
        return node;
    }
}
