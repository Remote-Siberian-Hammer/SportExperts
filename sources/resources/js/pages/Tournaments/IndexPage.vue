<script>
    import {BASE_URL, MESSAGES, ENDPOINTS, IDENTIFIER} from '../../constant';
    import { createLogOptionRequest } from "../../api/CreateLogOptionRequest";
    import { getKeyEventRequest } from "../../api/EventRequest";
    import AppParticipantInfoModalComponent from "../../components/modals/AppParticipantInfoModalComponent.vue";
    import AppAlertComponent from "../../components/AppAlertComponent.vue";
    import AppTournamentStageComponent from "../../components/AppTournamentStageComponent.vue";
    import AppTournamentValuesComponent from "../../components/AppTournamentValuesComponent.vue";
    import AppTournamentInfoCardComponent from "../../components/cards/AppTournamentInfoCardComponent.vue";
    import {tournamentReadRequest} from "../../api/TournamentRequest";
    import AppParticipantsCardComponent from "../../components/cards/AppParticipantsCardComponent.vue";
    import {getUser} from "../../api/UserRequest";
    import {getTournamentAdminRequest} from "../../api/TournamentAdminRequest";
    import Button from 'primevue/button';

    export default {
        data() {
            return {
                messageSuccess: null,
                messageError: null,
                baseUrl: BASE_URL,
                eventKey: '',
                event: null,
                user: null,
                values: null,
                rule: 'DEFAULT',
                admins: []
            };
        },
        components: {
            AppParticipantsCardComponent,
            AppTournamentValuesComponent,
            AppTournamentStageComponent,
            AppAlertComponent,
            AppParticipantInfoModalComponent,
            AppTournamentInfoCardComponent,
            Button
        },
        methods: {
            addMessageSuccess: function (data) { this.messageSuccess = data; },
            addMessageError: function (data) { this.messageError = data; },
            userIdentifier: async function () {
                if (window.$cookies.get(IDENTIFIER))
                {
                    let attributes = {id: window.$cookies.get(IDENTIFIER)};
                    await getUser(attributes)
                        .then(async (response) => {
                            const data = await response.data.result.original;
                            this.user = await data.attributes;
                        })
                        .catch(async (error) => {
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'getUserRequest',
                                status: error.code,
                                request_data: attributes.toString(),
                                message: error.message
                            });
                            this.messageError = MESSAGES.LOADING_ERROR;
                        });
                }
            },
            getUrl: function() {
                const urlParams = new URLSearchParams(window.location.search);
                this.eventKey = urlParams.get('event');
            },
            getEvent: async function(eventKey) {
                const attributes = {
                    key: eventKey
                };
                await getKeyEventRequest(attributes)
                    .then(async (response) => {
                        console.log(response)
                        const data = await response.data.result.original;
                        this.event = await data.attributes;
                    })
                    .catch(async (error) => {
                        console.log(error)
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getKeyEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.messageError = MESSAGES.ERROR_ERROR;
                    });
            },
            async readTournament() {
                const attributes = {
                    event_key: this.eventKey,
                };
                await tournamentReadRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.values = await data.attributes;
                        // Сортировка "tournament_values", у кого нет пары смещаем в конец
                        for (let attributes in this.values) {
                            this.values[attributes][0].tournament_values.sort((a, b) => (a.participants_B === null) - (b.participants_B === null));
                        }
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'tournamentReadRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.messageError = MESSAGES.ERROR_ERROR;
                    });
            },
            getAdmins: async function() {
                const attributes = {
                    event_key: this.eventKey
                };
                getTournamentAdminRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.admins = await data.attributes;
                        const rules = ['OWNER', 'ADMIN'];
                        this.admins.forEach((admin) => {
                            console.log(this.event.owner.id);
                            console.log(admin.users.id);
                            if (this.event.owner.id === admin.users.id && admin.users.id === this.user.id) {
                                this.rule = rules[0];
                            } else if (admin.users.id === this.user.id) {
                                this.rule = rules[1];
                            }
                        });
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getTournamentAdminRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                    })
            },
        },
        async beforeMount() {
            this.getUrl();
            await this.userIdentifier();
            await this.getEvent(this.eventKey);
            await this.readTournament();
            await this.getAdmins();
        }
    };
</script>

<template>
    <section class="d-center">
        <section class="mt-3">
            <AppAlertComponent
                :messageSuccess="this.messageSuccess"
                :messageError="this.messageError" />
            <section v-if="this.event">
                <h2 class="text-center">{{ this.event.name }}</h2>
                <section class="d-flex d-center mb-1">
                    <Button v-if="this.rule === 'OWNER'"
                            label="История изменений"
                            icon="pi pi-history"
                            aria-label="Info" />
                </section>

            </section>
            <div class="d-flex d-between">
                <div class="w-30 d-flex d-end">
                    <AppTournamentInfoCardComponent
                        :eventKeyProps="this.eventKey"
                        :adminsProps="this.admins"
                        :eventProps="this.event"
                        :roleProps="this.rule"
                        @messageErrorEmit="addMessageError" />
                </div>
                <div class=" d-flex scroll-bottom"
                     style="
                        position: relative;
                        right: 2.5%;
                        width: 65vw;
                        height: 73vh;
                        background-color: #f2f2f2;
                        border-radius: 1em;
                        box-shadow: 2px 1px 9px 2px rgba(34, 60, 80, 0.2);
                    ">
                   <div v-for="attribute in this.values" class="w-22e">
                        <template v-for="tournament in attribute" :key="tournament.id">
                            <AppTournamentStageComponent
                                :eventKeyProps="this.eventKey"
                                :tournamentProps="tournament"
                                :roleProps="this.rule"
                                @messageErrorEmit="addMessageError" />
                        </template>
                   </div>
                </div>
            </div>
        </section>
    </section>
</template>
