import {TournamentHistoryEndpointQuery} from "./query/TournamentHistoryEndpointQuery";
import {API_URL} from "../common/route/api";
import {TOKEN} from "../common/fields";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function getTournamentHistoryRequest(attributes, tournamentHistoryQuery = new TournamentHistoryEndpointQuery()) {
    tournamentHistoryQuery.setUrl(`${API_URL}api/v1/tournament/history`);
    tournamentHistoryQuery.setHeaders();
    tournamentHistoryQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    tournamentHistoryQuery.setMethod(`${PROCEDURES.tournament.history.read}@${REQUEST_METHOD_DEFAULT}`);
    tournamentHistoryQuery.setParams(attributes);
    return await tournamentHistoryQuery.execute();
}
