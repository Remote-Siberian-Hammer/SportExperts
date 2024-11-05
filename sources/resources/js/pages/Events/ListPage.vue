<script>
import { createLogOptionRequest } from '../../api/CreateLogOptionRequest';
import { getUser } from "../../api/UserRequest";
import AppAlertComponent from "../../components/AppAlertComponent.vue";
import AppEventListComponent from "../../components/AppEventListComponent.vue";
import AppAddModalOptions from "../../components/modals/AppAddModalOptions.vue";
import AppUserUpdateEmailFormComponent from "../../components/forms/AppUserUpdateEmailFormComponent.vue";
import {WEB_URL} from "../../common/route/web";
import {ENDPOINTS} from "../../common/route/api";
import {IDENTIFIER} from "../../common/fields";

export default {
    data() {
      return {
          messageSuccess: null,
          messageError: null,
          response: {
              data: 'data'
          },
          route: ENDPOINTS,
          baseUrl: WEB_URL,
          user: null
      };
    },
    components: {
        AppUserUpdateEmailFormComponent,
        AppAlertComponent,
        AppAddModalOptions,
        AppEventListComponent,
    },
    methods: {
        userIdentifier: async function ()
        {
            const userId = window.$cookies.get(IDENTIFIER);
            if (userId)
            {
                let attributes = {id: userId};
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
                            method: 'getUser',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        })
                    });
            }
        },
        addMessageSuccess: function (data) { this.messageSuccess = data; },
        addMessageError: function (data) { this.messageError = data; },
    },
    async beforeMount() {
        await this.userIdentifier();
    }
}
</script>

<template>
    <AppAddModalOptions
        :userProps="this.user"
        @messageSuccessEmit="addMessageSuccess"
        @messageErrorEmit="addMessageError" />
    <section class="mt-5 mb-5">
        <AppAlertComponent
            :messageSuccess="this.messageSuccess"
            :messageError="this.messageError" />
        <AppEventListComponent
            :userProps="this.user"
            :baseUrlProps="this.baseUrl"
            :routeProps="this.route"
            :responseProps="this.response"
            @messageSuccessEmit="addMessageSuccess"
            @messageErrorEmit="addMessageError" />
    </section>
<!--    <section v-if="this.events && this.events[this.response.data].length > 9" class="mt-5 mb-5">-->
<!--    <Paginator :rows="9" :totalRecords="120"></Paginator>-->
<!--    </section>-->
</template>

