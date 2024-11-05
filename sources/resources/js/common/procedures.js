export const PROCEDURES= {
    users: {
        registration:           'UserRegistrationProcedure',
        read:                   'UserReadProcedure',
        update:                 'UserUpdateProcedure',
        reset:                  'UserResetProcedure',
    },
    invites: {
        list:                   'InvitedListProcedure',
        create:                 'InvitedStoreProcedure',
        read:                   'InvitedReadProcedure',
        readUserParticipant:    'InvitedReadUserParticipantProcedure',
        notification:           'NotificationProcedure',
    },
    auth: {
        login:                  'AuthByEmailProcedure',
    },
    filter: {
        userRecord:             'EventDateFilterProcedure',
        ownerParticipantList:   'ParticipantOwnerFilterProcedure',
        ownerEventsList:        'EventOwnerFilterProcedure',
        participantUsers:       'ParticipantUsersToEventFilterProcedure',
    },
    event: {
        list:                   'EventListProcedure',
        create:                 'EventStoreProcedure',
        read:                   'EventReadProcedure',
        update:                 'EventUpdateProcedure',
        getKey:                 'EventGetKeyProcedure',
    },
    archive: {
        create:                 'ArchiveStoreProcedure',
        delete:                 'ArchiveDestroyProcedure',
    },
    option: {
        create:                 'OptionStoreProcedure',
        read:                   'OptionReadProcedure',
        update:                 'OptionUpdateProcedure',
        delete:                 'OptionDestroyProcedure',
    },
    participant: {
        create:                 'ParticipantStoreProcedure',
        additionally: {
            drop:               'ParticipantDisqualificationProcedure',
            replace:            'ParticipantКReplacementProcedure',
            skip:               'ParticipantSkippedProcedure',
        }
    },
    log: {
        create:                 'LogStoreProcedure',
        list:                   'LogListProcedure',
    },
    tournament: {
        read:                   'TournamentReadProcedure',
        value: {
            create:             'TournamentValueStoreProcedure',
            filter: {
                free:           'TournamentValueFreeParticipantsFilterProcedure'
            }
        },
        admin: {
            list: 'TournamentAdminListProcedure'
        },
        history: {
            read: 'TournamentHistoryReadProcedure'
        }
    }
};
