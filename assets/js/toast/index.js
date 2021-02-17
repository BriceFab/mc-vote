import '../../styles/toast/index.scss';
import {getObjectKeyByValue} from "../helpers/function.helper";

const toastr = require('toastr');
const $ = require('jquery');

toastr.options.progressBar = true;
toastr.options.preventDuplicates = true;

$(() => {
    const divToasts = $('#toasts');
    if (divToasts.length > 0) {
        const toasts = divToasts.data('toasts');
        const toasts_types = Object.keys(toasts);
        if (toasts && toasts_types && toasts_types.length > 0) {
            toasts_types.forEach(((type, index) => {
                const messages = toasts[type];
                const toast_type = TOASTS_TYPE[getObjectKeyByValue(TOASTS_TYPE, type)];

                if (messages && messages.length > 0 && toast_type) {
                    messages.forEach(message => {
                        displayToast(message, toast_type);
                    })
                }
            }))
        }
    }
});

export const TOASTS_TYPE = {
    ERROR: 'error',
    SUCCESS: 'success',
    WARNING: 'warning',
    INFO: 'info',
};

export function displayToast(message, type = TOASTS_TYPE.INFO) {
    const toast_fct = toastr[type];
    if (toast_fct) {
        toast_fct(message);
    }
}
