import {EventEndpointQuery} from './query/EventEndpointQuery';
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";
import {TOKEN} from "../common/fields";
import {API_URL} from "../common/route/api";

export async function getEventListRequest(eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/event/`);
    eventQuery.setHeaders();
    eventQuery.setMethod(`${PROCEDURES.event.list}@${REQUEST_METHOD_DEFAULT}`);
    return await eventQuery.execute();
}

export async function getEventRequest(attributes, eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/event/read`);
    eventQuery.setHeaders();
    eventQuery.setMethod(`${PROCEDURES.event.read}@${REQUEST_METHOD_DEFAULT}`);
    eventQuery.setParams(attributes);
    return await eventQuery.execute();
}

export async function createEventRequest(attributes, eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/event/store`);
    eventQuery.setHeaders();
    eventQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`)
    eventQuery.setMethod(`${PROCEDURES.event.create}@${REQUEST_METHOD_DEFAULT}`);
    eventQuery.setParams(attributes);
    return await eventQuery.execute();
}

export async function updateEventRequest(attributes, eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/event/update`);
    eventQuery.setHeaders();
    eventQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`)
    eventQuery.setMethod(`${PROCEDURES.event.update}@${REQUEST_METHOD_DEFAULT}`);
    eventQuery.setParams(attributes);
    return await eventQuery.execute();
}

export async function createEventOptionRequest(attributes, eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/option/store`);
    eventQuery.setHeaders();
    eventQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`)
    eventQuery.setMethod(`${PROCEDURES.option.create}@${REQUEST_METHOD_DEFAULT}`);
    eventQuery.setParams(attributes);
    return await eventQuery.execute();
}

export async function updateEventOptionRequest(attributes, eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/option/update`);
    eventQuery.setHeaders();
    eventQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`)
    eventQuery.setMethod(`${PROCEDURES.option.update}@${REQUEST_METHOD_DEFAULT}`);
    eventQuery.setParams(attributes);
    return await eventQuery.execute();
}
export async function getKeyEventRequest(attributes, eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/event/read`);
    eventQuery.setHeaders();
    eventQuery.setMethod(`${PROCEDURES.event.read}@${REQUEST_METHOD_DEFAULT}`);
    eventQuery.setParams(attributes);
    return await eventQuery.execute();
}

export  async function  statusEventUpdate(attributes, eventQuery = new EventEndpointQuery())
{
    eventQuery.setUrl(`${API_URL}api/v1/event/closed`);
    eventQuery.setHeaders();
    eventQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`)
    eventQuery.setMethod(`${PROCEDURES.event.closed}@${REQUEST_METHOD_DEFAULT}`);
    eventQuery.setParams(attributes);
    return await eventQuery.execute();
}
