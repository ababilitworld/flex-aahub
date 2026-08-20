// File: src/assets/js/singleton/TreeManager.js
import TreeController from "../Controller/TreeController.js";

export default class TreeManager 
{
    static #instance;

    constructor() 
    {
        if (TreeManager.#instance) 
        {
            return TreeManager.#instance;
        }

        this.treeController = new TreeController();

        TreeManager.#instance = this;
    }

    static getInstance() 
    {
        if (!TreeManager.#instance) 
        {
            TreeManager.#instance = new TreeManager();
        }

        return TreeManager.#instance;
    }

    init(containerId) 
    {
        this.treeController.render(containerId);
    }
}