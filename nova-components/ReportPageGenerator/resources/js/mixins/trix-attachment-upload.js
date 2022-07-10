import { uuidv4 } from './uuid';

const draftId = uuidv4();

/**
 * Initiate an attachement upload
 */
export function handleFileAdded({ attachment }) {
    if (attachment.file) {
        uploadAttachment(attachment);
    }
}

/**
 * Upload an attachment
 */
export function uploadAttachment(attachment) {
    const data = new FormData();
    data.append('Content-Type', attachment.file.type);
    data.append('attachment', attachment.file);
    data.append('draftId', draftId);

    Nova.request()
        .post(`/nova-vendor/report-page-generator/trix-attachment`, data, {
            onUploadProgress: function (progressEvent) {
                attachment.setUploadProgress(
                    Math.round((progressEvent.loaded * 100) / progressEvent.total)
                );
            },
        })
        .then(({ data: { url } }) => {
            return attachment.setAttributes({
                url: url,
                href: url,
            });
        })
        .catch((error) => {
            Nova.error('An error occured while uploading your file.');
        });
}

/**
 * Remove an attachment from the server
 */
export function handleFileRemoved({ attachment: { attachment } }) {
    Nova.request()
        .delete(`/nova-vendor/report-page-generator/trix-attachment`, {
            params: {
                attachmentUrl: attachment.attributes.values.url,
            },
        })
        .then((response) => {
            Nova.success(response.data);
        })
        .catch((error) => {
            Nova.error(error);
        });
}
