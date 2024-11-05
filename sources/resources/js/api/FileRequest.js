import axios from "axios";
import {FileEndpointQuery} from './query/FileEndpointQuery';
import {API_URL} from "../common/route/api";
import {TOKEN} from "../common/fields";

export async function uploadFileRequest(formData, fileQuery = new FileEndpointQuery())
{
    try {
        return await axios.post(`${API_URL}api/v1/file/`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json',
                'Authorization': `Bearer ${window.$cookies.get(TOKEN)}`
            }
        })
    } catch (error) {
        throw error;
    }
}
