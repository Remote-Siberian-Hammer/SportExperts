<?php

const PROCEDURE_AUTH_BY_EMAIL = 'AuthByEmailProcedure';
const PROCEDURE_LOGOUT = 'LogoutProcedure';
const PROCEDURE_NOTIFICATION = 'NotificationProcedure';
const PROCEDURE_LOG_STORE = 'LogStoreProcedure';


//// EVENT PROCEDURE
const PROCEDURE_EVENT_LIST = 'EventListProcedure';
const PROCEDURE_EVENT_STORE = 'EventStoreProcedure';
const PROCEDURE_EVENT_READ = 'EventReadProcedure';
const PROCEDURE_EVENT_UPDATE = 'EventUpdateProcedure';
const PROCEDURE_EVENT_DESTROY = 'EventDestroyProcedure';
const PROCEDURE_EVENT_CLOSED = 'EventClosedProcedure';
//// EVENTS ARCHIVE PROCEDURE
const PROCEDURE_EVENT_ARCHIVE_STORE = 'ArchiveStoreProcedure';
const PROCEDURE_EVENT_ARCHIVE_DESTROY = 'ArchiveDestroyProcedure';
//// EVENTS FILTER PROCEDURE
const PROCEDURE_EVENT_FILTER_DATE = 'EventDateFilterProcedure';
const PROCEDURE_EVENT_FILTER_EVENT_OWNER = 'EventOwnerFilterProcedure';

//// INVITE PROCEDURE
const PROCEDURE_INVITE_LIST = 'InvitedListProcedure';
const PROCEDURE_INVITE_STORE = 'InvitedStoreProcedure';
const PROCEDURE_INVITE_READ = 'InvitedReadProcedure';
const PROCEDURE_INVITE_READ_USER = 'InvitedReadUserParticipantProcedure';

//// OPTION PROCEDURE
const PROCEDURE_OPTION_LIST = 'OptionListProcedure';
const PROCEDURE_OPTION_STORE = 'OptionStoreProcedure';
const PROCEDURE_OPTION_READ = 'OptionReadProcedure';
const PROCEDURE_OPTION_UPDATE = 'OptionUpdateProcedure';
const PROCEDURE_OPTION_DESTROY = 'OptionDestroyProcedure';

//// PARTICIPANT PROCEDURE
const PROCEDURE_PARTICIPANT_LIST = 'ParticipantListProcedure';
const PROCEDURE_PARTICIPANT_STORE = 'ParticipantStoreProcedure';
const PROCEDURE_PARTICIPANT_READ = 'ParticipantReadProcedure';
const PROCEDURE_PARTICIPANT_UPDATE = 'ParticipantUpdateProcedure';
const PROCEDURE_PARTICIPANT_DESTROY = 'ParticipantDestroyProcedure';
//// PARTICIPANTS ADDITIONALLY PROCEDURE
const PROCEDURE_PARTICIPANT_DISQUALIFICATION = 'ParticipantDisqualificationProcedure';
const PROCEDURE_PARTICIPANT_SKIPPED = 'ParticipantSkippedProcedure';
const PROCEDURE_PARTICIPANT_REPLACEMENT = 'ParticipantКReplacementProcedure';
//// PARTICIPANTS FILTER PROCEDURE
const PROCEDURE_PARTICIPANT_FILTER_PARTICIPANT_OWNER = 'ParticipantOwnerFilterProcedure';
const PROCEDURE_PARTICIPANT_FILTER_PARTICIPANT_USER_TO_EVENT = 'ParticipantUsersToEventFilterProcedure';

//// TEAM PROCEDURE
const PROCEDURE_TEAM_LIST = 'TeamListProcedure';
const PROCEDURE_TEAM_STORE = 'TeamStoreProcedure';
const PROCEDURE_TEAM_READ = 'TeamReadProcedure';
const PROCEDURE_TEAM_UPDATE = 'TeamUpdateProcedure';
const PROCEDURE_TEAM_DESTROY = 'TeamDestroyProcedure';

//// TOURNAMENT PROCEDURE
const PROCEDURE_TOURNAMENT_READ = 'TournamentReadProcedure';

//// TOURNAMENT ADMIN PROCEDURE
const PROCEDURE_TOURNAMENT_ADMIN_LIST = 'TournamentAdminListProcedure';
const PROCEDURE_TOURNAMENT_ADMIN_STORE = 'TournamentAdminStoreProcedure';
const PROCEDURE_TOURNAMENT_ADMIN_READ = 'TournamentAdminReadProcedure';
const PROCEDURE_TOURNAMENT_ADMIN_UPDATE = 'TournamentAdminUpdateProcedure';
const PROCEDURE_TOURNAMENT_ADMIN_DESTROY = 'TournamentAdminDestroyProcedure';

//// TOURNAMENT HISTORY PROCEDURE
const PROCEDURE_TOURNAMENT_HISTORY_READ = 'TournamentHistoryReadProcedure';

//// TOURNAMENT VALUE PROCEDURE
const PROCEDURE_TOURNAMENT_VALUE_LIST = 'TournamentValueListProcedure';
const PROCEDURE_TOURNAMENT_VALUE_STORE = 'TournamentValueStoreProcedure';
const PROCEDURE_TOURNAMENT_VALUE_READ = 'TournamentValueReadProcedure';
const PROCEDURE_TOURNAMENT_VALUE_UPDATE = 'TournamentValueUpdateProcedure';
const PROCEDURE_TOURNAMENT_VALUE_DESTROY = 'TournamentValueDestroyProcedure';

//// TOURNAMENT VALUE FILTER PROCEDURE
const PROCEDURE_TOURNAMENT_VALUE_FREE_PARTICIPANTS = 'TournamentValueFreeParticipantsFilterProcedure';

//// USER PROCEDURE
const PROCEDURE_USER_READ = 'UserReadProcedure';
const PROCEDURE_USER_REGISTRATION = 'UserRegistrationProcedure';
const PROCEDURE_USER_RESET = 'UserResetProcedure';
const PROCEDURE_USER_UPDATE = 'UserUpdateProcedure';
