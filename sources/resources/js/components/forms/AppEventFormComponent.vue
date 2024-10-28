<script>
    import { createLogOptionRequest } from "../../api/CreateLogOptionRequest";
    import { ENDPOINTS, IDENTIFIER, MESSAGES } from "../../constant";
    import {
        createEventOptionRequest,
        createEventRequest,
        updateEventOptionRequest,
        updateEventRequest
    } from "../../api/EventRequest";
    import { QuillEditor } from "@vueup/vue-quill";
    import InputText from "primevue/inputtext";
    import Card from "primevue/card";
    import Image from "primevue/image";
    import FileUpload from "primevue/fileupload";
    import Calendar from "primevue/calendar";
    import Button from "primevue/button";
    import { uploadFileRequest } from "../../api/FileRequest";
    import { deleteOptionRequest } from "../../api/OptionRequest";
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";

    export default {
        name: 'AppEventFormComponent',
        data() {
            return {
                baseUrl: null,
                currentDate: new Date(),
                errors: [],
                file: null,
                event: {
                    user_id: 1,
                    name: null,
                    description: null,
                    image: null,
                    location: null,
                    start_date: null,
                    start_time: null,
                    expiration_date: null,
                    expiration_time: null,
                },
                editor: {
                    toolbar: 'essential',
                    placeholder: '...',
                    theme: 'snow',
                },
                optionName: null,
                optionValue: null,
                options: [],
            };
        },
        props: {
            eventIdProps: String,
            eventProps: Object,
            optionProps: Object,
            baseUrlProps: String,
        },
        components: {
            AppFormWrapperComponent,
            InputText,
            QuillEditor,
            Card,
            Image,
            FileUpload,
            Calendar,
            Button
        },
        methods: {
            onUpload: async function() {
                let formData = new FormData();
                await formData.append("file", this.$refs.fileInput.files[0]);
                return uploadFileRequest(formData);
            },
            isValid: function(fields) {
                this.errors = fields
            },
            createFile: async function ()
            {
                let attributes = '<FILE>';
                try {
                    let inputFile = await this.onUpload()
                        .then(async (response) => {
                            console.log(response);
                            if ('error' in response.data) {
                                this.isValid(response.data.error.data);
                                return;
                            }
                            const data = response.data.attributes;
                            this.file = await data.key;
                        })
                        .catch(async (error) => {
                            console.log(error);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'uploadFileRequest',
                                status: error.code,
                                request_data: attributes.toString(),
                                message: error.message
                            });
                            this.messageError = MESSAGES.ERROR_ERROR;
                        });
                } catch (error) {

                }
            },
            updateEvent: async function()
            {
                let attributes = {
                    id: this.event.id,
                    name: this.event.name,
                    description: this.event.description,
                    location: this.event.location,
                    start_date: this.event.start_date,
                    start_time: this.event.start_time,
                    expiration_date: this.event.expiration_date,
                    expiration_time: this.event.expiration_time,
                };
                if (this.$refs.fileInput.files[0])
                {
                    attributes.image = this.file
                }
                await updateEventRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        if ('error' in response.data) {
                            this.isValid(response.data.error.data);
                            return;
                        }
                        const data = response.data.result.original;
                        this.event = await data.attributes;
                        this.$emit('messageSuccessEmit', MESSAGES.FORM_SUCCESS);
                        this.$emit('triggerEmit', 'update');
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'updateEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.$emit('messageErrorEmit', MESSAGES.ERROR_ERROR);
                    });
            },
            createEvent: async function ()
            {
                let attributes = {
                    user_id: window.$cookies.get(IDENTIFIER),
                    name: this.event.name,
                    description: this.event.description,
                    image: this.file,
                    location: this.event.location,
                    start_date: this.event.start_date,
                    start_time: this.event.start_time,
                    expiration_date: this.event.expiration_date,
                    expiration_time: this.event.expiration_time,
                };
                await createEventRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        if ('error' in response.data) {
                            this.isValid(response.data.error.data);
                            return;
                        }
                        const data = response.data.result.original;
                        this.event = await data.attributes;
                        this.$emit('messageSuccessEmit', MESSAGES.FORM_SUCCESS);
                        this.$emit('triggerEmit', 'create');
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'createEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.$emit('messageErrorEmit', MESSAGES.ERROR_ERROR);
                    });
            },
            addOption: function()
            {
                this.options = [...this.options, {
                    name: this.optionName,
                    type: 'string', //TODO: в ясный и прекрасный день вернуться к вопросу о прокидывании типа
                    value: this.optionValue
                }];
                this.optionName = null;
                this.optionValue = null;
            },
            removeOption: async function (idx)
            {
                if ('id' in this.options[idx])
                {
                    const attributes = {
                        id: this.options[idx].id
                    };
                    await deleteOptionRequest(attributes)
                        .then((response) => {
                            console.log(response);
                            if ('error' in response.data) {
                                this.isValid(response.data.error.data);
                                return;
                            }
                        })
                        .catch(async(error) => {
                            console.log(error);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'deleteOptionRequest',
                                status: error.code,
                                request_data: attributes.toString(),
                                message: error.message
                            });
                            this.messageError = MESSAGES.ERROR_ERROR;
                        });
                }
                this.options.splice(idx, 1);
            },
            createOptions: async function ()
            {
                console.log(this.options)
                const eventKey = this.event.key;
                for (let i=0; i < this.options.length; i++)
                {
                    let attributes = {
                        event_key: eventKey,
                        entity: 'event',
                        name: this.options[i].name,
                        value: this.options[i].value,
                        type: this.options[i].type,
                    };
                    await createEventOptionRequest(attributes)
                        .then(async (response) => {
                            console.log(response);
                            if ('error' in response.data) {
                                this.isValid(response.data.error.data);
                                return;
                            }
                            this.event = await response.data.result.original;
                        })
                        .catch(async (error) => {
                            console.log(response);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'createEventOptionRequest',
                                status: error.code,
                                request_data: attributes.toString(),
                                message: error.message
                            });
                            this.messageError = MESSAGES.ERROR_ERROR;
                        });
                }
            },
            updateOrCreateOptions: async function ()
            {
                for (let i=0; i < this.options.length; i++)
                {
                    if (Object.hasOwn(this.options[i], 'id'))
                    {
                        let attributes = {
                            event_key: this.event.key,
                            entity: 'event',
                            name: this.options[i].name,
                            value: this.options[i].value,
                            type: this.options[i].type,
                        };
                        await updateEventOptionRequest(attributes)
                            .then(async (response) => {
                                console.log(response);
                                if ('error' in response.data) {
                                    this.isValid(response.data.error.data);
                                    return;
                                }
                                this.options = response.data.result.original;
                            })
                            .catch(async (error) => {
                                console.log(error);
                                await createLogOptionRequest({
                                    current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                    current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                    method: 'updateEventOptionRequest',
                                    status: error.code,
                                    request_data: attributes.toString(),
                                    message: error.message
                                });
                                this.messageError = MESSAGES.ERROR_ERROR;
                            });
                    }
                    else
                    {
                        const attributes = {
                            event_key: this.event.key,
                            entity: 'event',
                            name: this.options[i].name,
                            value: this.options[i].value,
                            type: 'string',
                        };
                        await createEventOptionRequest(attributes)
                            .then(async (response) => {
                                console.log(response);
                                if ('error' in response.data) {
                                    this.isValid(response.data.error.data);
                                    return;
                                }
                                this.options = await response.data.result.original;
                            })
                            .catch(async (error) => {
                                console.log(error);
                                await createLogOptionRequest({
                                    current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                    current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                    method: 'createEventOptionRequest',
                                    status: error.code,
                                    request_data: option.toString(),
                                    message: error.message
                                });
                                this.messageError = MESSAGES.ERROR_ERROR;
                            });
                    }
                }
            },
            createEventObject: async function ()
            {
                await this.createFile();
                await this.createEvent();
                await this.createOptions();
                window.location = this.baseUrlProps + ENDPOINTS.BASE;

            },
            updateEventObject: async function()
            {
                await this.createFile();
                await this.updateEvent();
                await this.updateOrCreateOptions();
                window.location = this.baseUrlProps + ENDPOINTS.BASE;
            },
        },
        watch: {
            baseUrlProps: {
                handler(data) {
                    this.baseUrl = data;
                },
                immediate: true,
                deep: true
            },
            eventProps: {
                handler(data) {
                    if (data) {
                        this.event = data;
                    }
                },
                immediate: true,
                deep: true
            },
            optionProps: {
                handler(data) {
                    if (data.length > 0) {
                        this.options = data;
                    }
                },
                immediate: true,
                deep: true
            },
        }
    }
