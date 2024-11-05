import {LogEndpointQuery} from "./query/LogEndpointQuery";
import {API_URL} from "../common/route/api";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function createLogOptionRequest(attributes, logQuery = new LogEndpointQuery())
{
    logQuery.setUrl(`${API_URL}api/v1/log/store`);
    logQuery.setHeaders();
    logQuery.setMethod(`${PROCEDURES.log.create}@${REQUEST_METHOD_DEFAULT}`);
    logQuery.setParams(attributes);
    return await logQuery.execute();
}

export async function listLogOptionRequest(logQuery = new LogEndpointQuery())
{
    logQuery.setUrl(`${API_URL}api/v1/log/`);
    logQuery.setHeaders();
    logQuery.setMethod(`${PROCEDURES.log.list}@${REQUEST_METHOD_DEFAULT}`);
    return await logQuery.execute();
}
