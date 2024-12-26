import axios from "axios";

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
