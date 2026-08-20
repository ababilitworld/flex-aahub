// File: src/assets/js/repository/TreeRepository.js
export default class TreeRepository 
{
    async fetchTreeData() 
    {
        try 
        {            
            // Fallback to fetch if localized data not available
            if (window.flexMultilingualEbook && window.flexMultilingualEbook.dataUrl) 
            {
                const response = await fetch(window.flexMultilingualEbook.dataUrl);
                if (!response.ok) 
                {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return await response.json();
            }
            
            throw new Error('No data source available');
            
        } 
        catch (error) 
        {
            console.error('Error loading tree data:', error);
            throw error;
        }
    }
}