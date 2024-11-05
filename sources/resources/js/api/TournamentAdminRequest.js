import {TournamentAdminEndpointQuery} from './query/TournamentAdminEndpointQuery';
import {API_URL} from "../common/route/api";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function getTournamentAdminRequest(attributes, tournamentAdminQuery = new TournamentAdminEndpointQuery()) {
    tournamentAdminQuery.setUrl(`${API_URL}api/v1/tournament/admin`);
    tournamentAdminQuery.setHeaders();
    //tournamentAdminQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    tournamentAdminQuery.setMethod(`${PROCEDURES.tournament.admin.list}@${REQUEST_METHOD_DEFAULT}`);
    tournamentAdminQuery.setParams(attributes);
    return await tournamentAdminQuery.execute();
}
