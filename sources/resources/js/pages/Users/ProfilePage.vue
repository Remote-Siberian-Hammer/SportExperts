<script>

import { getUser } from '../../api/UserRequest';
import { createLogOptionRequest } from '../../api/CreateLogOptionRequest';
import AppRoleTitleComponent from "../../components/AppRoleTitleComponent.vue";
import AppAlertComponent from "../../components/AppAlertComponent.vue";
import AppProfileCardComponent from "../../components/cards/AppProfileCardComponent.vue";
import AppOwnerCardComponent from "../../components/cards/AppOwnerCardComponent.vue";
import AppParticipantsCardComponent from "../../components/cards/AppParticipantsCardComponent.vue";
import AppEventsAdminCardComponent from "../../components/cards/AppEventsAdminCardComponent.vue";
import AppRegistrationFormComponent from "../../components/forms/AppRegistrationFormComponent.vue";
import AppRecordsListCardComponent from "../../components/cards/AppRecordsListCardComponent.vue";
import {WEB_URL} from "../../common/route/web";
import {ENDPOINTS} from "../../common/route/api";
import {IDENTIFIER, TOKEN} from "../../common/fields";
import {MESSAGES} from "../../common/messages";


export default {
    data() {
        return {
            baseUrl: WEB_URL,
            route: ENDPOINTS,
            user: null,
            token: null,
            messageError: null,
            messageSuccess: null,
        };
    },
    components: {
        AppRegistrationFormComponent,
        AppAlertComponent,
        AppRoleTitleComponent,
        AppProfileCardComponent,
        AppOwnerCardComponent,
        AppParticipantsCardComponent,
        AppEventsAdminCardComponent,
        AppRecordsListCardComponent
    },
    methods: {
        addMessageSuccess: function (data) { this.messageSuccess = data; },
        addMessageError: function (data) { this.messageError = data; },
        userIdentifier: async function ()
        {
            let attributes = {
                id: window.$cookies.get(IDENTIFIER)
            };
            await getUser(attributes)
                .then(async (response) => {
                    console.log(response);
                    const data = await response.data.result.original;
                    this.user = await data.attributes;
                })
                .catch(async (error) => {
                    console.log(error);
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
        },
        tokenRead: async function ()
        {
            this.token = await window.$cookies.get(TOKEN);
        },
    },
    async beforeMount() {
        await this.tokenRead();
        await this.userIdentifier();
    }
}
</script>

<template>
    <AppRoleTitleComponent :userProps="this.user" />
    <AppAlertComponent
        :messageSuccess="this.messageSuccess"
        :messageError="this.messageError" />
    <section class="d-flex d-between container wrap">
        <div class="w-30">
            <AppProfileCardComponent
                :userProps="this.user"
                :routeProps="this.route"
                :baseUrlProps="this.baseUrl"
                @messageSuccessEmit="addMessageSuccess"
                @messageErrorEmit="addMessageError"/>
            <!-- Виджет отображающий того кто пригласил -->
            <AppOwnerCardComponent
                v-if="this.user.role === 'athlete'"
                :userProps="this.user"
                @messageSuccessEmit="addMessageSuccess"
                @messageErrorEmit="addMessageError"/>
            <!-- Виджет приглашенных спортсменов -->
            <AppParticipantsCardComponent
                v-if="this.user.role === 'admin'"
                :userProps="this.user"
                :baseUrlProps="this.baseUrl"
                @messageSuccessEmit="addMessageSuccess"
                @messageErrorEmit="addMessageError" />
        </div>
        <div class="w-70">
            <!-- ADMIN VIEW -->
            <AppEventsAdminCardComponent
                v-if="this.user && this.user.role === 'admin'"
                :userProps="this.user"
                :baseUrlProps="this.baseUrl"
                :routeProps="this.route"
                @messageSuccessEmit="addMessageSuccess"
                @messageErrorEmit="addMessageError" />
            <!-- ATHLETE VIEW -->
            <AppRecordsListCardComponent
                v-if="this.user && this.user.role === 'athlete'"
                :baseUrlProps="this.baseUrl"
                :routeProps="this.route"
                @messageSuccessEmit="addMessageSuccess"
                @messageErrorEmit="addMessageError"/>
        </div>
    </section>
</template>
