
import {AuthEndpointQuery} from './query/AuthEndpointQuery';
import {API_URL} from "../common/route/api";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";
import {PROCEDURES} from "../common/procedures";

export async function authorizationRequest(attributes, authQuery = new AuthEndpointQuery())
{
    authQuery.setUrl(`${API_URL}api/v1/user/auth/`);
    authQuery.setHeaders();
    authQuery.setMethod(`${PROCEDURES.auth.login}@${REQUEST_METHOD_DEFAULT}`)
    authQuery.setParams(attributes)
    return await authQuery.execute();
}
