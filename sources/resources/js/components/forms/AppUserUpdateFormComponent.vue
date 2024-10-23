<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import {IDENTIFIER, MESSAGES} from "../../constant";
    import {updateUserRequest} from "../../api/UserRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import InputText from "primevue/inputtext";
    import Calendar from "primevue/calendar";
    import Button from "primevue/button";
    import SelectButton from "primevue/selectbutton";

    export default {
        data() {
          return {
              currentDate: new Date(),
              user: null,
              errors: [],
              roles: ['Администратор', 'Спортсмен'],
          }
        },
        props: {
            userProps: Object
        },
        components: {
            AppFormWrapperComponent,
            InputText,
            Calendar,
            Button,
            SelectButton
        },
        methods: {
            isValid: function(fields) {
                this.errors = fields
            },
            dateFormat: async function(dateStr) {
                const dateObj = await new Date(dateStr);
                const day = String(dateObj.getDate()).padStart(2, '0');
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const year = dateObj.getFullYear();
                return `${day}.${month}.${year}`;
            },
            userUpdate: async function ()
            {
                let attributes = {
                    id: window.$cookies.get(IDENTIFIER),
                    first_name: this.user.first_name,
                    first_name_eng: this.user.first_name_eng,
                    last_name: this.user.last_name,
                    last_name_eng: this.user.last_name_eng,
                    birth_date: await this.dateFormat(this.user.birth_date),
                };
                await updateUserRequest(attributes)
                    .then((response) => {
                        console.log(response);
                        if ('error' in response.data) {
                            this.isValid(response.data.error.data);
                            return;
                        }
                        this.messageSuccess = 'Данные сохранены';
                        this.$emit('messageSuccessEmit', MESSAGES.FORM_SUCCESS);
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'updateUserRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.$emit('messageErrorEmit', MESSAGES.ERROR_ERROR);
                    });
            },
        },
        watch: {
            userProps: {
                handler(newVal) {
                    /*if (newVal.role === 'admin') {
                        newVal = 'Администратор';
                    } else {
                        newVal = 'Спортсмен';
                    }*/
                    this.user = newVal;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <AppFormWrapperComponent>
        <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="name">Имя</label>
                <InputText type="text"
                           v-model="this.user.first_name"
                           class="w-100"
                           :value="this.user.first_name"
                           :invalid="this.errors !== null && 'first_name' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'first_name' in this.errors">
                    <small v-for="error in this.errors.first_name">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </section>
        <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="name">Имя на Английском</label>
                <InputText type="text"
                           v-model="this.user.first_name_eng"
                           class="w-100"
                           :value="this.user.first_name_eng"
                           :invalid="this.errors !== null && 'first_name_eng' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'first_name_eng' in this.errors">
                    <small v-for="error in this.errors.first_name_eng">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </section>
        <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="name">Фамилия</label>
                <InputText type="text"
                           v-model="this.user.last_name"
                           class="w-100"
                           :value="this.user.last_name"
                           :invalid="this.errors !== null && 'last_name' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'last_name' in this.errors">
                    <small v-for="error in this.errors.last_name">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </section>
        <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="name">Фамилия на Английском</label>
                <InputText type="text"
                           v-model="this.user.last_name_eng"
                           class="w-100"
                           :value="this.user.last_name_eng"
                           :invalid="this.errors !== null && 'last_name_eng' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'last_name_eng' in this.errors">
                    <small v-for="error in this.errors.last_name_eng">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </section>
        <!-- <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="#">Выберите роль</label>
                <div class="card flex justify-center">
                    <SelectButton v-model="this.user.role"
                                  :options="this.roles"
                                  aria-labelledby="basic" />
                </div>
            </div>
        </section> -->
        <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="name">Дата рождения</label>
                <Calendar type="date"
                          :showOnFocus="false"
                          inputId="buttondisplay"
                          v-model="this.user.birth_date"
                          class="w-100"
                          :value="this.user.birth_date"
                          dateFormat="dd.mm.yyyy"
                          showIcon
                          :invalid="this.errors !== null && 'birth_date' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'birth_date' in this.errors">
                    <small v-for="error in this.errors.birth_date">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </section>
        <Button type="button"
                label="Сохранить"
                class="w-100 mt-3"
                severity="success"
                @click="this.userUpdate" />
    </AppFormWrapperComponent>
</template>
