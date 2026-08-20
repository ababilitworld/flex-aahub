// File: src/assets/js/controller/TreeController.js
import TreeService from "../service/TreeService.js";

export default class TreeController {
    constructor() {
        this.service = new TreeService();
        this.treeModel = null;
    }

    async render(containerId) {
        this.treeModel = await this.service.getTree();
        const container = document.getElementById(containerId);
        container.innerHTML = "";

        if (!this.treeModel) {
            container.innerHTML = "<p>Error loading tree data.</p>";
            return;
        }

        // Get root nodes (excluding the actual root if it exists)
        const rootNodes = this.service.getRootNodes();

        rootNodes.forEach(rootNode => {
            container.appendChild(this.buildTree(rootNode));
        });
    }

    buildTree(node) {
        if (!node) return document.createElement('div');
    
        const wrapper = document.createElement('div');
        wrapper.classList.add('tree-node');
        wrapper.setAttribute('data-node-id', node.getId());
    
        // Create button for this node
        const button = document.createElement('button');
        button.classList.add('accordion-button');
        button.innerHTML = this.extractTextFromHTML(node.getTitle());
    
        // Create container for child nodes
        const content = document.createElement('div');
        content.classList.add('accordion-content');
        content.style.display = 'none';
    
        button.addEventListener('click', (event) => {
            this.toggleContent(event, node);
        });
    
        wrapper.appendChild(button);
        wrapper.appendChild(content);
    
        // Recursively render children (if any)
        const children = this.service.getChildrenNodes(node);
        if (children.length > 0) {
            children.forEach(childNode => {
                const childElement = this.buildTree(childNode);
                content.appendChild(childElement);
            });
        } else {
            // Add a class for leaf nodes (no children)
            wrapper.classList.add('leaf-node');
        }
    
        return wrapper;
    }

    extractTextFromHTML(htmlString) {
        if (!htmlString) return '';
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlString;
        return tempDiv.textContent || tempDiv.innerText || '';
    }

    toggleContent(event, item) {
        if (!item) return;
        
        const contentArea = document.getElementById("content");
        if (!contentArea) return;
    
        // Clear and append DOM elements
        contentArea.innerHTML = "";
        const content = this.prepareContent(item);
        contentArea.appendChild(content);
    
        // Accordion behavior
        const accordionButton = event.target;
        const accordionContent = accordionButton.nextElementSibling;
    
        accordionButton.classList.toggle("open");
        if (accordionContent.style.display === "block") {
            accordionContent.style.display = "none";
        } else {
            accordionContent.style.display = "block";
        }
        
        // Stop event propagation to prevent parent accordions from closing
        event.stopPropagation();
    }
    
    prepareContent(item) {
        if (!item) return document.createElement("div");
        
        const content = document.createElement("div");
        content.classList.add('content-container');
    
        // Current item details
        const header = document.createElement("h3");
        header.textContent = `${item.getId()}: ${this.extractTextFromHTML(item.getTitle())}`;
    
        const details = document.createElement("div");
        details.classList.add("content-details");
        
        // Safely handle the details which might be HTML
        if (item.getDetails()) {
            details.innerHTML = item.getDetails();
        } else {
            details.innerHTML = "<p>No details available.</p>";
        }
    
        content.appendChild(header);
        content.appendChild(details);
    
        // Handle example content if it exists (you might need to add this to your model)
        if (item.example) {
            const exampleHeader = document.createElement("h4");
            exampleHeader.textContent = "Example:";
            const exampleContent = document.createElement("div");
            exampleContent.classList.add("example-content");
            
            // Check if example is a URL (image) or HTML content
            if (typeof item.example === 'string' && item.example.startsWith('http')) {
                const img = document.createElement('img');
                img.src = item.example;
                img.alt = "Example image";
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                exampleContent.appendChild(img);
            } else if (item.example) {
                exampleContent.innerHTML = item.example;
            }
            
            content.appendChild(exampleHeader);
            content.appendChild(exampleContent);
        }
    
        // Recursively add children content (preview)
        const children = this.service.getChildrenNodes(item);
        if (children.length > 0) {
            const childWrapper = document.createElement("div");
            childWrapper.classList.add("child-preview");
            
            const childHeader = document.createElement("h4");
            childHeader.textContent = "Contains:";
            childWrapper.appendChild(childHeader);
            
            const childList = document.createElement("ul");
            children.forEach(childNode => {
                const listItem = document.createElement("li");
                listItem.textContent = this.extractTextFromHTML(childNode.getTitle());
                childList.appendChild(listItem);
            });
            
            childWrapper.appendChild(childList);
            content.appendChild(childWrapper);
        }
    
        return content;
    }

    // Method to find a node by ID
    findNodeById(id) {
        return this.service.getNodeById(id);
    }

    // Method to expand a specific node by ID
    expandNode(nodeId) {
        const node = this.findNodeById(nodeId);
        if (node) {
            const contentArea = document.getElementById("content");
            if (contentArea) {
                contentArea.innerHTML = "";
                const content = this.prepareContent(node);
                contentArea.appendChild(content);
            }
            
            // Also expand the accordion in the tree view
            const nodeElement = document.querySelector(`[data-node-id="${nodeId}"]`);
            if (nodeElement) {
                const button = nodeElement.querySelector('.accordion-button');
                const contentDiv = nodeElement.querySelector('.accordion-content');
                if (button && contentDiv) {
                    button.classList.add('open');
                    contentDiv.style.display = 'block';
                }
            }
        }
    }
}