import axios from "axios";
import {JSON_RPC_VERSION} from "../../common/rpc";

/**
 * Класс занимается сборкой реквестов, респонсы нужно смотреть в реализациях
 * не использовать напрямую.
 * @class
 * @name TBuilder
 */
export class RequestBuilder {
    constructor() {
        this.url = null;
        this.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
        this.data = {
            'jsonrpc': JSON_RPC_VERSION,
            'id': '1',
            'notification': false,
            'method': '',
            'params': {}
        };
    }

    /**
     * Определение урла, к которому нужно обратиться
     * @param url
     * @returns {RequestBuilder}
     * @throws TypeError
     */
    setUrl(url) {
        this.url = url;
        return this;
    }

    /**
     * Определение метода, необходимо для JSON-RPC
     * @param method
     * @returns {RequestBuilder}
     * @throws TypeError
     */
    setMethod(method) {
        this.data.method = method;
        return this;
    }

    /**
     * Сборка тела параметров запроса
     * @param params
     * @returns {RequestBuilder}
     * @throws TypeError
     */
    setParams(params) {
        this.data.params = params;
        return this;
    }

    /**
     * Сборка заголовков
     * @param headers
     * @returns {RequestBuilder}
     * @throws TypeError
     */
    setHeaders(headers=null) {
        if (headers !== null) {
            this.headers = { ...this.headers, ...headers };
        }
        return this;
    }

    /**
     * Установка заголовка авторизации.
     * Необходим Bearer токен
     * @param accessToken
     */
    isAuth(accessToken) {
        this.headers['Authorization'] = accessToken;
    }

    /**
     * Отправляет запрос к серверу.
     * @returns {Promise<axios.AxiosResponse<any>>}
     * @throws Error
     */
    async execute(formData = null) {
        if (formData)
        {
            return await axios.post(this.url, formData, {
                headers: this.headers,
            });
        }
        return await axios.post(this.url, this.data, {
            headers: this.headers,
        });
    }
}
