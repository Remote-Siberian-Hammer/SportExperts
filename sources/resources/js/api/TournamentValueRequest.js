import {TournamentValueEndpointQuery} from './query/TournamentValueEndpointQuery';
import {API_URL} from "../common/route/api";
import {TOKEN} from "../common/fields";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function tournamentValueCreateRequest(attributes, tournamentValueQuery = new TournamentValueEndpointQuery()) {
    // Для записи из списка спортсменов
    tournamentValueQuery.setUrl(`${API_URL}api/v1/tournament/value/store`);
    tournamentValueQuery.setHeaders();
    tournamentValueQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    tournamentValueQuery.setMethod(`${PROCEDURES.tournament.value.create}@${REQUEST_METHOD_DEFAULT}`);
    tournamentValueQuery.setParams(attributes);
    return await tournamentValueQuery.execute();
}

export async function getFreeParticipantsRequest(attributes, tournamentValueQuery = new TournamentValueEndpointQuery()) {
    tournamentValueQuery.setUrl(`${API_URL}api/v1/tournament/value/filter`);
    tournamentValueQuery.setHeaders();
    tournamentValueQuery.isAuth(`Bearer ${window.$cookies.get(TOKEN)}`);
    tournamentValueQuery.setMethod(`${PROCEDURES.tournament.value.filter.free}@${REQUEST_METHOD_DEFAULT}`);
    tournamentValueQuery.setParams(attributes);
    return await tournamentValueQuery.execute();
}
