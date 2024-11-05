import {FilterEndpointQuery} from './query/FilterEndpointQuery';
import {API_URL} from "../common/route/api";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function getRecordToEventsRequest(
    attributes,
    mode='after',
    limit=9,
    filterQuery = new FilterEndpointQuery()
)
{
    filterQuery.setUrl(`${API_URL}api/v1/event/filter/participant/to/events`);
    filterQuery.setHeaders();
    filterQuery.setMethod(`${PROCEDURES.filter.userRecord}@${REQUEST_METHOD_DEFAULT}`);
    filterQuery.setParams({
        filter: attributes,
        mode: mode,
        limit: limit
    });
    return await filterQuery.execute();
}

export async function getEventOwnerRequest(attributes, filterQuery = new FilterEndpointQuery())
{
    filterQuery.setUrl(`${API_URL}api/v1/event/filter/my/events`);
    filterQuery.setHeaders();
    filterQuery.setMethod(`${PROCEDURES.filter.ownerEventsList}@${REQUEST_METHOD_DEFAULT}`);
    filterQuery.setParams(attributes);
    return await filterQuery.execute();
}

export async function getEventParticipantRequest(
    attributes,
    limit=9,
    filterQuery = new FilterEndpointQuery()
)
{
    filterQuery.setUrl(`${API_URL}api/v1/participant/filter/events/my/participants`);
    filterQuery.setHeaders();
    filterQuery.setMethod(`${PROCEDURES.filter.ownerParticipantList}@${REQUEST_METHOD_DEFAULT}`);
    filterQuery.setParams({
        filter: attributes,
        limit: limit
    });
    return await filterQuery.execute();
}

export async function getParticipantsToEventRequest(attributes, filterQuery = new FilterEndpointQuery())
{
    filterQuery.setUrl(`${API_URL}api/v1/participant/filter/events/in/users`);
    filterQuery.setHeaders();
    filterQuery.setMethod(`${PROCEDURES.filter.participantUsers}@${REQUEST_METHOD_DEFAULT}`);
    filterQuery.setParams(attributes);
    return await filterQuery.execute();
}
