<script>
    import { addNotificationUserInviteEventRequest } from "../../api/InvitedRequest";
    import { createLogOptionRequest } from "../../api/CreateLogOptionRequest";
    import { recordUserToEventRequest } from "../../api/ParticipantRequest";
    import { getInvitedOwnerRequest } from "../../api/UserRequest";
    import Dialog from "primevue/dialog";
    import Listbox from "primevue/listbox";
    import InputText from "primevue/inputtext";
    import Button from "primevue/button";
    import AppAlertComponent from "../AppAlertComponent.vue";
    import {IDENTIFIER} from "../../common/fields";
    import {MESSAGES} from "../../common/messages";

    export default {
        data() {
            return {
                currentDate: new Date(),
                dialog: false,
                eventId: null,
                invites: [],
                user: null,
                selectedInvite: null,
                invitedEmail: '',
                messageInviteDialogSuccess: null,
                messageInviteDialogError: null,
                errors: []
            };
        },
        props: {
            userProps: Object,
            eventIdProps: String
        },
        components: {
            AppAlertComponent,
            Dialog,
            Listbox,
            InputText,
            Button
        },
        methods: {
            getWhoInvited: async function () {
                let attributes = {
                    who_user_id: window.$cookies.get(IDENTIFIER)
                };
                await getInvitedOwnerRequest(attributes)
                    .then(async (response) => {
                        const data = await response.data.result.original;
                        this.invites = await data.attributes;
                    })
                    .catch(async (error) => {
                        console.log(error)
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getInvitedOwnerRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.messageError = MESSAGES.LOADING_ERROR + ' 3';
                    });
            },
            isValid: function(fields) {
                this.errors = fields
            },
            recordAndInvitedUser: async function() {
                let attributes = {
                    email: this.invitedEmail,
                    invited_user_id: this.user.id,
                    event_id: this.eventId,
                };

                await addNotificationUserInviteEventRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        if ('error' in response.data) {
                            this.isValid(response.data.error.data);
                            return;
                        }
                        const data = await response.data.result.original;
                        this.messageInviteDialogSuccess = await data.attributes ? MESSAGES.FORM_SUCCESS : MESSAGES.ERROR_ERROR;
                        this.invitedEmail = '';
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'recordUserToEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.messageInviteDialogError = MESSAGES.ERROR_ERROR;
                    });
            },
            recordToInvitedUser: async function () {
                let attributes = {
                    event_id: this.eventId,
                    user_id: this.selectedInvite.users.id,
                    invited_user_id: this.selectedInvite.who_user.id,
                    admin_id: this.user.id,
                    // team_key: null,
                };
                await recordUserToEventRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.messageSuccess = await data.attributes ? MESSAGES.FORM_SUCCESS : MESSAGES.ERROR_ERROR;
                    })
                    .catch(async (error) => {
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'recordUserToEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.messageError = MESSAGES.ERROR_ERROR;
                    });
            },
            inviteToEvent: async function () {
                if (this.invitedEmail.length > 0)
                {
                    await this.recordAndInvitedUser();
                    return;
                }
                if (this.selectedInvite) {
                    await this.recordToInvitedUser();
                    return;
                }
            },
        },
        async beforeMount() {
            if (this.user)
            {
                await this.getWhoInvited()
            }
        },
        watch: {
            eventIdProps: {
                handler(value) {
                    this.eventId = value;
                },
                immediate: true,
                deep: true
            },
            userProps: {
                handler(value) {
                    this.user = value;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <Button label="Записать спортсмена на событие"
            severity="primary"
            class="w-100"
            @click="this.dialog=true" />

    <Dialog v-if="this.userProps && this.userProps.role === 'admin'"
            v-model:visible="this.dialog"
            maximizable
            modal
            header="Пригласить участника"
            :style="{ width: '50rem' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <AppAlertComponent
            :messageSuccess="this.messageInviteDialogSuccess"
            :messageError="this.messageInviteDialogError" />
        <div v-if="this.invites.length > 0" class="mb-3">
            <label class="font-semibold w-6rem">Выберите спортсмена из списка</label>
            <Listbox v-model="this.selectedInvite"
                     :options="this.invites"
                     class="w-100"
                     listStyle="max-height:310px">
                <template #option="slotProps">
                    <div class="flex align-items-center">
                        <div>{{ slotProps.option.users.first_name }} {{ slotProps.option.users.last_name }}</div>
                    </div>
                </template>
            </Listbox>
            <small>
                <b>Не нашли спортсмена? Воспользуйтесь формой ниже.</b>
            </small>
        </div>
        <div class="mb-3">
            <label class="font-semibold w-6rem">Отправить приглашение по E-mail</label>
            <InputText type="email"
                       v-model="this.invitedEmail"
                       class="w-100"
                       :invalid="this.errors !== null && 'email' in this.errors" />
            <section id="errorField" v-if="this.errors !== null && 'email' in this.errors">
                <small v-for="error in this.errors.email">
                    <i class="pi pi-times-circle"></i> {{ error }}
                </small>
            </section>
        </div>
        <div class="mb-3">
            <Button label="Записать на событие"
                    severity="success"
                    class="w-100"
                    @click="this.inviteToEvent" />
        </div>
    </Dialog>
</template>
