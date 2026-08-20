// File: Js/Repository/Abstract/BaseRepository.js
import { Repository as ContractRepository } from '../Contract/Repository.js';

/**
 * Abstract base repository implementing common functionality
 * @abstract
 */
export default class BaseRepository extends ContractRepository 
{
    constructor() 
    {
        super();
        if (this.constructor === BaseRepository) 
        {
            throw new Error('Cannot instantiate abstract class');
        }
    }

    // Template method pattern - defines algorithm skeleton
    async fetchData(options = {}) 
    {
        const {
            forceRefresh = false,
            useCache = true,
            validateSchema = true
        } = options;

        // Step 1: Check cache if not forcing refresh
        if (useCache && !forceRefresh) 
        {
            const cached = await this._getFromCache();
            if (cached && this._validateData(cached, validateSchema)) 
            {
                return cached;
            }
        }

        // Step 2: Fetch fresh data (implemented by subclasses)
        const freshData = await this._fetchFreshData(options);

        // Step 3: Validate data
        if (!this._validateData(freshData, validateSchema)) 
        {
            throw new Error('Data validation failed');
        }

        // Step 4: Cache the data
        await this._cacheData(freshData);

        return freshData;
    }

    // Common validation (can be overridden by subclasses)
    _validateData(data, shouldValidate = true) 
    {
        if (!shouldValidate) return true;
        
        return data !== null && 
               data !== undefined && 
               typeof data === 'object';
    }

    // Common error handling
    _handleError(methodName, error, context = {}) 
    {
        const errorContext = {
            repository: this.constructor.name,
            method: methodName,
            timestamp: new Date().toISOString(),
            ...context
        };
        
        console.error(`Repository error in ${methodName}:`, error, errorContext);
        throw error;
    }
}