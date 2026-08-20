// File: src/assets/js/facade/EbookTreeFacade.js
import TreeManager from "../Manager/TreeManager.js";

export default class EbookTreeFacade 
{
    renderTree(containerId) 
    {
        TreeManager.getInstance().init(containerId);
    }
}