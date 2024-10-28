<script>
    import Button from 'primevue/button';

    export default {
        data() {
            return {
                user: null
            }
        },
        components: {
            Button
        },
        methods: {
            handleGoogleUserInfo: async function() {
                // TODO: вынести этот венигрет в .env
                const clientId = '28317098568-4q0rhn7qmq74s1jv6cll29gldosdd7bl.apps.googleusercontent.com';
                const redirectUri = 'http://localhost:8080/';
                const scope = 'profile email';
                const responseType = 'token';
                const url = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}&redirect_uri=${redirectUri}&response_type=${responseType}&scope=${scope}`;
                const authWindow = window.open(url, 'GoogleAuth', 'width=500,height=600');

                const checkAuthWindow = setInterval(() => {
                    try {
                        if (authWindow.closed) {
                            clearInterval(checkAuthWindow);
                            return;
                        }

                        const urlHash = authWindow.location.hash;
                        if (urlHash) {
                            const token = new URLSearchParams(urlHash.substring(1)).get('access_token');

                            if (token) {
                                console.log('Access Token:', token);
                                authWindow.close();
                                clearInterval(checkAuthWindow);
                                axios.get('https://www.googleapis.com/oauth2/v3/userinfo', {
                                    headers: {
                                        Authorization: `Bearer ${token}`,
                                    }
                                })
                                    .then((response) => {
                                        console.log(response)
                                    })
                                    .catch((error) => {

                                    });

                            }
                        }
                    } catch (e) {
                        // Ошибка возникает при попытке доступа к окну на другом домене
                    }
                }, 1000);

            },
            handleVkUserInfo: async function() {
                // TODO: вынести этот венигрет в .env
                const clientId = '51572081';
                const redirectUri = 'http://localhost:8080/';

                const VKID = window.VKIDSDK;
                VKID.Config.init({
                    app: clientId,
                    redirectUrl: redirectUri,
                    state: 'dj29fnsadjsd82',
                    codeVerifier: 'FGH767Gd65',
                    scope: 'email phone',
                });

                const oauthList = new VKID.OAuthList();
                oauthList.on(VKID.OAuthListInternalEvents.LOGIN_SUCCESS, function (payload) {
                    console.log(payload);
                });
            }
        }
    }
</script>

<template>
    <div class="mt-3"></div>
    <section class="mb-1">
        <Button icon="pi pi-google"
                label="Войти через Google аккаунт"
                class="w-100"
                style="
                    background-color: #ed572d;
                    border-color: #ed572d;
                    color: #fff;
                "
                @click="this.handleGoogleUserInfo"/>
    </section>
    <section class="mb-1">
        <Button label="Войти через ВКонтакте"
                class="w-100"
                style="
                    background-color: rgb(0, 119, 255);
                    border-color: rgb(0, 119, 255);
                    color: rgb(255, 255, 255);
                "
                @click="this.handleVkUserInfo"/>
    </section>
</template>

