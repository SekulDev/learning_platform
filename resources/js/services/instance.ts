import axios from "axios";

function getJwtFromCookies(): string | null {
    const cookies = document.cookie.split("; ");
    const jwtCookie = cookies.find((cookie) => cookie.startsWith("jwt="));
    return jwtCookie ? jwtCookie.split("=")[1] : null;
}

export const instance = axios.create({
    baseURL: "/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: `Bearer ${getJwtFromCookies()}`,
    },
});
