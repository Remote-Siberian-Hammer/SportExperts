import {OptionEndpointQuery} from './query/OptionEndpointQuery';
import {API_URL} from "../common/route/api";
import {TOKEN} from "../common/fields";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function createOptionRequest(attributes, optionQuery = new OptionEndpointQuery())
{
    optionQuery.setUrl(`${API_URL}api/v1/option/store`);
    optionQuery.setHeaders();
    optionQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    optionQuery.setMethod(`${PROCEDURES.option.create}@${REQUEST_METHOD_DEFAULT}`);
    optionQuery.setParams(attributes);
    return await optionQuery.execute();
}

export async function updateOptionRequest(attributes, optionQuery = new OptionEndpointQuery())
{
    optionQuery.setUrl(`${API_URL}api/v1/option/update`);
    optionQuery.setHeaders();
    optionQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    optionQuery.setMethod(`${PROCEDURES.option.update}@${REQUEST_METHOD_DEFAULT}`);
    optionQuery.setParams(attributes);
    return await optionQuery.execute();
}

export async function getOptionRequest(attributes, optionQuery = new OptionEndpointQuery())
{
    optionQuery.setUrl(`${API_URL}api/v1/option/read`);
    optionQuery.setHeaders();
    optionQuery.setMethod(`${PROCEDURES.option.read}@${REQUEST_METHOD_DEFAULT}`);
    optionQuery.setParams(attributes);
    return await optionQuery.execute();
}

export async function deleteOptionRequest(attributes, optionQuery = new OptionEndpointQuery())
{
    optionQuery.setUrl(`${API_URL}api/v1/option/destroy`);
    optionQuery.setHeaders();
    optionQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`)
    optionQuery.setMethod(`${PROCEDURES.option.delete}@${REQUEST_METHOD_DEFAULT}`);
    optionQuery.setParams(attributes);
    return await optionQuery.execute();
}
