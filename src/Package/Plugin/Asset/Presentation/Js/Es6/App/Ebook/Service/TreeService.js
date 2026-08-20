// File: Js/Service/TreeService.js
import TreeRepository from "../Repository/Localization/TreeRepository.js";
import TreeFactory from "../Factory/TreeFactory.js";

export default class TreeService {
    constructor() {
        this.repository = new TreeRepository();
        this.factory = new TreeFactory();
        this.treeModel = null;
        this.nodeMap = new Map(); // For quick node lookup by ID
    }

    async getTree() {
        try {
            const raw = await this.repository.fetchTreeData(); //console.log(raw);exit;
            const dtoList = Array.isArray(raw) ? raw : raw.data || raw.nodes || raw.tree || [];
            this.treeModel = this.factory.createTree(dtoList);
            
            // Build node map for quick lookup
            this.buildNodeMap(this.treeModel);
            return this.treeModel;
        } 
        catch (error) 
        {
            console.error('Error loading tree data:', error);
            return null;
        }
    }

    buildNodeMap(node) {
        if (!node) return;
        
        this.nodeMap.set(node.getId(), node);
        
        const children = node.getChildren();
        if (children && children.length > 0) {
            children.forEach(child => {
                this.buildNodeMap(child);
            });
        }
    }

    getNodeById(id) {
        return this.nodeMap.get(id);
    }

    getChildrenNodes(parentNode) {
        if (!parentNode) return [];
        return parentNode.getChildren() || [];
    }

    // Get root nodes (nodes with parent "0" or similar)
    getRootNodes() {
        if (!this.treeModel) return [];
        
        // If the tree model itself is the root (id: "0"), return its children
        if (this.treeModel.getId() === "0") {
            return this.treeModel.getChildren();
        }
        
        // Otherwise, return the tree model itself as the only root
        return [this.treeModel];
    }

    // Convert the tree to a flat array for compatibility
    toFlatArray() {
        const result = [];
        
        const traverse = (node) => {
            result.push({
                id: node.getId(),
                title: node.getTitle(),
                details: node.getDetails(),
                parent: node.getParent(),
                children: node.getChildren().map(child => child.getId()),
                example: node.example // Assuming you might add this to your model
            });
            
            node.getChildren().forEach(child => {
                traverse(child);
            });
        };
        
        if (this.treeModel) {
            traverse(this.treeModel);
        }
        
        return result;
    }
}