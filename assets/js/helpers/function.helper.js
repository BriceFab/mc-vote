export function getObjectKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}
