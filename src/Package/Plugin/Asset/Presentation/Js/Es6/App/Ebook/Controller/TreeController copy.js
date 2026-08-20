// File: src/assets/js/controller/TreeController.js
import TreeService from "../service/TreeService.js";

export default class TreeController {
    constructor() {
        this.service = new TreeService();
    }

    async render(containerId) {
        const root = await this.service.getTree();
        const container = document.getElementById(containerId);
        container.innerHTML = "";

        if (!root) {
            container.innerHTML = "<p>Error loading tree data.</p>";
            return;
        }

        container.appendChild(this.buildTree(root));
    }

    buildTree(node) {
        if (!node) return document.createElement('div');
    
        const wrapper = document.createElement('div');
    
        // Create button for this node
        const button = document.createElement('button');
        button.classList.add('accordion-button');
        button.textContent = this.extractTextFromHTML(node.title);
    
        // Create container for details or child nodes
        const content = document.createElement('div');
        content.classList.add('accordion-content');
        content.style.display = 'none';
    
        button.addEventListener('click', (event) => {
            this.toggleContent(event, node);
        });
    
        wrapper.appendChild(button);
        wrapper.appendChild(content);
    
        // Recursively render children (if any)
        if (node.children && node.children.length > 0) {
            node.children.forEach(childId => {
                // You'll need to get the actual child node by ID from your service
                // For now, we'll just create a placeholder
                const childNode = { id: childId, title: `Item ${childId}` };
                const childElement = this.buildTree(childNode);
                content.appendChild(childElement);
            });
        }
    
        return wrapper;
    }

    extractTextFromHTML(htmlString) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlString;
        return tempDiv.textContent || tempDiv.innerText || '';
    }

    toggleContent(event, item) {
        if (!item) return;
        
        const contentArea = document.getElementById("content");
    
        // Clear and append DOM elements instead of using innerHTML
        contentArea.innerHTML = "";
        const content = this.prepareContent(item);
        contentArea.appendChild(content);
    
        // Accordion behavior
        const accordionButton = event.target;
        const accordionContent = accordionButton.nextElementSibling;
    
        accordionButton.classList.toggle("open");
        accordionContent.style.display =
            accordionContent.style.display === "block" ? "none" : "block";
    }
    
    prepareContent(item) {
        if (!item) return document.createElement("div");
        
        const content = document.createElement("div");
    
        // Current item details
        const header = document.createElement("h3");
        header.textContent = `${item.parent || "Root"} . ${item.id}: ${this.extractTextFromHTML(item.title)}`;
    
        const details = document.createElement("div");
        details.classList.add("content-details");
        
        // Safely handle the details which might be HTML
        if (item.details) {
            details.innerHTML = item.details;//console.log(details);
        } else {
            details.innerHTML = "<p>No details available.</p>";
        }
    
        content.appendChild(header);
        content.appendChild(details);
    
        // Handle example content if it exists
        if (item.example) {
            const exampleHeader = document.createElement("h4");
            exampleHeader.textContent = "Example:";
            const exampleContent = document.createElement("div");
            exampleContent.classList.add("example-content");
            exampleContent.innerHTML = item.example;
            
            content.appendChild(exampleHeader);
            content.appendChild(exampleContent);
        }
    
        // Recursively add children
        if (item.children && item.children.length > 0) {
            const childWrapper = document.createElement("div");
            childWrapper.classList.add("child-content");
    
            item.children.forEach(childId => {
                // You'll need to get the actual child node by ID from your service
                // For now, we'll just create a placeholder
                const childNode = { id: childId, title: `Item ${childId}` };
                const childContent = this.prepareContent(childNode);
                childWrapper.appendChild(childContent);
            });
    
            content.appendChild(childWrapper);
        }
    
        return content;
    }
}