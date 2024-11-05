import { TournamentEndpointQuery } from './query/TournamentEndpointQuery';
import {API_URL} from "../common/route/api";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function tournamentReadRequest(attributes, tournamentQuery = new TournamentEndpointQuery()) {
    tournamentQuery.setUrl(`${API_URL}api/v1/tournament/read`);
    tournamentQuery.setHeaders();
    tournamentQuery.setMethod(`${PROCEDURES.tournament.read}@${REQUEST_METHOD_DEFAULT}`);
    tournamentQuery.setParams(attributes);
    return await tournamentQuery.execute();
}
