// File: Js/Repository/Concrete/Memory/Repository.js
import  { Repository as BaseRepository } from '../Base/Repository.js';

export default class Repository extends BaseRepository 
{
    constructor(options = {}) 
    {
        super();
        this.cache = new Map();
        this.cacheTimeout = options.cacheTimeout || 5 * 60 * 1000; // 5 minutes
        this.STORAGE_KEY = 'tree_data';
    }

    async _fetchFreshData(options = {}) 
    {
        try 
        {
            // Strategy 1: Check global variable
            if (window.flexMultilingualEbook?.treeData) 
            {
                return window.flexMultilingualEbook.treeData;
            }

            // Strategy 2: Fetch from dataUrl
            if (window.flexMultilingualEbook?.dataUrl) 
            {
                return await this._fetchFromUrl(window.flexMultilingualEbook.dataUrl);
            }

            // Strategy 3: Default URL
            return await this._fetchFromUrl("./data/ebook.json");
        } 
        catch (error) 
        {
            this._handleError('_fetchFreshData', error, { options });
        }
    }

    async _fetchFromUrl(url) 
    {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: Failed to fetch from ${url}`);
        }
        return await response.json();
    }

    async _getFromCache() 
    {
        const cached = this.cache.get(this.STORAGE_KEY);
        if (!cached) return null;

        const { data, timestamp } = cached;
        const isExpired = Date.now() - timestamp > this.cacheTimeout;

        return isExpired ? null : data;
    }

    async _cacheData(data) 
    {
        this.cache.set(this.STORAGE_KEY, {
            data,
            timestamp: Date.now()
        });
    }

    async saveData(data, options = {}) 
    {
        try 
        {
            await this._cacheData(data);
            return true;
        } 
        catch (error) 
        {
            this._handleError('saveData', error, { data, options });
        }
    }

    async clearData(options = {}) 
    {
        this.cache.delete(this.STORAGE_KEY);
    }

    hasCachedData() 
    {
        return this.cache.has(this.STORAGE_KEY);
    }

    getRepositoryInfo() {
        return {
            type: 'Memory',
            hasCachedData: this.hasCachedData(),
            cacheSize: this.cache.size,
            cacheTimeout: this.cacheTimeout
        };
    }
}