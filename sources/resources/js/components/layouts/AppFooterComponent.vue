<script>
import Image from "primevue/image";
import Button from "primevue/button";
import {WEB_URL} from "../../common/route/web";
import {ENDPOINTS} from "../../common/route/api";
import {TOKEN} from "../../common/fields";


export default {
    data() {
      return {
          webUrl: WEB_URL,
          postscriptText: 'Продолжая использовать наш сайт, вы даете согласие на обработку файлов cookies и других пользовательских данных, в соответствии с ',
          postscriptUrlText: 'Политикой конфиденциальности',
          postscriptUrl: '#',
          footerMenu: [
              [
                  {
                      text: 'События',
                      url: WEB_URL + ENDPOINTS.EVENT,
                  },
                  {
                      text: 'Вход',
                      url: WEB_URL + ENDPOINTS.LOGIN,
                  },
              ],
              [
                  {
                      text: 'Правила использования',
                      url: '#',
                  },
                  {
                      text: 'Договор оферты',
                      url: '#',
                  },
                  {
                      text: 'Условия использования файлов cookies',
                      url: '#'
                  }
              ]
          ]
      }
    },
    components: {
        Image,
        Button
    },
    async beforeMount() {
        if (window.$cookies.isKey(TOKEN)) {
            let itemIdx = this.footerMenu[0].indexOf((item) => { return item.text === 'Вход'; });
            this.footerMenu[0].splice(itemIdx, 1);

        }
    }
}
</script>

<template>
    <footer class="mt-5 p-2" style="background-color: var(--surface-900);">
        <div class="d-flex d-between d-align-center mb-3">
            <div class="w-30">
                <a :href="this.webUrl">
                    <Image :src="this.webUrl +'images/logo.png'" alt="Image" width="290" />
                </a>
            </div>
            <div class="w-70 d-flex d-end d-align-center">
                <div v-for="items in this.footerMenu" class="w-30">
                    <section v-for="item in items"
                             :key="item.text">
                        <a :href="item.url">
                            <Button :label="item.text"
                                    link />
                        </a>
                    </section>
                </div>
            </div>
        </div>
        <hr>
        <div id="footerEnd" class="d-flex d-center d-align-center">
            <p>{{ this.postscriptText }} <a :href="this.postscriptUrl">{{ this.postscriptUrlText }}</a></p>
        </div>
        <div id="footerEnd" class="d-flex d-center d-align-center">
            <small>© 2024</small>
        </div>
    </footer>
</template>

<style scoped>
    .p-button.p-button-link,
    #footerEnd p,
    #footerEnd p a,
    #footerEnd small{
        color: #fff;
    }
    #footerEnd p a{
        text-decoration: underline;
    }
</style>
