// File: Asset/Js/App/App.js
import EbookTreeFacade from "../Facade/EbookTreeFacade.js";

export default class App 
{
    static #instance;

    constructor() 
    {
        if (App.#instance) 
        {
            return App.#instance;
        }

        this.ebookTreeFacade = new EbookTreeFacade();

        App.#instance = this;
    }

    run(containerId) 
    {
        this.ebookTreeFacade.renderTree(containerId);
    }
}