</script>

<template>
    <section class="container d-flex d-between d-flex-wrap wrap">
        <AppFormWrapperComponent class="w-50">
        <div class="form-block">
            <label for="#">Введите название мероприятия</label>
            <InputText type="text"
                       v-model="event.name"
                       class="w-100"
                       :invalid="this.errors !== null && 'name' in this.errors" />
            <section id="errorField" v-if="this.errors !== null && 'name' in this.errors">
                <small v-for="error in this.errors.name">
                    <i class="pi pi-times-circle"></i> {{ error }}
                </small>
            </section>
        </div>
        <div class="form-block">
            <label for="#">Введите описание</label>
            <QuillEditor ref="editor"
                         v-model:content="event.description"
                         :options="editor"
                         content-type="html"
                         :invalid="this.errors !== null && 'description' in this.errors" />
            <section id="errorField" v-if="this.errors !== null && 'description' in this.errors">
                <small v-for="error in this.errors.description">
                    <i class="pi pi-times-circle"></i> {{ error }}
                </small>
            </section>
        </div>
        <div class="form-block">
            <Card>
                <template #content>
                    <section v-if="this.eventIdProps" class="mb-3">
                        <p>Текущий баннер события:</p>
                        <Image :src="this.baseUrlProps + 'storage/uploads/' + event.image.name"
                               :alt="event.name"
                               style="display:block;"
                               id="detail-image"
                               preview />
                    </section>
                    <label class="text-center" for="#">Добавьте баннер</label>
                    <br>
                    <section class="d-flex d-center">
                        <!-- TODO: разрешать загрузку баннера размеров 600х602 -->
                        <FileUpload ref="fileInput"
                                    mode="basic"
                                    name="file"
                                    accept="image/*"
                                    :maxFileSize="1000000"
                                    chooseLabel="Загрузить" />
                        <section id="errorField" v-if="this.errors !== null && 'image' in this.errors">
                            <small v-for="error in this.errors.image">
                                <i class="pi pi-times-circle"></i> {{ error }}
                            </small>
                        </section>
                    </section>
                </template>
            </Card>
        </div>
        <div class="form-block">
            <label for="#">Укажите место проведения мероприятия</label>
            <InputText type="text"
                       v-model="event.location"
                       class="w-100"
                       :invalid="this.errors !== null && 'description' in this.errors" />
            <small id="username-help">Желательный формат: Город, улица, номер дома</small>
            <section id="errorField" v-if="this.errors !== null && 'location' in this.errors">
                <small v-for="error in this.errors.location">
                    <i class="pi pi-times-circle"></i> {{ error }}
                </small>
            </section>
        </div>
        <div class="d-flex d-between wrap">
            <div class="form-block w-70">
                <label for="#">Укажите дату старта</label>
                <Calendar v-model="event.start_date"
                          class="w-70"
                          :invalid="this.errors !== null && 'start_date' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'start_date' in this.errors">
                    <small v-for="error in this.errors.start_date">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
            <div class="form-block w-70">
                <label for="#">Укажите время старта</label>
                <InputText type="time"
                           v-model="event.start_time"
                           class="w-70"
                           :invalid="this.errors !== null && 'start_time' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'start_time' in this.errors">
                    <small v-for="error in this.errors.start_time">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </div>
        <div class="d-flex d-between wrap">
            <div class="form-block w-70">
                <label for="#">Укажите дату старта</label>
                <Calendar v-model="event.expiration_date"
                          class="w-70"
                          :invalid="this.errors !== null && 'expiration_date' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'expiration_date' in this.errors">
                    <small v-for="error in this.errors.expiration_date">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
            <div class="form-block w-70">
                <label for="#">Укажите время старта</label>
                <InputText type="time"
                           v-model="event.expiration_time"
                           class="w-70"
                           :invalid="this.errors !== null && 'expiration_time' in this.errors" />
                <section id="errorField" v-if="this.errors !== null && 'expiration_time' in this.errors">
                    <small v-for="error in this.errors.expiration_time">
                        <i class="pi pi-times-circle"></i> {{ error }}
                    </small>
                </section>
            </div>
        </div>
        <section class="mb-5 mobile-none">
            <Button v-if="this.eventIdProps == null"
                    type="button"
                    label="Создать событие"
                    class="w-100 mt-3"
                    severity="success"
                    @click="this.createEventObject" />
            <Button v-else
                    type="button"
                    label="Обновить"
                    class="w-100 mt-3"
                    severity="success"
                    @click="this.updateEventObject" />
        </section>
        <br>
    </AppFormWrapperComponent>
        <section class="w-25" style="
                position: relative;
                left: -10%;
                @media(max-width: 480px) {
                    position: relative;
                    left: 0%;
                }
            ">
            <Card>
                <template #header>
                    <h4 class="text-center">Параметры</h4>
                </template>
                <template #content>
                    <form>
                        <section class="mb-3">
                            <div class="mb-3">
                                <label for="#">Название параметра</label>
                                <InputText type="text"
                                           v-model="this.optionName"
                                           class="w-100" />
                            </div>
                            <div class="mb-3">
                                <label for="#">Значение параметра</label>
                                <InputText type="text"
                                           v-model="this.optionValue"
                                           class="w-100"
                                           :invalid="this.errors !== null && 'value' in this.errors" />
                                <section id="errorField" v-if="this.errors !== null && 'value' in this.errors">
                                    <small v-for="error in this.errors.value">
                                        <i class="pi pi-times-circle"></i> {{ error }}
                                    </small>
                                </section>
                            </div>
                            <Button type="button"
                                    label="Добавить параметр"
                                    class="w-100 mt-3"
                                    severity="success"
                                    @click="this.addOption" />
                        </section>
                    </form>
                </template>
            </Card>
            <section class="mt-3">
                <Card v-for="(option, idx) in this.options" class="mt-1">
                    <template #header>
                        <div class="d-flex d-end" style="
                                position: relative;
                                right: 5%;
                                top: 0.7em;
                            ">
                            <i class="pi pi-times pointer" @click="this.removeOption(idx)"></i>
                        </div>
                    </template>
                    <template #content>
                        <strong>{{ option.name }}</strong><br>
                        <span>{{ option.value }}</span>
                    </template>
                </Card>
            </section>
        </section>

        <section class="mb-5 mobile-view w-100">
            <Button v-if="this.eventIdProps == null"
                    type="button"
                    label="Создать событие"
                    class="w-100 mt-3"
                    severity="success"
                    @click="this.createEventObject" />
            <Button v-else
                    type="button"
                    label="Обновить"
                    class="w-100 mt-3"
                    severity="success"
                    @click="this.updateEventObject" />
        </section>
    </section>
</template>
