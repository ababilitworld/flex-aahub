// File: Js/Repository/Contract/Repository.js
/**
 * Interface for  Repository
 * @interface
 */
export class Repository 
{
    async fetchData(options = {}) 
    {
        throw new Error('Method not implemented: fetchData');
    }

    async saveData(data, options = {}) 
    {
        throw new Error('Method not implemented: saveData');
    }

    async clearData(options = {}) 
    {
        throw new Error('Method not implemented: clearData');
    }

    hasCachedData() 
    {
        throw new Error('Method not implemented: hasCachedData');
    }

    getRepositoryInfo() 
    {
        throw new Error('Method not implemented: getRepositoryInfo');
    }
}