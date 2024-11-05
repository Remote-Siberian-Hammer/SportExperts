import {ArchiveEndpointQuery} from './query/ArchiveEndpointQuery';
import {PROCEDURES} from "../common/procedures";
import {API_URL} from "../common/route/api";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function createArchiveRequest(attributes, archiveQuery  = new ArchiveEndpointQuery())
{
    archiveQuery.setUrl(`${API_URL}api/v1/event/archive/store`);
    archiveQuery.setHeaders();
    archiveQuery.setMethod(`${PROCEDURES.archive.create}@${REQUEST_METHOD_DEFAULT}`);
    archiveQuery.setParams(attributes);
    return await archiveQuery.execute();
}

export async function removeArchiveRequest(attributes, archiveQuery  = new ArchiveEndpointQuery())
{
    archiveQuery.setUrl(`${API_URL}api/v1/event/archive/destroy`);
    archiveQuery.setHeaders();
    archiveQuery.setMethod(`${PROCEDURES.archive.delete}@${REQUEST_METHOD_DEFAULT}`);
    archiveQuery.setParams(attributes);
    return await archiveQuery.execute();
}
