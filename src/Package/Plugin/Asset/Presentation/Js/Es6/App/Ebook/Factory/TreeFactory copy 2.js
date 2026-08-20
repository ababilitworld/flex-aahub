// File: src/assets/js/factory/TreeFactory.js
import TreeNodeDTO from "../dto/TreeNodeDTO.js";
import TreeNodeModel from "../model/TreeNodeModel.js";

export default class TreeFactory {
    constructor() {
        this.flatMap = new Map();
    }

    createTree(dtoList) {
        if (!Array.isArray(dtoList)) {
            console.error("TreeFactory expected an array, got:", dtoList);
            return null;
        }

        dtoList.forEach(item => {
            const dto = new TreeNodeDTO(item);
            this.flatMap.set(dto.id, dto);
        });

        const rootDto = this.flatMap.get("0");
        return this._buildModelFromDto(rootDto);
    }

    _buildModelFromDto(dto) {
        if (!dto) return null;

        const children = dto.children.map(childId => {
            const childDto = this.flatMap.get(childId);
            return this._buildModelFromDto(childDto);
        }).filter(Boolean); // Filter out any null children

        return new TreeNodeModel({
            id: dto.id,
            title: dto.title,
            details: dto.details,
            parent: dto.parent,
            children: children,
            example: dto.example // Pass the example property
        });
    }
}