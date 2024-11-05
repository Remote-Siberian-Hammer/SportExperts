import {UserEndpointQuery} from './query/UserEndpointQuery';
import {API_URL} from "../common/route/api";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";
import {TOKEN} from "../common/fields";

export async function registrationRequest(attributes, userQuery = new UserEndpointQuery())
{
    userQuery.setUrl(`${API_URL}api/v1/user/registration`);
    userQuery.setHeaders();
    userQuery.setMethod(`${PROCEDURES.users.registration}@${REQUEST_METHOD_DEFAULT}`);
    userQuery.setParams(attributes);
    return await userQuery.execute();
}

export async function getUser(attributes, userQuery = new UserEndpointQuery())
{
    userQuery.setUrl(`${API_URL}api/v1/user/read`);
    userQuery.setHeaders();
    userQuery.setMethod(`${PROCEDURES.users.read}@${REQUEST_METHOD_DEFAULT}`);
    userQuery.setParams(attributes);
    return await userQuery.execute();
}

export async function updateUserRequest(attributes, userQuery = new UserEndpointQuery())
{
    userQuery.setUrl(`${API_URL}api/v1/user/update`);
    userQuery.setHeaders();
    userQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    userQuery.setMethod(`${PROCEDURES.users.update}@${REQUEST_METHOD_DEFAULT}`);
    userQuery.setParams(attributes);
    return await userQuery.execute();
}

export async function resetToPasswordRequest(attributes, userQuery = new UserEndpointQuery())
{
    userQuery.setUrl(`${API_URL}api/v1/user/reset/`);
    userQuery.setHeaders();
    userQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    userQuery.setMethod(`${PROCEDURES.users.reset}@${REQUEST_METHOD_DEFAULT}`);
    userQuery.setParams(attributes);
    return await userQuery.execute();

}

export async function getInvitedOwnerRequest(attributes, userQuery = new UserEndpointQuery())
{
    userQuery.setUrl(`${API_URL}api/v1/invite/`);
    userQuery.setHeaders();
    userQuery.setMethod(`${PROCEDURES.invites.list}@${REQUEST_METHOD_DEFAULT}`);
    userQuery.setParams(attributes);
    return await userQuery.execute();
}
