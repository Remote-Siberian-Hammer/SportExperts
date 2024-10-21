<script>
    import Button from "primevue/button";
    import Dialog from 'primevue/dialog';
    import {getUser} from "../../api/UserRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import {MESSAGES, TRIGGER} from "../../constant";
    import {readUserParticipantInvitedRequest} from "../../api/InvitedRequest";
    import {getParticipantsToEventRequest} from "../../api/FilterRequest";
    import {
        participantUserRemoveAdditionallyRequest,
        participantUserReplaceAdditionallyRequest,
        participantUserSkipAdditionallyRequest
    } from "../../api/ParticipantRequest";
    import {tournamentReadRequest} from "../../api/TournamentRequest";

    export default {
        data() {
            return {
                currentDate: new Date(),
                dialog: false,
                eventKey: null,
                participant: {
                    user: null,
                    invite: null,
                    users: null
                }
            };
        },
        props: {
            userIdProps: Number,
            valueIdProps: Number,
            eventKeyProps: String,
            participantsPositionProps: String,
            stageProps: Number
        },
        components: {
            Button,
            Dialog,
        },
        methods: {
            addMessageError: async function (data) { await this.$emit('messageErrorEmit', data); },
            formatDate(birthDate) {
                // Разбиваем исходную строку по символу "-"
                const [year, month, day] = birthDate.split('-');

                // Собираем дату в формате dd.mm.yyyy
                return `${day}.${month}.${year}`;
            },
            getUserParticipant: async function(id) {
                const attributes = {
                    id: id
                }
                await getUser(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.participant.user = await data.attributes;
                    })
                    .catch(async (error) => {
                        console.log(response);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getUser',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        await this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                    });
            },
            getUserInvite: async function(userId) {
                const attributes = {
                    user_id: userId
                };
                await readUserParticipantInvitedRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.participant.invite = await data.attributes;
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'readUserParticipantInvitedRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        await this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                    });
            },
            getListParticipants: async function(eventKey, mode='card') {
                const attributes = {
                    event_key: eventKey
                };
                await getParticipantsToEventRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        if (mode === 'card')
                        {
                            this.participant.users = await data.attributes;
                        }
                        else {
                            this.participantList = await data.attributes;
                        }
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getParticipantsToEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        await this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                    });
            },
            getParticipantInfo: async function(userId) {
                await this.getUserParticipant(userId);
                await this.getUserInvite(this.participant.user.id);
                await this.getListParticipants(this.eventKey);
            },
            readTournament: async function() {
                const attributes = {
                    event_key: this.eventKey,
                };
                await tournamentReadRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.values = await data.attributes;
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
                        await this.$emit('messageErrorEmit', MESSAGES.ERROR_ERROR);
                    });
            },
            dropTournamentParticipant: async function(participants_position, stage)
            {
                const attributes = {
                    event_key: this.eventKey,
                    participants_position: participants_position,
                    user_id: this.participant.user.id,
                    stage: stage
                };
                await participantUserRemoveAdditionallyRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = response.data.result.original;
                        if (data.attributes.id)
                        {
                            this.closeDialog();
                            // await this.readTournament();
                            window.$cookies.set(TRIGGER, true);
                        }
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'participantUserRemoveAdditionallyRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        // await this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                    });
            },
            skipToParticipant: async function(value_id, stage, participant)
            {
                const attributes = {
                    admin_id: this.userIdProps,
                    tournament_value_id: value_id,
                    event_key: this.eventKey,
                    stage: stage,
                    ...participant
                };
                await participantUserSkipAdditionallyRequest(attributes)
                    .then((response) => {
                        console.log(response);
                        window.$cookies.set(TRIGGER, true);
                        //window.location.reload();

                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'participantUserSkipAdditionallyRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        await this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                    });
            },
            replaceToParticipant: async function(values)
            {
                const attributes = values;
                await participantUserReplaceAdditionallyRequest(attributes)
                    .then(async (response) => {
                        window.$cookies.set(TRIGGER, true);
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'participantUserReplaceAdditionallyRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        await this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                    });
            },

            openDialog(userId) {
                this.getParticipantInfo(userId)
                this.dialog=true;
            },
            closeDialog() {
                this.dialog = false;
                this.participant.user = null;
                this.participant.invite = null;
                this.participant.users = null;
            },
        },
        watch: {
            eventKeyProps: {
                handler(value) {
                    this.eventKey = value;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <Button icon="pi pi-cog" severity="info" @click="this.openDialog(this.userIdProps)" />
    <Dialog v-model:visible="this.dialog"
            maximizable
            modal
            header="Параметры спортсмена"
            :style="{ width: '50rem' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="mb-3">
            <section class="d-flex d-between d-align-center">
                <div class="w-70">
                    <section>
                        <h2>{{ this.participant.user.last_name }} {{ this.participant.user.first_name }}</h2>
                    </section>
                    <section class="mb-1 mt-1">
                        <p>({{ this.participant.user.age }} лет)</p>
                        <p><b>Дата рождения:</b> {{ this.formatDate(this.participant.user.birth_date) }}</p>
                        <section v-for="option in this.participant.user.options" :key="option.name">
                            <p v-if="option.name === 'Height'"><b>Рост:</b> {{ option.value }} см</p>
                            <p v-if="option.name === 'Weight'"><b>Вес:</b> {{ option.value }} кг</p>
                        </section>
                    </section>
                    <section>
                        <p><b>Пригласил:</b> {{ this.participant.invite.who_user.last_name }} {{ this.participant.invite.who_user.first_name }}</p>
                    </section>
                </div>
                <div class="w-30">
                    <div class="mb-2">
                        <Button label="Дисквалифицировать"
                                severity="danger"
                                class="w-100"
                                @click="this.dropTournamentParticipant(this.participantsPositionProps, this.stageProps)" />
                    </div>
                    <div class="mb-2">
                        <Button label="Пропустить"
                                severity="success"
                                class="w-100"
                                @click="this.skipToParticipant(this.valueIdProps, this.stageProps, {user_id: this.participant.user.id})" />
                    </div>
                </div>
            </section>
            <hr>
            <h3 class="text-center">Заменить спортсмена</h3>
            <section>
                <div v-for="item in this.participant.users"
                     :key="item.user_id"
                     class="d-flex d-between d-align-center d-flex-wrap">
                    <div class="w-70">
                        <p>
                            <strong>{{ item.users.first_name }} {{ item.users.last_name }}</strong>
                        </p>
                        <section>
                            <small>
                                Возраст: {{ item.users.age }} лет
                            </small>
                        </section>
                        <section v-for="option in item.users.options"
                                 :key="option.name">
                            <small v-if="option.name === 'Height'">
                                Рост: {{ option.value }} см
                            </small>
                            <small v-if="option.name === 'Weight'">
                                Вес: {{ option.value }} кг
                            </small>
                        </section>
                    </div>
                    <div class="w-30">
                        <Button label="Заменить"
                                severity="primary"
                                class="w-100"
                                @click="this.replaceToParticipant({
                                    event_key: this.eventKeyProps,
                                    new_participant_key: item.key,
                                    stage: this.stageProps,
                                    user_id: this.participant.invite.users.id,
                                })"/>
                    </div>
                    <hr class="w-100">
                </div>
            </section>
        </div>
    </Dialog>
</template>
