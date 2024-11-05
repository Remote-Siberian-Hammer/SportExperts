import { ParticipantEndpointQuery } from './query/ParticipantEndpointQuery';
import {API_URL} from "../common/route/api";
import {PROCEDURES} from "../common/procedures";
import {REQUEST_METHOD_DEFAULT} from "../common/rpc";

export async function recordUserToEventRequest(attributes, participantQuery = new ParticipantEndpointQuery()) {
    // Для записи из списка спортсменов
    participantQuery.setUrl(`${API_URL}api/v1/participant/store/`);
    participantQuery.setHeaders();
    participantQuery.setMethod(`${PROCEDURES.participant.create}@${REQUEST_METHOD_DEFAULT}`);
    participantQuery.setParams(attributes);
    return await participantQuery.execute();
}

export async function eventRecordRequest(attributes,  participantQuery = new ParticipantEndpointQuery()) {
    participantQuery.setUrl(`${API_URL}api/v1/participant/store/`);
    participantQuery.setHeaders();
    participantQuery.setMethod(`${PROCEDURES.participant.create}@${REQUEST_METHOD_DEFAULT}`);
    participantQuery.setParams(attributes);
    return await participantQuery.execute();
}

export async function participantUserRemoveAdditionallyRequest(attributes,  participantQuery = new ParticipantEndpointQuery()) {
    participantQuery.setUrl(`${API_URL}api/v1/participant/additionally/drop`);
    participantQuery.setHeaders();
    participantQuery.setMethod(`${PROCEDURES.participant.additionally.drop}@${REQUEST_METHOD_DEFAULT}`);
    participantQuery.setParams(attributes);
    return await participantQuery.execute();
}

export async function participantUserReplaceAdditionallyRequest(attributes,  participantQuery = new ParticipantEndpointQuery()) {
    participantQuery.setUrl(`${API_URL}api/v1/participant/additionally/replace`);
    participantQuery.setHeaders();
    participantQuery.setMethod(`${PROCEDURES.participant.additionally.replace}@${REQUEST_METHOD_DEFAULT}`);
    participantQuery.setParams(attributes);
    return await participantQuery.execute();
}

export async function participantUserSkipAdditionallyRequest(attributes,  participantQuery = new ParticipantEndpointQuery()) {
    participantQuery.setUrl(`${API_URL}api/v1/participant/additionally/skip`);
    participantQuery.setHeaders();
    participantQuery.setMethod(`${PROCEDURES.participant.additionally.skip}@${REQUEST_METHOD_DEFAULT}`);
    participantQuery.setParams(attributes);
    return await participantQuery.execute();
}

export async function participantSearchAthleteRequest(attributes,  participantQuery = new ParticipantEndpointQuery()) {
    participantQuery.setUrl(`${API_URL}api/v1/participant/search/`);
    participantQuery.setHeaders();
    participantQuery.setMethod(`${PROCEDURES.participant.search}@${REQUEST_METHOD_DEFAULT}`);
    participantQuery.setParams(attributes);
    return await participantQuery.execute();
}
