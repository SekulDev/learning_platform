import { instance } from "./instance";

export async function uploadFile(file: File) {
    const formData = new FormData();
    formData.append("file", file);

    const { data } = await instance.post("/media", formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });

    return data;
}
