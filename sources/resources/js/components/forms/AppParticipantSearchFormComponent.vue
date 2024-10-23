<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import InputText from "primevue/inputtext";
    import Button from "primevue/button";
    import {MESSAGES} from "../../constant";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import {participantSearchAthleteRequest} from "../../api/ParticipantRequest";

    export default {
        data() {
            return {
                errors: [],
                first_name: null,
                first_name_eng: null,
                last_name: null,
                last_name_eng: null,
                email: null,
                phone: null,
                birth_date: null,
                gender: null,
                width: null,
                height: null,
                participants: null
            };
        },
        props: {
            eventKeyProps: String
        },
        components: {
            AppFormWrapperComponent,
            InputText,
            Button
        },
        methods: {
            isValid: function(fields) {
                this.errors = fields
            },
            searchParticipants: async function () {
                let attributes = {
                    event_key: this.eventKeyProps,
                    first_name: this.first_name,
                    first_name_eng: this.first_name_eng,
                    last_name: this.last_name,
                    last_name_eng: this.last_name_eng,
                    email: this.email,
                    phone: this.phone,
                    birth_date: this.birth_date,
                    width: this.width,
                    height: this.height,
                };
                await participantSearchAthleteRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        if ('error' in response.data) {
                            this.isValid(response.data.error.data);
                            return;
                        }
                        const data = await response.data.result.original;
                        this.participants = await data.attributes;
                        this.$emit('participantsEmit', this.participants);
                    })
                    .catch(async (error) => {
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'participantSearchAthleteRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.messageError = MESSAGES.LOADING_ERROR + ' 2';
                    });
            },
        }
    }
</script>

<template>
    <AppFormWrapperComponent>
        <div class="d-flex d-between d-align-center">
            <div class="form-block">
                <label for="name">Имя</label>
                <InputText type="text"
                           v-model="this.first_name"
                           class="w-100"
                           :invalid="this.errors !== null && 'first_name' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'first_name' in this.errors">
                    <small v-for="error in this.errors.first_name">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
            <div class="form-block">
                <label for="nameLat">Имя на латинице</label>
                <InputText type="text"
                           v-model="this.first_name_eng"
                           class="w-100"
                           :invalid="this.errors !== null && 'first_name_eng' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'first_name_eng' in this.errors">
                    <small v-for="error in this.errors.first_name_eng">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </div>
        <div class="d-flex d-between d-align-center">
            <div class="form-block">
                <label for="lastName">Фамилия</label>
                <InputText type="text"
                           v-model="this.last_name"
                           class="w-100"
                           :invalid="this.errors !== null && 'last_name' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'last_name' in this.errors">
                    <small v-for="error in this.errors.last_name">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
            <div class="form-block">
                <label for="lastNameLat">Фамилия на латинице</label>
                <InputText type="text"
                           v-model="this.last_name_eng"
                           class="w-100"
                           :invalid="this.errors !== null && 'last_name_eng' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'last_name_eng' in this.errors">
                    <small v-for="error in this.errors.last_name_eng">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </div>
        <div class="d-flex d-between d-align-center">
            <div class="form-block w-50">
                <label for="#">Дата рождения</label>
                <InputText type="date"
                           v-model="this.birth_date"
                           class="w-100"
                           :invalid="this.errors !== null && 'birth_date' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'birth_date' in this.errors">
                    <small v-for="error in this.errors.birth_date">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
            <div class="form-block w-50">
                <label for="#">Укажите пол</label>
                <div class="flexing">
                    <label for="radioMale" class="w-auto">
                        <input type="radio"
                               v-model="this.gender"
                               name="gender"
                               value="Мужчина"
                               class="w-auto" />
                        Мужской
                    </label>
                    <label for="radioFemale" class="w-auto">
                        <input type="radio"
                               v-model="this.gender"
                               name="gender"
                               value="Женщина"
                               class="w-auto" />
                        Женский
                    </label>
                </div>
            </div>
        </div>
        <div class="d-flex d-between d-align-center">
            <div class="form-block">
                <label for="#">Укажите вес</label>
                <InputText type="number"
                           v-model="this.weight"
                           class="w-100"
                           :invalid="this.errors !== null && 'weight' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'weight' in this.errors">
                    <small v-for="error in this.errors.weight">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
            <div class="form-block">
                <label for="#">Укажите рост</label>
                <InputText type="number"
                           v-model="this.height"
                           class="w-100"
                           :invalid="this.errors !== null && 'height' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'height' in this.errors">
                    <small v-for="error in this.errors.height">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </div>
        <br>
        <Button label="Найти"
                class="w-100"
                severity="success"
                @click="this.searchParticipants()" />
    </AppFormWrapperComponent>
</template>

