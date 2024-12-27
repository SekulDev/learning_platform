import axios, { AxiosRequestConfig, AxiosResponse } from "axios";

function getJwtFromCookies(): string | null {
    const KEY = "jwt";
    return (
        decodeURIComponent(
            document.cookie.replace(
                new RegExp(
                    "(?:(?:^|.*;)\\s*" +
                        encodeURIComponent(KEY).replace(/[\-.+*]/g, "\\$&") +
                        "\\s*\\=\\s*([^;]*).*$)|^.*$",
                ),
                "$1",
            ),
        ) || null
    );
}

const instance = axios.create({
    baseURL: "/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

instance.interceptors.request.use((data) => {
    data.headers.Authorization = `Bearer ${getJwtFromCookies()}`;
    return data;
});

export { instance };

export const queryRequest = <T>(options: AxiosRequestConfig) => {
    const onSuccess = (response: AxiosResponse<T>) => {
        return response?.data;
    };

    const onError = (error: any) => {
        return Promise.reject(error.response?.data);
    };

    return instance(options).then(onSuccess).catch(onError);
};
