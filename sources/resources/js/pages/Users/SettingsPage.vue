<script>
import { getUser, updateUserRequest } from '../../api/UserRequest';
import Breadcrumb from 'primevue/breadcrumb';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import InputMask from 'primevue/inputmask';
import Button from 'primevue/button';
import Message from 'primevue/message';
import Calendar from 'primevue/calendar';
import { createLogOptionRequest } from "../../api/CreateLogOptionRequest";
import {createOptionRequest, getOptionRequest} from "../../api/OptionRequest";
import AppAlertComponent from "../../components/AppAlertComponent.vue";
import AppWrapperComponent from "../../components/wrappers/AppWrapperComponent.vue";
import AppUserUpdateFormComponent from "../../components/forms/AppUserUpdateFormComponent.vue";
import AppUserUpdateEmailFormComponent from "../../components/forms/AppUserUpdateEmailFormComponent.vue";
import AppUserUpdatePhoneFormComponent from "../../components/forms/AppUserUpdatePhoneFormComponent.vue";
import AppUserUpdatePasswordFormComponent from "../../components/forms/AppUserUpdatePasswordFormComponent.vue";
import AppUserCreateOrUpdateOptionFormComponent
    from "../../components/forms/AppUserCreateOrUpdateOptionFormComponent.vue";
import {WEB_URL} from "../../common/route/web";
import {ENDPOINTS} from "../../common/route/api";
import {IDENTIFIER} from "../../common/fields";
import {MESSAGES} from "../../common/messages";

export default {
    data() {
      return {
          messageSuccess: null,
          messageError: null,
          route: ENDPOINTS,
          currentDate: new Date(),
          baseUrl: WEB_URL,
          user: null,
          options: [
              {
                  user_id: window.$cookies.get(IDENTIFIER),
                  entity: 'user',
                  name: 'Weight',
                  value: null,
                  type: 'string',
              },
              {
                  user_id: window.$cookies.get(IDENTIFIER),
                  entity: 'user',
                  name: 'Height',
                  value: null,
                  type: 'string',
              },
          ],
      };
    },
    components: {
        AppUserUpdateFormComponent,
        AppUserUpdateEmailFormComponent,
        AppUserUpdatePhoneFormComponent,
        AppUserUpdatePasswordFormComponent,
        AppUserCreateOrUpdateOptionFormComponent,
        AppWrapperComponent,
        AppAlertComponent,
        Card
    },
    methods: {
        addMessageSuccess: function (data) { this.messageSuccess = data; },
        addMessageError: function (data) { this.messageError = data; },
        userIdentifier: async function () {
            let attributes = {
                id: window.$cookies.get(IDENTIFIER)
            };
            await getUser(attributes)
                .then((response) => {
                    const data = response.data.result.original;
                    this.user = data.attributes;
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
        getUserOptions: async function() {
            let attributes = {
                user_id: window.$cookies.get(IDENTIFIER)
            };
            await getOptionRequest(attributes)
                .then(async (response) => {
                    console.log(response);
                    const data = await response.data.result.original;
                    this.options = await data.attributes;
                })
                .catch(async (error) => {
                    console.log(error);
                    await createLogOptionRequest({
                        current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                        current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                        method: 'getOptionRequest',
                        status: error.code,
                        request_data: '',
                        message: error.message
                    })
                });
        }
    },
    async beforeMount() {
        await this.userIdentifier();
        await this.getUserOptions()
    }
}
</script>

<template>
    <AppWrapperComponent>
        <section class="mt-2"> <!--  w-30 -->
            <div class="text-center mt-3 mb-3">
                <h2>Настройки профиля</h2>
                <AppAlertComponent
                    :messageSuccess="this.messageSuccess"
                    :messageError="this.messageError" />
            </div>
            <Card v-if="this.user">
                <template #content>
                    <h3>Общая информация:</h3>
                    <AppUserUpdateFormComponent
                        :userProps="this.user"
                        @messageSuccessEmit="addMessageSuccess"
                        @messageErrorEmit="addMessageError" />
                </template>
            </Card>
            <Card v-if="this.user" class="mt-3">
                <template #content>
                    <h3>Сменить адрес электронной почты</h3>
                    <AppUserUpdateEmailFormComponent
                        :userEmailProps="this.user.email"
                        @messageSuccessEmit="addMessageSuccess"
                        @messageErrorEmit="addMessageError" />
                </template>
            </Card>
            <Card v-if="this.user" class="mt-3">
                <template #content>
                    <h3>Сменить номер телефона</h3>
                    <AppUserUpdatePhoneFormComponent
                        :userPhoneProps="this.user.phone"
                        @messageSuccessEmit="addMessageSuccess"
                        @messageErrorEmit="addMessageError" />
                </template>
            </Card>
            <Card class="mt-3">
                <template #content>
                    <h3>Сменить пароль</h3>
                    <AppUserUpdatePasswordFormComponent
                        :userEmailProps="this.user.email"
                        @messageSuccessEmit="addMessageSuccess"
                        @messageErrorEmit="addMessageError" />
                </template>
            </Card>
            <Card v-if="this.user && this.user.role === 'athlete'"
                  class="mt-3">
                <template #content>
                    <h3>Параметры спортсмена</h3>
                    <small>Параметры учитываются в алгоритме жеребьёвки. Их желательно заполнить.</small>
                    <AppUserCreateOrUpdateOptionFormComponent
                        :userOptionsProps="this.options"
                        @messageSuccessEmit="addMessageSuccess"
                        @messageErrorEmit="addMessageError" />
                </template>
            </Card>
        </section>
    </AppWrapperComponent>
</template>
