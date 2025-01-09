import * as React from "react";
import type { ClientUploadedFileData } from "uploadthing/types";
import { toast } from "sonner";
import { z } from "zod";
import { uploadFile } from "@/services/media-service";

export interface UploadedFile<T = unknown> extends ClientUploadedFileData<T> {}

interface UseUploadFileProps {
    onUploadComplete?: (file: UploadedFile) => void;
    onUploadError?: (error: unknown) => void;
}

export function useUploadFile({
    onUploadComplete,
    onUploadError,
}: UseUploadFileProps = {}) {
    const [uploadedFile, setUploadedFile] = React.useState<UploadedFile>();
    const [uploadingFile, setUploadingFile] = React.useState<File>();
    const [progress, setProgress] = React.useState<number>(0);
    const [isUploading, setIsUploading] = React.useState(false);

    async function uploadThing(file: File) {
        setIsUploading(true);
        setUploadingFile(file);

        try {
            const res = await uploadFile(file);
            setUploadedFile(res[0]);
            onUploadComplete?.(res[0]);

            return uploadedFile;
        } catch (error) {
            const errorMessage = getErrorMessage(error);

            const message =
                errorMessage.length > 0
                    ? errorMessage
                    : "Something went wrong, please try again later.";

            toast.error(message);

            onUploadError?.(error);

            return undefined;
        } finally {
            setProgress(0);
            setIsUploading(false);
            setUploadingFile(undefined);
        }
    }

    return {
        isUploading,
        progress,
        uploadFile: uploadThing,
        uploadedFile,
        uploadingFile,
    };
}

export function getErrorMessage(err: unknown) {
    const unknownError = "Something went wrong, please try again later.";

    if (err instanceof z.ZodError) {
        const errors = err.issues.map((issue) => {
            return issue.message;
        });

        return errors.join("\n");
    } else if (err instanceof Error) {
        return err.message;
    } else {
        return unknownError;
    }
}
