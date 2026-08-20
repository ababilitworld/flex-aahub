// File: src/assets/js/repository/TreeRepository.js
export default class TreeRepository 
{
    async fetchTreeData() 
    {
        try 
        {
            // Check if data is already available via wp_localize_script
            if (window.flexMultilingualEbook && window.flexMultilingualEbook.data) 
            {
                console.log('Using localized data:', window.flexMultilingualEbook.data);
                return JSON.parse(window.flexMultilingualEbook.data);
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