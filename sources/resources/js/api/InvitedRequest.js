import {InviteEndpointQuery} from './query/InviteEndpointQuery';
import {API_URL} from "../common/route/api";
import {TOKEN} from "../common/fields";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function addNotificationUserInviteEventRequest(attributes, inviteQuery = new InviteEndpointQuery())
{
    inviteQuery.setUrl(`${API_URL}api/v1/invite/notification/`);
    inviteQuery.setHeaders();
    inviteQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    inviteQuery.setMethod(`${PROCEDURES.invites.notification}@${REQUEST_METHOD_DEFAULT}`);
    inviteQuery.setParams(attributes);
    return await inviteQuery.execute();
}

export async function createInvitedRequest(attributes, inviteQuery = new InviteEndpointQuery())
{
    inviteQuery.setUrl(`${API_URL}api/v1/invite/store`);
    inviteQuery.setHeaders();
    inviteQuery.setMethod(`${PROCEDURES.invites.create}@${REQUEST_METHOD_DEFAULT}`);
    inviteQuery.setParams(attributes);
    return await inviteQuery.execute();
}

export async function listInvitedRequest(attributes, inviteQuery = new InviteEndpointQuery())
{
    inviteQuery.setUrl(`${API_URL}api/v1/invite/`);
    inviteQuery.setHeaders();
    inviteQuery.setMethod(`${PROCEDURES.invites.list}@${REQUEST_METHOD_DEFAULT}`);
    inviteQuery.setParams(attributes);
    return await inviteQuery.execute();
}

export async function readUserParticipantInvitedRequest(attributes, inviteQuery = new InviteEndpointQuery())
{
    inviteQuery.setUrl(`${API_URL}api/v1/invite/read/participant`);
    inviteQuery.setHeaders();
    inviteQuery.setMethod(`${PROCEDURES.invites.readUserParticipant}@${REQUEST_METHOD_DEFAULT}`);
    inviteQuery.setParams(attributes);
    return await inviteQuery.execute();
}
