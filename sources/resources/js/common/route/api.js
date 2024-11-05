export const API_URL =`${import.meta.env.VITE_HTTP_PROTOCOL}://${import.meta.env.VITE_HTTP_HOST}:${import.meta.env.VITE_HTTP_PORT}/`;
export const ENDPOINTS = {
    API: {
        LIST: 'list',
        STORE: 'store',
        READ: 'read',
        UPDATE: 'update',
        DESTROY: 'destroy',
        FILTER: 'filter',
    },
    SETTINGS: 'settings',
    HISTORY: 'history',
    ARCHIVE: 'archive',
    BASE: '/',
    CREATE: 'create',
    DETAIL: 'detail',
    DELETE: 'delete',
    EVENT: 'event',
    FILTER: 'filter',
    LOGIN: 'login',
    PROFILE: 'profile',
    RECOVERY: 'recovery',
    REGISTRATION: 'registration',
    UPDATE: 'update',
    TOURNAMENT: 'tournament'
};
