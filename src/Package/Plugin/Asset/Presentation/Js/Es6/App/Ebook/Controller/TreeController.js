// File: src/assets/js/controller/TreeController.js
import TreeService from "../Service/TreeService.js";

export default class TreeController {
    constructor() {
        this.service = new TreeService();
        this.treeModel = null;
        this.currentLanguage = 'en'; // Default language
    }

    async render(containerId) {
        this.treeModel = await this.service.getTree();
        const container = document.getElementById(containerId);
        container.innerHTML = "";

        if (!this.treeModel) {
            container.innerHTML = "<p>Error loading tree data.</p>";
            return;
        }

        // Create language selector
        this.createLanguageSelector(container);

        // Get root nodes (excluding the actual root if it exists)
        const rootNodes = this.service.getRootNodes();

        const treeContainer = document.createElement('div');
        treeContainer.id = 'tree-content';
        rootNodes.forEach(rootNode => {
            treeContainer.appendChild(this.buildTree(rootNode));
        });
        
        container.appendChild(treeContainer);
    }

    createLanguageSelector(container) {
        const selectorContainer = document.createElement('div');
        selectorContainer.classList.add('language-selector');
        
        const languages = [
            { code: 'en', name: 'English', flag: '🇺🇸' },
            { code: 'ar', name: 'العربية', flag: '🇸🇦' },
            { code: 'bn', name: 'বাংলা', flag: '🇧🇩' },
            { code: 'com', name: 'Combined', flag: '🇧🇩' }
        ];
        
        languages.forEach(lang => {
            const button = document.createElement('button');
            button.innerHTML = `${lang.flag} ${lang.name}`;
            button.classList.add('lang-btn');
            if (lang.code === this.currentLanguage) {
                button.classList.add('active');
            }
            
            button.addEventListener('click', () => {
                this.setLanguage(lang.code);
            });
            
            selectorContainer.appendChild(button);
        });
        
        container.appendChild(selectorContainer);
    }

    setLanguage(language) {
        this.currentLanguage = language;
        this.refreshContent();
    }

    refreshContent() {
        // Refresh tree content
        const treeContainer = document.getElementById('tree-content');
        if (treeContainer) {
            treeContainer.innerHTML = '';
            const rootNodes = this.service.getRootNodes();
            rootNodes.forEach(rootNode => {
                treeContainer.appendChild(this.buildTree(rootNode));
            });
        }
        
        // Refresh main content if something is selected
        const contentArea = document.getElementById("content");
        if (contentArea && contentArea.hasChildNodes()) {
            // Try to find the currently selected node and refresh its content
            const selectedNodeId = contentArea.getAttribute('data-selected-node');
            if (selectedNodeId) {
                const node = this.service.getNodeById(selectedNodeId);
                if (node) {
                    contentArea.innerHTML = "";
                    const content = this.prepareContent(node);
                    contentArea.appendChild(content);
                }
            }
        }
    }

    buildTree(node) {
        if (!node) return document.createElement('div');
    
        const wrapper = document.createElement('div');
        wrapper.classList.add('tree-node');
        wrapper.setAttribute('data-node-id', node.getId());
    
        // Create button for this node
        const button = document.createElement('button');
        button.classList.add('accordion-button');
        button.innerHTML = this.extractTextFromHTML(node.getTitle(this.currentLanguage));
    
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
        contentArea.setAttribute('data-selected-node', item.getId());
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
        header.textContent = `${item.getId()}: ${this.extractTextFromHTML(item.getTitle(this.currentLanguage))}`;
    
        const details = document.createElement("div");
        details.classList.add("content-details");
        
        // Safely handle the details which might be HTML
        const detailsContent = item.getDetails(this.currentLanguage);
        if (detailsContent) {
            details.innerHTML = detailsContent;
        } else {
            details.innerHTML = "<p>No details available.</p>";
        }
    
        content.appendChild(header);
        content.appendChild(details);
    
        // Handle example content if it exists
        const exampleContent = item.getExample(this.currentLanguage);
        if (exampleContent) {
            const exampleHeader = document.createElement("h4");
            exampleHeader.textContent = "Example:";
            const exampleDiv = document.createElement("div");
            exampleDiv.classList.add("example-content");
            
            // Check if example is a URL (image) or HTML content
            if (typeof exampleContent === 'string' && exampleContent.startsWith('http')) {
                const img = document.createElement('img');
                img.src = exampleContent;
                img.alt = "Example image";
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                exampleDiv.appendChild(img);
            } else if (exampleContent) {
                exampleDiv.innerHTML = exampleContent;
            }
            
            content.appendChild(exampleHeader);
            content.appendChild(exampleDiv);
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
                listItem.textContent = this.extractTextFromHTML(childNode.getTitle(this.currentLanguage));
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
                contentArea.setAttribute('data-selected-node', nodeId);
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